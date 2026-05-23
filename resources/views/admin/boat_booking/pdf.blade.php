<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boat Bookings</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h2>Boat Bookings @if($search_boat_type) {{App\Models\BoatType::where('id', $search_boat_type)->first()?->name}} @endif</h2>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Number of Persons</th>
                @if(!$search_boat_type)
                    <th>Boat Type</th>
                @endif
                <th>Seat Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    <td>{{ $row['booking_id'] }}</td>
                    <td>{{ $row['customer_name'] }}</td>
                    <td>{{ $row['number_of_person'] }}</td>
                    @if(!$search_boat_type)
                        <td>{{ $row['boat_type'] }}</td>
                    @endif
                    <td>{{ $row['seat_number'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
