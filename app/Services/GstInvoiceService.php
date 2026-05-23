<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class GstInvoiceService
{
    /**
     * Generate next GST invoice number
     * Format: GST-YYYY-NNNN
     */
    public function generateGstNumber()
    {
        $year = date('Y');
        
        // Get the last GST invoice number for current year
        $lastInvoice = Booking::where('is_gst_invoice', true)
            ->where('gst_invoice_number', 'LIKE', "GST-{$year}-%")
            ->orderBy('gst_invoice_number', 'desc')
            ->first();
        
        if ($lastInvoice) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastInvoice->gst_invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            // First invoice of the year
            $newNumber = 1;
        }
        
        return sprintf('GST-%s-%04d', $year, $newNumber);
    }
    
    /**
     * Calculate GST breakdown
     */
    public function calculateGst($amount, $rate)
    {
        $taxableAmount = $amount;
        $gstAmount = ($taxableAmount * $rate) / 100;
        $cgst = $gstAmount / 2;
        $sgst = $gstAmount / 2;
        $totalAmount = $taxableAmount + $gstAmount;
        
        return [
            'taxable_amount' => round($taxableAmount, 2),
            'gst_rate' => $rate,
            'gst_amount' => round($gstAmount, 2),
            'cgst' => round($cgst, 2),
            'sgst' => round($sgst, 2),
            'total_amount' => round($totalAmount, 2),
        ];
    }
    
    /**
     * Toggle invoice type and adjust sequence numbers
     */
    public function toggleInvoiceType(Booking $booking, $isGst, $gstRate = 5, $customerGstin = null)
    {
        DB::beginTransaction();
        
        try {
            if ($isGst) {
                // Converting TO GST
                $gstNumber = $this->generateGstNumber();
                $gstData = $this->calculateGst($booking->total_amount, $gstRate);
                
                $booking->is_gst_invoice = true;
                $booking->gst_invoice_number = $gstNumber;
                $booking->gst_rate = $gstRate;
                $booking->taxable_amount = $gstData['taxable_amount'];
                $booking->gst_amount = $gstData['gst_amount'];
                $booking->customer_gstin = $customerGstin;
                
            } else {
                // Converting FROM GST - need to adjust sequence
                $oldGstNumber = $booking->gst_invoice_number;
                
                $booking->is_gst_invoice = false;
                $booking->gst_invoice_number = null;
                $booking->gst_rate = null;
                $booking->taxable_amount = null;
                $booking->gst_amount = null;
                $booking->customer_gstin = null;
                
                // Adjust sequence numbers for invoices after this one
                if ($oldGstNumber) {
                    $this->adjustSequenceAfterRemoval($oldGstNumber);
                }
            }
            
            $booking->save();
            
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Adjust GST invoice numbers after one is removed
     */
    protected function adjustSequenceAfterRemoval($removedNumber)
    {
        // Extract year and number from removed invoice
        preg_match('/GST-(\d{4})-(\d{4})/', $removedNumber, $matches);
        if (count($matches) !== 3) {
            return;
        }
        
        $year = $matches[1];
        $removedSeq = (int) $matches[2];
        
        // Get all GST invoices with higher numbers in the same year
        $invoicesToAdjust = Booking::where('is_gst_invoice', true)
            ->where('gst_invoice_number', 'LIKE', "GST-{$year}-%")
            ->where('gst_invoice_number', '>', $removedNumber)
            ->orderBy('gst_invoice_number', 'asc')
            ->get();
        
        // Decrement each invoice number by 1
        foreach ($invoicesToAdjust as $invoice) {
            preg_match('/GST-(\d{4})-(\d{4})/', $invoice->gst_invoice_number, $currentMatches);
            $currentSeq = (int) $currentMatches[2];
            $newSeq = $currentSeq - 1;
            $invoice->gst_invoice_number = sprintf('GST-%s-%04d', $year, $newSeq);
            $invoice->save();
        }
    }
    
    /**
     * Determine GST rate based on services
     * Returns 12 for hotel services, 5 for others
     */
    public function determineGstRate($booking)
    {
        // Check if booking has quotation with items
        if ($booking->quotation && $booking->quotation->items) {
            foreach ($booking->quotation->items as $item) {
                $serviceType = $item->serviceTemplate->serviceType->name ?? '';
                
                // Check if it's a hotel service
                if (stripos($serviceType, 'hotel') !== false || 
                    stripos($serviceType, 'accommodation') !== false) {
                    return 12.00;
                }
            }
        }
        
        // Default to 5% for other services
        return 5.00;
    }
    
    /**
     * Update GST details for a booking
     */
    public function updateGstDetails(Booking $booking, $gstRate, $customerGstin = null)
    {
        if (!$booking->is_gst_invoice) {
            return false;
        }
        
        $gstData = $this->calculateGst($booking->total_amount, $gstRate);
        
        $booking->gst_rate = $gstRate;
        $booking->taxable_amount = $gstData['taxable_amount'];
        $booking->gst_amount = $gstData['gst_amount'];
        $booking->customer_gstin = $customerGstin;
        $booking->save();
        
        return true;
    }
}
