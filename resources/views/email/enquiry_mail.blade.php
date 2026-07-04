<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>New Lead — Visit Kashi</title>
<!--[if mso]>
<noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
<![endif]-->
</head>
<body style="margin:0;padding:0;background:#F0F4F8;font-family:'Segoe UI',Arial,Helvetica,sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">

@php
  $leadId  = 'VK-' . str_pad(optional($enquiry)->id ?? rand(1000,9999), 5, '0', STR_PAD_LEFT);
  $recvAt  = now()->format('d M Y, h:i A');
  $logoUrl = url('backend/admin/website_setup/' . websiteSetupValue('logo'));

  // Use category passed from controller (preferred), or detect from package_name
  if (!empty($enq_category ?? null)) {
      $enqType = $enq_category;
      $typeIcon = $enq_icon ?? '📋';
      $typeColorMap = [
          'Boat Ride'    => '#0F766E','Cab Booking'  => '#D97706',
          'Stay / Hotel' => '#0891B2','Tour Package' => '#7C3AED',
          'Contact'      => '#6366F1','General'      => '#C94B20',
      ];
      $typeColor = $typeColorMap[$enqType] ?? '#C94B20';
  } else {
      $pname = strtolower($package_name ?? '');
      if      (str_contains($pname,'contact'))                                                                { $enqType='Contact';       $typeColor='#6366F1'; $typeIcon='✉️'; }
      elseif  (str_contains($pname,'hotel')||str_contains($pname,'homestay')||str_contains($pname,'stay'))    { $enqType='Stay / Hotel';  $typeColor='#0891B2'; $typeIcon='🏨'; }
      elseif  (str_contains($pname,'cab')||str_contains($pname,'car')||str_contains($pname,'taxi')||str_contains($pname,'seater')||str_contains($pname,'traveller')) { $enqType='Cab Booking';  $typeColor='#D97706'; $typeIcon='🚕'; }
      elseif  (str_contains($pname,'boat')||str_contains($pname,'ganga')||str_contains($pname,'aarti')||str_contains($pname,'cruise')) { $enqType='Boat Ride';    $typeColor='#0F766E'; $typeIcon='⛵'; }
      elseif  (str_contains($pname,'package')||str_contains($pname,'tour'))                                   { $enqType='Tour Package';  $typeColor='#7C3AED'; $typeIcon='🗺️'; }
      else                                                                                                     { $enqType='General';       $typeColor='#C94B20'; $typeIcon='📋'; }
  }

  // Dynamic header title & alert text
  $headerTitleMap = [
      'Boat Ride'    => 'New Boat Ride Enquiry',
      'Cab Booking'  => 'New Cab Booking Enquiry',
      'Stay / Hotel' => 'New Hotel / Stay Enquiry',
      'Tour Package' => 'New Tour Package Enquiry',
      'Contact'      => 'New Contact Message',
      'General'      => 'New Enquiry Received',
  ];
  $alertTextMap = [
      'Boat Ride'    => '⚡ New boat enquiry — confirm ghat & time slot with guest',
      'Cab Booking'  => '⚡ New cab booking — confirm vehicle & pickup location',
      'Stay / Hotel' => '⚡ New stay enquiry — check availability & confirm dates',
      'Tour Package' => '⚡ New tour enquiry — share itinerary & pricing',
      'Contact'      => '⚡ New message received — please reply promptly',
      'General'      => '⚡ Action Required — follow up with this lead as soon as possible',
  ];
  $headerTitle = $headerTitleMap[$enqType] ?? 'New Enquiry Received';
  $alertText   = $alertTextMap[$enqType]   ?? '⚡ Action Required — follow up promptly';

  // The public enquiry forms bundle extra fields (pickup/drop, trip type, food plan,
  // hotel category, luggage, roof carrier, notes, etc.) into one free-text `message`
  // field as "Label: value" lines. Parse them generically here so any label from any
  // form — current or future — shows up in the email instead of being silently dropped.
  $msgRows = [];
  if (!empty($messages) && trim($messages) !== '' && trim($messages) !== 'N/A') {
      $msgIconMap = [
          'trip type' => '🚗', 'pickup location' => '📍', 'pickup ghat' => '📍', 'pickup' => '📍',
          'drop' => '🏁', 'persons' => '👥', 'adults' => '👨‍👩‍👧', 'children (<10 yrs)' => '👶',
          'duration' => '📅', 'infants' => '🍼', 'date' => '🗓', 'time slot' => '⏰',
          'hotel category' => '🏨', 'food plan' => '🍽️', 'cab type' => '🚕',
          'luggage bags' => '🧳', 'roof carrier' => '📦', 'whatsapp' => '💬',
          'notes' => '📝', 'special notes' => '📝',
      ];
      foreach (preg_split('/\r\n|\r|\n/', trim($messages)) as $line) {
          $line = trim($line);
          if ($line === '') continue;
          if (str_contains($line, ':')) {
              [$label, $value] = explode(':', $line, 2);
              $label = trim($label);
              $value = trim($value);
          } else {
              $label = '';
              $value = $line;
          }
          if ($value === '') continue;
          $msgRows[] = [
              'icon'  => $msgIconMap[strtolower($label)] ?? '▪️',
              'label' => $label ?: 'Note',
              'value' => $value,
          ];
      }
  }
@endphp

<!-- Email Wrapper -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#F0F4F8;">
<tr><td align="center" style="padding:24px 12px;">

<!-- Main Card -->
<table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 32px rgba(0,0,0,0.12);">

  <!-- ══ HEADER ══ -->
  <tr>
    <td style="background:linear-gradient(135deg,#C94B20 0%,#E8652F 50%,#D4562A 100%);padding:0;">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <!-- Logo -->
          <td width="90" style="padding:24px 0 24px 28px;vertical-align:middle;">
            <table cellpadding="0" cellspacing="0" border="0">
              <tr><td style="background:rgba(255,255,255,0.15);border-radius:12px;padding:8px;width:72px;height:72px;text-align:center;vertical-align:middle;">
                <img src="{{ $logoUrl }}" alt="VisitKashi" width="56" height="56"
                     style="display:block;width:56px;height:56px;object-fit:contain;border:0;outline:none;"
                     border="0">
              </td></tr>
            </table>
          </td>
          <!-- Title -->
          <td style="padding:24px 28px 24px 16px;vertical-align:middle;">
            <div style="color:#fff;font-size:22px;font-weight:800;letter-spacing:-0.5px;margin-bottom:4px;">{{ $headerTitle }}</div>
            <div style="color:rgba(255,255,255,0.75);font-size:12px;">Explore Spiritual Varanasi with Trusted Local Experts</div>
          </td>
          <!-- Type badge -->
          <td width="110" style="padding:24px 28px 24px 0;vertical-align:middle;text-align:right;">
            <div style="background:rgba(255,255,255,0.18);border:1px solid rgba(255,255,255,0.28);border-radius:30px;padding:6px 14px;display:inline-block;">
              <div style="color:#fff;font-size:18px;line-height:1;margin-bottom:2px;text-align:center;">{{ $typeIcon }}</div>
              <div style="color:#fff;font-size:9px;font-weight:700;letter-spacing:0.5px;text-align:center;white-space:nowrap;">{{ $enqType }}</div>
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
          <td style="color:#F59E0B;font-size:15px;font-weight:800;letter-spacing:1px;padding-top:3px;">{{ $leadId }}</td>
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
    <td style="background:#FFF7ED;border-left:4px solid #EA580C;padding:11px 28px;">
      <span style="color:#9A3412;font-size:13px;font-weight:600;">{{ $alertText }}</span>
    </td>
  </tr>

  <!-- ══ BODY ══ -->
  <tr>
    <td style="padding:28px;">

      <!-- Package / Service -->
      <div style="margin-bottom:20px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#94a3b8;margin-bottom:8px;border-bottom:1px solid #f1f5f9;padding-bottom:6px;">📦 SERVICE / PACKAGE ENQUIRED</div>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td style="background:linear-gradient(135deg,#EEF2FF,#E0E7FF);border:1px solid #C7D2FE;border-radius:10px;padding:14px 18px;">
              <div style="color:#3730A3;font-size:16px;font-weight:800;line-height:1.4;">{{ $package_name }}</div>
              @if(!empty($booking_amount) && $booking_amount > 0)
              <div style="margin-top:6px;color:#059669;font-size:13px;font-weight:600;">Starting from ₹{{ number_format($booking_amount) }}</div>
              @endif
            </td>
          </tr>
        </table>
      </div>

      <!-- Quick Stat Cards -->
      <div style="margin-bottom:20px;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td width="33%" style="padding-right:8px;">
              <div style="background:linear-gradient(135deg,#0369A1,#0891B2);border-radius:10px;padding:14px;text-align:center;">
                <div style="color:rgba(255,255,255,0.75);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Persons</div>
                <div style="color:#fff;font-size:24px;font-weight:900;line-height:1;">{{ (!empty($no_of_person) && $no_of_person !== 'N/A') ? $no_of_person : '—' }}</div>
                <div style="color:rgba(255,255,255,0.7);font-size:11px;margin-top:2px;">{{ (isset($children_count) && $children_count > 0) ? '+' . $children_count . ' child' : 'Adults' }}</div>
              </div>
            </td>
            <td width="34%" style="padding:0 4px;">
              <div style="background:linear-gradient(135deg,{{ $typeColor }},{{ $typeColor }}CC);border-radius:10px;padding:14px;text-align:center;">
                <div style="color:rgba(255,255,255,0.75);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Category</div>
                <div style="color:#fff;font-size:18px;font-weight:800;line-height:1.2;">{{ $typeIcon }}</div>
                <div style="color:rgba(255,255,255,0.7);font-size:11px;margin-top:2px;">{{ $enqType }}</div>
              </div>
            </td>
            <td width="33%" style="padding-left:8px;">
              <div style="background:linear-gradient(135deg,#059669,#10B981);border-radius:10px;padding:14px;text-align:center;">
                <div style="color:rgba(255,255,255,0.75);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">Date</div>
                <div style="color:#fff;font-size:15px;font-weight:800;line-height:1.3;margin-top:4px;">{{ (!empty($arrival_date) && $arrival_date !== 'N/A') ? $arrival_date : '—' }}</div>
              </div>
            </td>
          </tr>
        </table>
      </div>

      <!-- Lead Details Table -->
      <div style="margin-bottom:20px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#94a3b8;margin-bottom:10px;border-bottom:1px solid #f1f5f9;padding-bottom:6px;">👤 COMPLETE LEAD DETAILS</div>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
          <tr style="background:#f8fafc;">
            <td width="38%" style="padding:8px 14px;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #e2e8f0;">Field</td>
            <td width="62%" style="padding:8px 14px;font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;border-bottom:1px solid #e2e8f0;">Details</td>
          </tr>
          <tr style="background:#fff;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">👤 Full Name</td>
            <td style="padding:10px 14px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $name }}</td>
          </tr>
          <tr style="background:#f8fafc;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">📞 Mobile (WhatsApp)</td>
            <td style="padding:10px 14px;font-size:14px;font-weight:700;color:#0369A1;border-bottom:1px solid #f1f5f9;">
              <a href="tel:{{ $phone }}" style="color:#0369A1;text-decoration:none;">+91 {{ $phone }}</a>
            </td>
          </tr>
          @if(!empty($no_of_person) && $no_of_person !== 'N/A')
          <tr style="background:#fff;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">👨‍👩‍👧 Adults</td>
            <td style="padding:10px 14px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $no_of_person }} Adult{{ (int)$no_of_person != 1 ? 's' : '' }}</td>
          </tr>
          @endif
          @if(isset($children_count) && $children_count > 0)
          <tr style="background:#f8fafc;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">👶 Children (&lt;10 yrs)</td>
            <td style="padding:10px 14px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $children_count }} Child{{ $children_count != 1 ? 'ren' : '' }}</td>
          </tr>
          @endif
          @if(!empty($arrival_date) && $arrival_date !== 'N/A')
          <tr style="background:#fff;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">🗓 Booking Date</td>
            <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">
              <span style="background:#FFF7ED;color:#C2410C;border:1px solid #FED7AA;border-radius:6px;padding:4px 10px;font-size:13px;font-weight:700;">{{ $arrival_date }}</span>
            </td>
          </tr>
          @endif
          @if(!empty($time_slot ?? null))
          <tr style="background:#f8fafc;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">⏰ Time Slot</td>
            <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">
              <span style="background:#EFF6FF;color:#1D4ED8;border:1px solid #BFDBFE;border-radius:6px;padding:4px 10px;font-size:13px;font-weight:700;">{{ $time_slot }}</span>
            </td>
          </tr>
          @endif
          @if(!empty($pickup_ghat ?? null))
          <tr style="background:#fff;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">📍 Pickup Ghat</td>
            <td style="padding:10px 14px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $pickup_ghat }}</td>
          </tr>
          @endif
          @if(!empty($checkin_time) && $checkin_time !== 'N/A')
          <tr style="background:#f8fafc;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">✅ Check-In</td>
            <td style="padding:10px 14px;font-size:13px;font-weight:600;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $checkin_time }}</td>
          </tr>
          @endif
          @if(!empty($checkout_time) && $checkout_time !== 'N/A')
          <tr style="background:#fff;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">🔚 Check-Out</td>
            <td style="padding:10px 14px;font-size:13px;font-weight:600;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $checkout_time }}</td>
          </tr>
          @endif
          @if(!empty($booking_amount) && $booking_amount > 0)
          <tr style="background:#ECFDF5;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#065F46;border-bottom:1px solid #f1f5f9;">💰 Package Price</td>
            <td style="padding:10px 14px;font-size:16px;font-weight:900;color:#059669;border-bottom:1px solid #f1f5f9;">₹{{ number_format($booking_amount) }}</td>
          </tr>
          @endif
          @if(isset($luggage_bags) && $luggage_bags > 0)
          <tr style="background:#fff;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#64748b;border-bottom:1px solid #f1f5f9;">🧳 Luggage Bags</td>
            <td style="padding:10px 14px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #f1f5f9;">{{ $luggage_bags }} Bag{{ $luggage_bags != 1 ? 's' : '' }}</td>
          </tr>
          @endif
          @if(!empty($roof_carrier ?? null))
          <tr style="background:#FFFBEB;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#92400E;border-bottom:1px solid #f1f5f9;">📦 Roof Carrier</td>
            <td style="padding:10px 14px;font-size:14px;font-weight:700;color:#92400E;border-bottom:1px solid #f1f5f9;">{{ $roof_carrier }} — Luggage rack on top</td>
          </tr>
          @endif
          @if(!empty($special_notes ?? null))
          <tr style="background:#F8FAFC;">
            <td style="padding:10px 14px;font-size:12px;font-weight:600;color:#64748b;vertical-align:top;border-bottom:1px solid #f1f5f9;">📝 Special Notes</td>
            <td style="padding:10px 14px;font-size:13px;color:#374151;line-height:1.6;">{!! nl2br(e($special_notes)) !!}</td>
          </tr>
          @endif
        </table>
      </div>

      @if(count($msgRows))
      <!-- Additional Details (parsed from the enquiry form's message field) -->
      <div style="margin-bottom:20px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#94a3b8;margin-bottom:10px;border-bottom:1px solid #f1f5f9;padding-bottom:6px;">🧾 ADDITIONAL DETAILS FROM FORM</div>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
          @foreach($msgRows as $i => $row)
          <tr style="background:{{ $i % 2 === 0 ? '#fff' : '#f8fafc' }};">
            <td width="38%" style="padding:10px 14px;font-size:12px;font-weight:600;color:#64748b;vertical-align:top;{{ !$loop->last ? 'border-bottom:1px solid #f1f5f9;' : '' }}">{{ $row['icon'] }} {{ $row['label'] }}</td>
            <td style="padding:10px 14px;font-size:13px;font-weight:600;color:#0f172a;line-height:1.6;{{ !$loop->last ? 'border-bottom:1px solid #f1f5f9;' : '' }}">{!! nl2br(e($row['value'])) !!}</td>
          </tr>
          @endforeach
        </table>
      </div>
      @endif

      <!-- Quick Actions -->
      <div style="margin-bottom:20px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:#94a3b8;margin-bottom:10px;border-bottom:1px solid #f1f5f9;padding-bottom:6px;">⚡ QUICK ACTIONS</div>
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td style="padding-right:10px;">
              <a href="https://wa.me/91{{ preg_replace('/\D/','',$phone) }}?text={{ urlencode('Hi '.$name.', thank you for enquiring about '.$package_name.' at VisitKashi! Our team will assist you shortly. Lead ID: '.$leadId) }}"
                 style="display:inline-block;background:#25D366;color:#ffffff;font-size:13px;font-weight:700;padding:12px 22px;border-radius:8px;text-decoration:none;">
                📱 WhatsApp Reply
              </a>
            </td>
            <td style="padding-right:10px;">
              <a href="tel:+91{{ preg_replace('/\D/','',$phone) }}"
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

  <!-- ══ DIVIDER ══ -->
  <tr><td style="padding:0 28px;"><div style="height:1px;background:#f1f5f9;"></div></td></tr>

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
              <div style="color:#F59E0B;font-size:14px;font-weight:800;letter-spacing:1px;">{{ $leadId }}</div>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="padding-top:14px;border-top:1px solid rgba(255,255,255,0.07);margin-top:14px;">
            <div style="color:rgba(255,255,255,0.28);font-size:10px;text-align:center;padding-top:12px;">
              This is an automated lead notification from visitkashi.com · Please do not reply to this email
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>

</table>
<!-- /Main Card -->

</td></tr>
</table>
<!-- /Wrapper -->

</body>
</html>
