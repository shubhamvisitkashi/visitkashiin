<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class BookingReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnWidths, WithEvents
{
    protected $booking;
    protected $totalVendorCost;
    protected $totalMargin;
    protected $totalMarginPercent;
    protected $serviceTypeBreakdown;
    protected $vendorBreakdown;
    protected $currentRow = 1;

    public function __construct($bookingId)
    {
        $this->booking = Booking::with([
            'lead',
            'quotation.items.serviceTemplate',
            'quotation.items.serviceType',
            'serviceAssignments.serviceProvider',
            'serviceAssignments.quotationItem.serviceTemplate',
            'serviceAssignments.quotationItem.serviceType',
            'payments.paymentAccount',
            'createdBy'
        ])
        ->withSum('payments', 'amount')
        ->findOrFail($bookingId);

        // Calculate totals
        $this->totalVendorCost = $this->booking->serviceAssignments->sum('assigned_cost');
        $this->totalMargin = $this->booking->total_amount - $this->totalVendorCost;
        $this->totalMarginPercent = $this->booking->total_amount > 0 
            ? ($this->totalMargin / $this->booking->total_amount) * 100 
            : 0;

        // Calculate service type breakdown
        $this->serviceTypeBreakdown = [];
        foreach ($this->booking->serviceAssignments as $assignment) {
            if ($assignment->quotationItem && $assignment->quotationItem->serviceType) {
                $serviceTypeName = $assignment->quotationItem->serviceType->name;
                if (!isset($this->serviceTypeBreakdown[$serviceTypeName])) {
                    $this->serviceTypeBreakdown[$serviceTypeName] = 0;
                }
                $this->serviceTypeBreakdown[$serviceTypeName] += $assignment->assigned_cost;
            }
        }
        arsort($this->serviceTypeBreakdown);

        // Calculate vendor breakdown
        $this->vendorBreakdown = [];
        foreach ($this->booking->serviceAssignments as $assignment) {
            if ($assignment->serviceProvider) {
                $vendorName = $assignment->serviceProvider->name;
                if (!isset($this->vendorBreakdown[$vendorName])) {
                    $this->vendorBreakdown[$vendorName] = 0;
                }
                $this->vendorBreakdown[$vendorName] += $assignment->assigned_cost;
            }
        }
        arsort($this->vendorBreakdown);
    }

    public function collection()
    {
        $data = collect();

        // HEADER - Company Info
        $data->push(['VISITKASHI CRM - BOOKING REPORT', '', '', '', '', '', '', '', '']);
        $data->push(['', '', '', '', '', '', '', '', '']);

        // Booking Information Section
        $data->push(['BOOKING INFORMATION', '', '', '', '', '', '', '', '']);
        $data->push(['Booking Number:', $this->booking->booking_number, '', 'Booking Date:', $this->booking->booking_date->format('d M Y'), '', '', '', '']);
        $data->push(['Status:', ucfirst(str_replace('_', ' ', $this->booking->booking_status)), '', 'Created By:', $this->booking->createdBy->name ?? 'N/A', '', '', '', '']);
        $data->push(['', '', '', '', '', '', '', '', '']);

        // Customer Information Section
        $data->push(['CUSTOMER INFORMATION', '', '', '', '', '', '', '', '']);
        $data->push(['Guest Name:', $this->booking->lead->guest_name ?? 'N/A', '', 'Contact:', $this->booking->lead->contact ?? 'N/A', '', '', '', '']);
        if ($this->booking->lead->email) {
            $data->push(['Email:', $this->booking->lead->email, '', 'Group Size:', ($this->booking->lead->pax ?? 'N/A') . ' Person(s)', '', '', '', '']);
        } else {
            $data->push(['Group Size:', ($this->booking->lead->pax ?? 'N/A') . ' Person(s)', '', '', '', '', '', '', '']);
        }
        $data->push(['', '', '', '', '', '', '', '', '']);

        // Financial Summary Section
        $data->push(['FINANCIAL SUMMARY', '', '', '', '', '', '', '', '']);
        $data->push(['Metric', 'Amount', '', '', '', '', '', '', '']);
        $data->push(['Customer Payment', number_format($this->booking->total_amount, 2), '', '', '', '', '', '', '']);
        $data->push(['Vendor Payment', number_format($this->totalVendorCost, 2), '', '', '', '', '', '', '']);
        $data->push(['Net Profit', number_format($this->totalMargin, 2), '', '', '', '', '', '', '']);
        $data->push(['Profit Margin %', number_format($this->totalMarginPercent, 2) . '%', '', '', '', '', '', '', '']);
        $data->push(['', '', '', '', '', '', '', '', '']);

        // Service Type Breakdown
        if (count($this->serviceTypeBreakdown) > 0) {
            $data->push(['SPENDING BY SERVICE TYPE', '', '', '', '', '', '', '', '']);
            $data->push(['Service Type', 'Amount', '', '', '', '', '', '', '']);
            foreach ($this->serviceTypeBreakdown as $serviceType => $amount) {
                $data->push([$serviceType, number_format($amount, 2), '', '', '', '', '', '', '']);
            }
            $data->push(['TOTAL', number_format($this->totalVendorCost, 2), '', '', '', '', '', '', '']);
            $data->push(['', '', '', '', '', '', '', '', '']);
        }

        // Vendor Breakdown
        if (count($this->vendorBreakdown) > 0) {
            $data->push(['VENDOR PAYMENTS', '', '', '', '', '', '', '', '']);
            $data->push(['Vendor Name', 'Amount', '', '', '', '', '', '', '']);
            foreach ($this->vendorBreakdown as $vendor => $amount) {
                $data->push([$vendor, number_format($amount, 2), '', '', '', '', '', '', '']);
            }
            $data->push(['TOTAL', number_format($this->totalVendorCost, 2), '', '', '', '', '', '', '']);
            $data->push(['', '', '', '', '', '', '', '', '']);
        }

        // Services Breakdown Section
        $data->push(['SERVICES BREAKDOWN', '', '', '', '', '', '', '', '']);
        $data->push(['#', 'Service Name', 'Vendor', 'Date', 'Qty', 'Unit Price', 'Total', 'Vendor Cost', 'Margin']);

        if ($this->booking->quotation && $this->booking->quotation->items) {
            foreach ($this->booking->quotation->items as $index => $item) {
                $assignment = $this->booking->serviceAssignments->where('quotation_item_id', $item->id)->first();
                $vendorCost = $assignment ? $assignment->assigned_cost : 0;
                $totalPrice = $item->total_price ?? ($item->quantity * $item->unit_price);
                $margin = $totalPrice - $vendorCost;
                $marginPercent = $totalPrice > 0 ? ($margin / $totalPrice) * 100 : 0;

                $data->push([
                    $index + 1,
                    $item->serviceTemplate->name . ' (' . $item->serviceType->name . ')',
                    $assignment && $assignment->serviceProvider ? $assignment->serviceProvider->name : 'Not assigned',
                    $item->service_date ? $item->service_date->format('d M Y') : 'Not set',
                    $item->quantity,
                    number_format($item->unit_price, 2),
                    number_format($totalPrice, 2),
                    number_format($vendorCost, 2),
                    number_format($margin, 2) . ' (' . number_format($marginPercent, 1) . '%)'
                ]);
            }
        }

        $data->push([
            '',
            '',
            '',
            '',
            'TOTAL:',
            '',
            number_format($this->booking->total_amount, 2),
            number_format($this->totalVendorCost, 2),
            number_format($this->totalMargin, 2)
        ]);

        $data->push(['', '', '', '', '', '', '', '', '']);

        // Payment Status Section
        $data->push(['PAYMENT STATUS', '', '', '', '', '', '', '', '']);
        $data->push(['Description', 'Amount', '', '', '', '', '', '', '']);
        $data->push(['Total Amount', number_format($this->booking->total_amount, 2), '', '', '', '', '', '', '']);
        $data->push(['Paid Amount', number_format($this->booking->paid_amount, 2), '', '', '', '', '', '', '']);
        $data->push(['Pending Amount', number_format($this->booking->pending_amount, 2), '', '', '', '', '', '', '']);
        $paymentStatus = $this->booking->pending_amount <= 0 ? 'Paid' : ($this->booking->paid_amount > 0 ? 'Partial' : 'Unpaid');
        $data->push(['Payment Status', $paymentStatus, '', '', '', '', '', '', '']);

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function map($row): array
    {
        return $row;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 35,
            'C' => 25,
            'D' => 15,
            'E' => 12,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // This is kept for basic compatibility, detailed styling in registerEvents
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                
                // Main Header (Row 1)
                $sheet->mergeCells('A1:I1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Section Headers Styling
                $sectionHeaders = [3, 7, 11]; // Booking Info, Customer Info, Financial Summary
                $currentRow = 3;
                
                // Find all section headers dynamically
                for ($row = 1; $row <= $highestRow; $row++) {
                    $cellValue = $sheet->getCell('A' . $row)->getValue();
                    
                    if (in_array($cellValue, [
                        'BOOKING INFORMATION',
                        'CUSTOMER INFORMATION', 
                        'FINANCIAL SUMMARY',
                        'SPENDING BY SERVICE TYPE',
                        'VENDOR PAYMENTS',
                        'SERVICES BREAKDOWN',
                        'PAYMENT STATUS'
                    ])) {
                        $sheet->mergeCells('A' . $row . ':I' . $row);
                        $sheet->getStyle('A' . $row)->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 13,
                                'color' => ['rgb' => 'FFFFFF']
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '6366F1']
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_LEFT,
                                'vertical' => Alignment::VERTICAL_CENTER
                            ]
                        ]);
                        $sheet->getRowDimension($row)->setRowHeight(25);
                        
                        // Style the header row after section header
                        $nextRow = $row + 1;
                        if ($sheet->getCell('A' . $nextRow)->getValue()) {
                            $sheet->getStyle('A' . $nextRow . ':I' . $nextRow)->applyFromArray([
                                'font' => ['bold' => true, 'size' => 11],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'E0E7FF']
                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => Border::BORDER_THIN,
                                        'color' => ['rgb' => '9CA3AF']
                                    ]
                                ]
                            ]);
                        }
                    }
                }

                // Apply borders and alternating colors to data rows
                for ($row = 1; $row <= $highestRow; $row++) {
                    $cellValue = $sheet->getCell('A' . $row)->getValue();
                    
                    // Data rows (not headers, not empty)
                    if (!empty($cellValue) && 
                        !in_array($cellValue, ['BOOKING INFORMATION', 'CUSTOMER INFORMATION', 'FINANCIAL SUMMARY', 
                                               'SPENDING BY SERVICE TYPE', 'VENDOR PAYMENTS', 'SERVICES BREAKDOWN', 
                                               'PAYMENT STATUS', 'VISITKASHI CRM - BOOKING REPORT']) &&
                        !str_contains($cellValue, 'Metric') &&
                        !str_contains($cellValue, 'Service Type') &&
                        !str_contains($cellValue, 'Vendor Name') &&
                        !str_contains($cellValue, 'Description') &&
                        $cellValue != '#') {
                        
                        // Alternating row colors
                        if ($row % 2 == 0) {
                            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'F9FAFB']
                                ]
                            ]);
                        }
                        
                        // Add borders
                        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['rgb' => 'E5E7EB']
                                ]
                            ]
                        ]);
                    }
                    
                    // Total rows styling
                    if (in_array($cellValue, ['TOTAL', 'TOTAL:']) || str_contains($cellValue, 'TOTAL')) {
                        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                            'font' => ['bold' => true, 'size' => 11],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FEF3C7']
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_MEDIUM,
                                    'color' => ['rgb' => 'F59E0B']
                                ]
                            ]
                        ]);
                    }
                }

                // Center align numeric columns
                $sheet->getStyle('E1:I' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('A1:I' . $highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                
                // Wrap text for long content
                $sheet->getStyle('B1:B' . $highestRow)->getAlignment()->setWrapText(true);
            },
        ];
    }

    public function title(): string
    {
        return 'Booking Report';
    }
}
