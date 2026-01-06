<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Hello {{ $order->customer_name ?? 'Customer' }},</p>
    <p>Thank you for your order #{{ $order->id }}.</p>
    <p>Your invoice is attached as a PDF file.</p>
    <p>Regards,<br>{{ $vendor->name ?? $companyName }}</p>

</body>

</html>