<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style type="text/css">
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <h3 class="text-mutted">Dear {{ $name }}</h3>
    <p>Here is the data for sales made today ({{ $data['date'] }})!</p>
    <p>Total Sales: {{ $data['sales_count'] }} </p>
    <p>Total Value: R${{ $data['sales_value'] / 100 }} </p>
    <p>Commissions: R${{ $data['commission'] / 100 }} </p>
    <br>

    <h4>Sellers's Sales</h4>
    <table class="table">
        <thead>
            <th>Seller</th>
            <th>Total Sales</th>
            <th>Total Value</th>
            <th>Commission</th>
        </thead>
        <tbody>
            @foreach($data['sellers'] as $seller)
            <tr>
                <td>{{ $seller['name'] }}</td>
                <td>{{ $seller['sales']['sales_count'] }}</td>
                <td>R${{ $seller['sales']['sales_value'] / 100}}</td>
                <td>R${{ $seller['sales']['commission'] / 100 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>