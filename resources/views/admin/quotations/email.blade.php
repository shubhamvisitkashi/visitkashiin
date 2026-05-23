<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .quotation-info {
            background: white;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-label {
            font-weight: bold;
            color: #6b7280;
        }
        .info-value {
            color: #111;
        }
        .total-amount {
            font-size: 24px;
            color: #059669;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Quotation</h1>
            <p>{{ $quotation->quotation_number }}</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $quotation->lead->guest_name }},</p>
            
            @if($customMessage)
            <p>{{ $customMessage }}</p>
            @else
            <p>Thank you for your interest. Please find attached the quotation for your requested services.</p>
            @endif
            
            <div class="quotation-info">
                <div class="info-row">
                    <span class="info-label">Quotation Number:</span>
                    <span class="info-value">{{ $quotation->quotation_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date:</span>
                    <span class="info-value">{{ $quotation->quotation_date->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Valid Until:</span>
                    <span class="info-value">{{ $quotation->valid_until ? $quotation->valid_until->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="info-row" style="border-bottom: none;">
                    <span class="info-label">Services:</span>
                    <span class="info-value">{{ $quotation->items->count() }} item(s)</span>
                </div>
            </div>
            
            <div class="total-amount">
                Total: ₹{{ number_format($quotation->total_amount, 2) }}
            </div>
            
            <p>Please review the attached PDF for complete details including services, itinerary, and terms.</p>
            
            <p>If you have any questions or would like to proceed with booking, please don't hesitate to contact us.</p>
            
            <p>Best regards,<br>
            <strong>Visit Kashi Team</strong></p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply directly to this message.</p>
            <p>&copy; {{ date('Y') }} Visit Kashi. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
