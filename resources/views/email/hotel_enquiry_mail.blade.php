<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Hotel Lead — Visit Kashi</title>
</head>
<body style="margin:0;padding:0;background:#F0F4F8;font-family:'Segoe UI',Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">

@php
  $leadId  = 'VK-H-' . str_pad($enquiry->id, 5, '0', STR_PAD_LEFT);
  $recvAt  = $enquiry->created_at->format('d M Y, h:i A');
  $logoUrl = url('backend/admin/website_setup/' . websiteSetupValue('logo'));
  $nights  = $enquiry->nights ?? 1;
  $checkin  = \Carbon\Carbon::parse($enquiry->checkin_datetime)->format('d M Y, h:i A');
  $checkout = \Carbon\Carbon::parse($enquiry->checkout_datetime)->format('d M Y, h:i A');
  $totalGuests = ($enquiry->adults ?? 0) + ($enquiry->kids ?? 0);
@endphp

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#F0F4F8;">
<tr><td align="center" style="padding:24px 12px;">

<table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 32px rgba(0,0,0,0.12);">

  <!-- ══ HEADER ══ -->
  <tr>
    <td style="background:linear-gradient(135deg,#0C4A6E 0%,#0369A1 55%,#0891B2 100%);padding:0;">
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
            <div style="color:#fff;font-size:22px;font-weight:800;letter-spacing:-0.5px;margin-bottom:4px;">New Hotel Enquiry</div>
            <div style="color:rgba(255,255,255,0.75);font-size:12px;">Explore Spiritual Varanasi with Trusted Local Experts</div>
          </td>
          <td width="110" style="padding:24px 28px 24px 0;vertical-align:middle;text-align:right;">
            <div style="background:rgba(255,255,255,0.18);border:1px solid rgba(255,255,255,0.28);border-radius:30px;padding:6px 14px;display:inline-block;">
              <div style="color:#fff;font-size:20px;line-height:1;margin-bottom:2px;text-align:center;">🏨</div>
              <div style="color:#fff;font-size:9px;font-weight:700;letter-spacing:0.5px;text-align:center;white-space:nowrap;">STAY BOOKING</div>
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- ══ LEAD META BAR ══ -->
  <tr>
    <td style="background:#1e293b;padding:12px 28px;">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td style="color:rgba(255,255,255,0.5);font-size:10px;font-weight:600;letter-spacing:0.8px;text-transform:uppercase;">LEAD ID</td>
          <td style="color:rgba(255,255,255,0.5);font-size:10px;font-weight:600;letter-spacing:0.8px;text-transform:uppercase;text-align:center;">RECEIVED</td>
          <td style="color:rgba(255,255,255,0.5);font-size:10px;font-weight:600;letter-spacing:0.8px;text-transform:uppercase;text-align:right;">STATUS</td>
        </tr>
        <tr>
          <td style="color:#38BDF8;font-size:15px;font-weight:800;letter-spacing:1px;padding-top:3px;">{{ $leadId }}</td>
          <td style="color:#fff;font-size:12px;font-weight:600;text-align:center;padding-top:3px;">{{ $recvAt }}</td>
          <td style="text-align:right;padding-top:3px;">
            <span style="background:#10B981;color:#fff;font-size:10px;font-weight:800;padding:3px 10px;border-radius:20px;letter-spacing:0.5px;">🔥 NEW LEAD</span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- ══ ALERT BAR ══ -->
  <tr>
    <td style="background:#EFF6FF;border-left:4px solid #0369A1;padding:11px 28px;">
      <span style="color:#1E40AF;font-size:13px;font-weight:600;">⚡ Action Required — Contact guest to confirm availability</span>
    </td>
  </tr>

  <!-- ══ STAY SUMMARY CARDS ══ -->
  <tr>
    <td style="padding:24px 28px 0;">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <!-- Nights -->
          <td width="33%" style="padding-right:8px;">
            <div style="background:linear-gradient(135deg,#0369A1,#0891B2);border-radius:10px;padding:14px;text-align:center;">
              <div style="color:rgba(255,255,255,0.75);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Total Nights</div>
              <div style="color:#fff;font-size:26px;font-weight:900;line-height:1;">{{ $nights }}</div>
              <div style="color:rgba(255,255,255,0.7);font-size:11px;margin-top:2px;">Night{{ $nights > 1 ? 's' : '' }}</div>
            </div>
          </td>
          <!-- Guests -->
          <td width="33%" style="padding:0 4px;">
            <div style="background:linear-gradient(135deg,#059669,#10B981);border-radius:10px;padding:14px;text-align:center;">
              <div style="color:rgba(255,255,255,0.75);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Total Guests</div>
              <div style="color:#fff;font-size:26px;font-weight:900;line-height:1;">{{ $totalGuests }}</div>
              <div style="color:rgba(255,255,255,0.7);font-size:11px;margin-top:2px;">{{ $enquiry->adults }}A + {{ $enquiry->kids }}K</div>
            </div>
          </td>
          <!-- Category -->
          <td width="33%" style="padding-left:8px;">
            <div style="background:linear-gradient(135deg,#7C3AED,#8B5CF6);border-radius:10px;padding:14px;text-align:center;">
              <div style="color:rgba(255,255,255,0.75);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Category</div>
              <div style="color:#fff;font-size:18px;font-weight:800;line-height:1.2;">Stay</div>
              <div style="color:rgba(255,255,255,0.7);font-size:11px;margin-top:2px;">{{ ucfirst($enquiry->category ?? 'Hotel') }}</div>
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- ══ BODY ══ -->
  <tr>
    <td style="padding:20px 28px 28px;">

      <!-- Hotel / Property -->
      <div style="margin-bottom:20px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#94a3b8;margin-bottom:8px;border-bottom:1px solid #f1f5f9;padding-bottom:6px;">🏨 PROPERTY ENQUIRED</div>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td style="background:linear-gradient(135deg,#EFF6FF,#DBEAFE);border:1px solid #BFDBFE;border-radius:10px;padding:14px 18px;">
              <div style="color:#1E40AF;font-size:17px;font-weight:800;">{{ $enquiry->hotel_name }}</div>
              <div style="color:#3B82F6;font-size:12px;margin-top:3px;">Varanasi, Uttar Pradesh</div>
            </td>
          </tr>
        </table>
      </div>

      <!-- Lead Details Table -->
      <div style="margin-bottom:20px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#94a3b8;margin-bottom:10px;border-bottom:1px solid #f1f5f9;padding-bottom:6px;">👤 GUEST DETAILS</div>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
          <tr style="background:#f8fafc;">
            <td width="42%" style="padding:8px 14px;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #e2e8f0;">Field</td>
            <td width="58%" style="padding:8px 14px;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #e2e8f0;">Details</td>
          </tr>
          <tr style="background:#fff;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">👤 Guest Name</td>
            <td style="padding:11px 14px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $enquiry->guest_name }}</td>
          </tr>
          <tr style="background:#f8fafc;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">📞 Contact</td>
            <td style="padding:11px 14px;font-size:14px;font-weight:700;color:#0369A1;border-bottom:1px solid #f1f5f9;">
              <a href="tel:{{ $enquiry->contact_number }}" style="color:#0369A1;text-decoration:none;">+91 {{ $enquiry->contact_number }}</a>
            </td>
          </tr>
          <tr style="background:#fff;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">👨‍👩‍👧‍👦 Adults (18+)</td>
            <td style="padding:11px 14px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $enquiry->adults }} Adult{{ $enquiry->adults > 1 ? 's' : '' }}</td>
          </tr>
          <tr style="background:#f8fafc;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">👶 Children (&lt;10 yrs)</td>
            <td style="padding:11px 14px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $enquiry->kids }} Child{{ $enquiry->kids != 1 ? 'ren' : '' }}</td>
          </tr>
          <tr style="background:#fff;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">✅ Check-In</td>
            <td style="padding:11px 14px;border-bottom:1px solid #f1f5f9;">
              <span style="background:#F0FDF4;color:#15803D;border:1px solid #BBF7D0;border-radius:6px;padding:4px 10px;font-size:13px;font-weight:700;display:inline-block;">{{ $checkin }}</span>
            </td>
          </tr>
          <tr style="background:#f8fafc;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">🔚 Check-Out</td>
            <td style="padding:11px 14px;border-bottom:1px solid #f1f5f9;">
              <span style="background:#FEF3C7;color:#B45309;border:1px solid #FDE68A;border-radius:6px;padding:4px 10px;font-size:13px;font-weight:700;display:inline-block;">{{ $checkout }}</span>
            </td>
          </tr>
          <tr style="background:#ECFDF5;">
            <td style="padding:11px 14px;font-size:12px;font-weight:600;color:#065F46;">🌙 Total Nights</td>
            <td style="padding:11px 14px;font-size:16px;font-weight:900;color:#059669;">{{ $nights }} Night{{ $nights > 1 ? 's' : '' }}</td>
          </tr>
        </table>
      </div>

      <!-- Quick Actions -->
      <div style="margin-bottom:8px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#94a3b8;margin-bottom:10px;border-bottom:1px solid #f1f5f9;padding-bottom:6px;">⚡ QUICK ACTIONS</div>
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td style="padding-right:10px;">
              <a href="https://wa.me/91{{ $enquiry->contact_number }}?text={{ urlencode('Hi '.$enquiry->guest_name.', thank you for enquiring about '.$enquiry->hotel_name.' at VisitKashi! Our team will confirm availability shortly. Lead ID: '.$leadId) }}"
                 style="display:inline-block;background:#25D366;color:#ffffff;font-size:13px;font-weight:700;padding:12px 22px;border-radius:8px;text-decoration:none;">
                📱 WhatsApp Reply
              </a>
            </td>
            <td style="padding-right:10px;">
              <a href="tel:+91{{ $enquiry->contact_number }}"
                 style="display:inline-block;background:#0369A1;color:#ffffff;font-size:13px;font-weight:700;padding:12px 22px;border-radius:8px;text-decoration:none;">
                📞 Call Guest
              </a>
            </td>
            <td>
              <a href="{{ url('/admin/enquiry-index') }}"
                 style="display:inline-block;background:#f1f5f9;color:#374151;border:1.5px solid #e2e8f0;font-size:13px;font-weight:700;padding:11px 20px;border-radius:8px;text-decoration:none;">
                📋 View in CRM
              </a>
            </td>
          </tr>
        </table>
      </div>

    </td>
  </tr>

  <!-- ══ FOOTER ══ -->
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
              <div style="color:rgba(255,255,255,0.40);font-size:9px;letter-spacing:0.5px;margin-bottom:2px;">LEAD ID</div>
              <div style="color:#38BDF8;font-size:14px;font-weight:800;letter-spacing:1px;">{{ $leadId }}</div>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="padding-top:12px;border-top:1px solid rgba(255,255,255,0.07);">
            <div style="color:rgba(255,255,255,0.28);font-size:10px;text-align:center;padding-top:12px;">
              This is an automated lead notification from visitkashi.com · Please do not reply to this email
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
