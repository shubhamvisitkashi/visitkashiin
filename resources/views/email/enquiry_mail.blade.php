<html>
<title>Visit Kashi | Booking </title>
</head>

<body>
    <table cellspacing="5" cellpadding="5" border="1" style='border-collapse: collapse; width:100%; text-align:center;'>
        <tr>
            <td style="width:20%;"><img src="{{asset('backend/admin/website_setup/'.websiteSetupValue('logo'))}}" style="height:60px; width:auto; background:#c12f00; padding:5px;"></td>
            <td>
                <h3>Visit Kashi</h3>
            </td>
        </tr>
        <tr>
            <td><b>Package Name: </b></td>
            <td>{{$package_name}}</td>
        </tr>
        @if($enquiry->arrival_date)
        <tr>
            <td><b>Date: </b></td>
            <td>{{date('d-m-Y h:i A', strtotime($enquiry->arrival_date))}}</td>
        </tr>
        @endif
        <tr>
            <td><b>Name: </b></td>
            <td>{{$name}}</td>
        </tr>
        <tr>
            <td><b>Contact: </b></td>
            <td>{{$phone}}</td>
        </tr>
        @if($enquiry->no_of_person)
            <tr>
                <td><b>Number of Person: </b></td>
                <td>{{$enquiry->no_of_person}}</td>
            </tr>
        @endif
        @if($enquiry->checkin_time)
            <tr>
                <td><b>Check-In: </b></td>
                <td>{{date('d-m-Y h:i A', strtotime($enquiry->checkin_time))}}</td>
            </tr>
        @endif
        @if($enquiry->checkout_time)
            <tr>
                <td><b>Check-Out: </b></td>
                <td>{{date('d-m-Y h:i A', strtotime($enquiry->checkout_time))}}</td>
            </tr>
        @endif
        <tr>
            <td><b>Message: </b></td>
            <td>{{$messages}}</td>
        </tr>

    </table>
</body>

</html>
