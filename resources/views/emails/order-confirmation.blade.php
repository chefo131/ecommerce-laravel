<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Confirmación de Pedido</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
            }

            .container {
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }

            .header {
                font-size: 24px;
                font-weight: bold;
                color: #3498db;
            }

            .order-details {
                margin-top: 20px;
                padding-top: 10px;
                border-top: 1px solid #eee;
            }

            .order-link {
                color: #3498db;
                text-decoration: none;
                font-weight: bold;
            }

            .item-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                margin-bottom: 20px;
            }

            .item-table th,
            .item-table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            .item-table th {
                background-color: #f2f2f2;
            }

            .text-right {
                text-align: right;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <p class="header">¡Gracias por tu compra, {{ $order->user->name }}!</p>
            <p>Hemos recibido el pago y tu pedido <strong>#{{ $order->id }}</strong> ya está en proceso. Te
                mantendremos informado sobre el estado del envío.</p>

            {{-- Resumen de los productos --}}
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th class="text-right">Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->content as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['qty'] }}</td>
                            <td class="text-right">€{{ number_format($item['price'], 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="order-details">
                <p><strong>Total del pedido:</strong> €{{ number_format($order->total, 2) }}</p>
                <p>Puedes ver todos los detalles de tu pedido y seguir su estado en el siguiente enlace:</p>
                <p><a href="{{ route('orders.show', $order) }}" class="order-link">Ver mi pedido</a></p>
            </div>

            <p>Gracias por confiar en nosotros.</p>
            <p>Saludos,<br>El equipo de {{ config('app.name') }}</p>
        </div>
    </body>

</html>
