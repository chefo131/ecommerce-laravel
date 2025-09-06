<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Reseña Aprobada</title>
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
                color: #4CAF50;
            }

            .product-link {
                color: #3498db;
                text-decoration: none;
            }

            .review-box {
                border-left: 3px solid #eee;
                padding-left: 15px;
                margin-top: 15px;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <p class="header">¡Buenas noticias, {{ $review->user->name }}!</p>
            <p>Tu reseña para el producto <strong>{{ $review->product->name }}</strong> ha sido aprobada y ya está
                visible para toda la comunidad.</p>
            <p>¡Muchas gracias por compartir tu opinión!</p>

            <div class="review-box">
                <p><strong>Tu calificación:</strong>
                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</p>
                <p><strong>Tu comentario:</strong> "{{ $review->comment }}"</p>
            </div>

            <p>Puedes ver tu reseña publicada visitando la página del producto: <a
                    href="{{ route('products.show', $review->product) }}"
                    class="product-link">{{ $review->product->name }}</a></p>
            <p>Saludos,<br>El equipo de {{ config('app.name') }}</p>
        </div>
    </body>

</html>
