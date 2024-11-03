<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

</head>

<body class="font-sans antialiased">
    <h3 class="text-mutted">Dear {{ $name }}</h3>
    <p>Here is the data of your sales made today ({{ $data['date'] }})!</p>
    <p>Total Sales: {{ $data['sales_count'] }} </p>
    <p>Total Value: R${{ $data['sales_value'] / 100 }} </p>
    <p>Your Commission: R${{ $data['commission'] / 100 }} </p>
</body>

</html>