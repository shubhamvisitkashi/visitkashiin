<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Festival Boat Booking — Visit Kashi</title>
</head>
<body style="margin:0;padding:0;background:#F0F4F8;font-family:'Segoe UI',Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">

@php
  $leadId  = $booking->booking_request_id;
  $recvAt  = $booking->created_at->format('d M Y, h:i A');
  $logoUrl = url('backend/admin/website_setup/' . websiteSetupValue('logo'));
  $boatName = $booking->boat->boatType->name ?? ($booking->boat->name ?? 'Festival Boat');
@endphp

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#F0F4F8;">
<tr><td align="center" style="padding:24px 12px;">

<table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 32px rgba(0,0,0,0.12);">

  <!-- HEADER -->
  <tr>
    <td style="background:linear-gradient(135deg,#7C2D12 0%,#B45309 55%,#D97706 100%);padding:0;">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td width="90" style="padding:24px 0 24px 28px;vertical-align:middle;">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr><td style="background:rgba(255,255,255,0.15);border-radius:12px;padding:8px;width:72px;height:72px;text-align:center;vertical-align:middle;">
                <img src="{{ $logoUrl }}" alt="VisitKashi" width="56" height="56"
                     style="display:block;width:56px;height:56px;object-fit:contain;border:0;outline:none;"
                     border="0">
              </td></tr>
            </table>
          </td>
          <td style="padding:24px 28px 24px 16px;vertical-align:middle;">
            <div style="color:#fff;font-size:22px;font-weight:800;letter-spacing:-0.5px;margin-bottom:4px;">
              {{ $paymentSubmitted ? 'Payment Submitted' : 'New Festival Boat Booking' }}
            </div>
            <div style="color:rgba(255,255,255,0.75);font-size:12px;">Dev Diwali Boat Booking — 5 Nov 2025</div>
          </td>
          <td width="110" style="padding:24px 28px 24px 0;vertical-align:middle;text-align:right;">
            <div style="background:rgba(255,255,255,0.18);border:1px solid rgba(255,255,255,0.28);border-radius:30px;padding:6px 14px;display:inline-block;">
              <div style="color:#fff;font-size:20px;line-height:1;margin-bottom:2px;text-align:center;">🛶</div>
              <div style="color:#fff;font-size:9px;font-weight:700;letter-spacing:0.5px;text-align:center;white-space:nowrap;">FESTIVAL BOAT</div>
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- META BAR -->
  <tr>
    <td style="background:#1e293b;padding:12px 28px;">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td style="color:rgba(255,255,255,0.5);font-size:10px;font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">BOOKING ID</td>
          <td style="color:rgba(255,255,255,0.5);font-size:10px;font-weight:600;letter-spacing:0.8px;text-transform:uppercase;text-align:center;">RECEIVED</td>
          <td style="color:rgba(255,255,255,0.5);font-size:10px;font-weight:600;letter-spacing:0.8px;text-transform:uppercase;text-align:right;">STATUS</td>
        </tr>
        <tr>
          <td style="color:#FBBF24;font-size:15px;font-weight:800;letter-spacing:1px;padding-top:3px;">{{ $leadId }}</td>
          <td style="color:#fff;font-size:12px;font-weight:600;text-align:center;padding-top:3px;">{{ $recvAt }}</td>
          <td style="text-align:right;padding-top:3px;">
            @if($paymentSubmitted)
              <span style="background:#F59E0B;color:#fff;font-size:10px;font-weight:800;padding:3px 10px;border-radius:20px;letter-spacing:0.5px;">💳 VERIFY PAYMENT</span>
            @else
              <span style="background:#10B981;color:#fff;font-size:10px;font-weight:800;padding:3px 10px;border-radius:20px;letter-spacing:0.5px;">🔥 NEW LEAD</span>
            @endif
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- ALERT BAR -->
  <tr>
    <td style="background:#FFFBEB;border-left:4px solid #D97706;padding:11px 28px;">
      <span style="color:#92400E;font-size:13px;font-weight:600;">
        @if($paymentSubmitted)
          ⚡ Action Required — Verify UTR &amp; screenshot, then confirm the booking
        @else
          ⚡ Action Required — Awaiting payment from guest
        @endif
      </span>
    </td>
  </tr>

  <!-- BODY -->
  <tr>
    <td style="padding:20px 28px 28px;">

      <!-- Boat -->
      <div style="margin-bottom:20px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#94a3b8;margin-bottom:8px;border-bottom:1px solid #f1f5f9;padding-bottom:6px;">🛶 BOAT BOOKED</div>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td style="background:linear-gradient(135deg,#FFFBEB,#FEF3C7);border:1px solid #FDE68A;border-radius:10px;padding:14px 18px;">
              <div style="color:#92400E;font-size:17px;font-weight:800;">{{ $boatName }}</div>
              <div style="color:#B45309;font-size:12px;margin-top:3px;">{{ $booking->no_of_person }} Person(s) · Dev Diwali, Varanasi</div>
            </td>
          </tr>
        </table>
      </div>

      <!-- Guest + Amount Details -->
      <div style="margin-bottom:20px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#94a3b8;margin-bottom:10px;border-bottom:1px solid #f1f5f9;padding-bottom:6px;">👤 GUEST &amp; PAYMENT DETAILS</div>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
          <tr style="background:#fff;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">👤 Guest Name</td>
            <td style="padding:11px 14px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $booking->name }}</td>
          </tr>
          <tr style="background:#f8fafc;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">📞 Phone</td>
            <td style="padding:11px 14px;font-size:14px;font-weight:700;color:#0369A1;border-bottom:1px solid #f1f5f9;">
              <a href="tel:{{ $booking->phone }}" style="color:#0369A1;text-decoration:none;">{{ $booking->phone }}</a>
            </td>
          </tr>
          <tr style="background:#fff;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">✉️ Email</td>
            <td style="padding:11px 14px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $booking->email }}</td>
          </tr>
          <tr style="background:#f8fafc;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">💰 Final Amount</td>
            <td style="padding:11px 14px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">₹{{ number_format($booking->final_amount, 2) }}</td>
          </tr>
          @if($paymentSubmitted)
          <tr style="background:#fff;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">🔖 UTR Number</td>
            <td style="padding:11px 14px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $utrNumber }}</td>
          </tr>
          <tr style="background:#ECFDF5;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#065F46;">🖼️ Payment Screenshot</td>
            <td style="padding:11px 14px;">
              <a href="{{ $screenshotUrl }}" style="color:#059669;font-weight:700;text-decoration:none;">View Screenshot →</a>
            </td>
          </tr>
          @endif
        </table>
      </div>

      <!-- Quick Actions -->
      <div style="margin-bottom:8px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#94a3b8;margin-bottom:10px;border-bottom:1px solid #f1f5f9;padding-bottom:6px;">⚡ QUICK ACTIONS</div>
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td style="padding-right:10px;">
              <a href="https://wa.me/91{{ preg_replace('/\D/', '', $booking->phone) }}?text={{ urlencode('Hi '.$booking->name.', thank you for booking with VisitKashi! Booking ID: '.$leadId) }}"
                 style="display:inline-block;background:#25D366;color:#ffffff;font-size:13px;font-weight:700;padding:12px 22px;border-radius:8px;text-decoration:none;">
                📱 WhatsApp Reply
              </a>
            </td>
            <td>
              <a href="tel:{{ $booking->phone }}"
                 style="display:inline-block;background:#B45309;color:#ffffff;font-size:13px;font-weight:700;padding:12px 22px;border-radius:8px;text-decoration:none;">
                📞 Call Guest
              </a>
            </td>
          </tr>
        </table>
      </div>

    </td>
  </tr>

  <!-- FOOTER -->
  <tr>
    <td style="background:#1e293b;padding:20px 28px;border-radius:0 0 16px 16px;">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
            <div style="color:#F59E0B;font-size:13px;font-weight:800;margin-bottom:4px;">VISITKASHI</div>
            <div style="color:rgba(255,255,255,0.45);font-size:11px;line-height:1.7;">
              Varanasi's #1 Spiritual Travel Platform &nbsp;·&nbsp; STAY &middot; CAB &middot; BOAT &middot; GUIDE<br>
              <a href="mailto:{{ websiteSetupValue('email') ?: 'info.visitkashi@gmail.com' }}" style="color:rgba(255,255,255,0.55);text-decoration:none;">{{ websiteSetupValue('email') ?: 'info.visitkashi@gmail.com' }}</a>
              &nbsp;|&nbsp; +91 {{ websiteSetupValue('contact_number') ?: '7080109919' }}
            </div>
          </td>
          <td style="text-align:right;vertical-align:middle;">
            <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);border-radius:8px;padding:8px 14px;display:inline-block;">
              <div style="color:rgba(255,255,255,0.40);font-size:9px;letter-spacing:0.5px;margin-bottom:2px;">BOOKING ID</div>
              <div style="color:#FBBF24;font-size:14px;font-weight:800;letter-spacing:1px;">{{ $leadId }}</div>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="padding-top:12px;border-top:1px solid rgba(255,255,255,0.07);">
            <div style="color:rgba(255,255,255,0.28);font-size:10px;text-align:center;padding-top:12px;">
              This is an automated booking notification from visitkashi.com · Please do not reply to this email
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

</table>
</td></tr>
</table>

</body>
</html>
