<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        .content h1 {
            color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 1.2em;
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ config('app.name') }}</h2>
        </div>
        <div class="content">
            <h1>¡Gracias por tu compra, {{ $order->user->name }}!</h1>
            <p>Hemos recibido tu pedido #{{ $order->id }} y ya lo estamos preparando. Aquí tienes un resumen de tu compra:</p>

            <h3>Detalles del Pedido</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->content as $item)
                        <tr>
                            <td>
                                {{ $item['name'] }}
                                @if (!empty($item['options']['color']))
                                    <br><small>Color: {{ $item['options']['color'] }}</small>
                                @endif
                                @if (!empty($item['options']['size']))
                                    <br><small>Talla: {{ $item['options']['size'] }}</small>
                                @endif
                            </td>
                            <td>{{ $item['qty'] }}</td>
                            <td>{{ number_format($item['price'], 2) }} €</td>
                            <td>{{ number_format($item['price'] * $item['qty'], 2) }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">
                <p>Subtotal: {{ number_format($order->total - $order->shipping_cost, 2) }} €</p>
                <p>Envío: {{ number_format($order->shipping_cost, 2) }} €</p>
                <p>Total: {{ number_format($order->total, 2) }} €</p>
            </div>

            <p>Puedes ver los detalles de tu pedido y seguir su estado en cualquier momento desde tu panel de usuario.</p>
            <p>¡Gracias por confiar en nosotros!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
