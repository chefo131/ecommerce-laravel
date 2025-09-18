# Ecommerce con Laravel y Livewire (Martin-ecommerce)

Este es un proyecto de comercio electrónico completo desarrollado con el TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire). La plataforma permite la gestión de productos, categorías, marcas, y un flujo de compra completo con carrito, checkout y pasarela de pago.

## Características Principales

- **Catálogo de Productos:** Navegación por categorías y subcategorías.
- **Variantes de Producto:** Soporte para productos con colores, tallas o ambas.
- **Filtros Avanzados:** Búsqueda y filtrado de productos por marca, categoría, etc.
- **Carrito de Compras:** Funcionalidad completa de añadir, actualizar y eliminar productos del carrito, persistente en la sesión.
- **Proceso de Checkout:** Pasos guiados para introducir dirección de envío, seleccionar método de envío y realizar el pago.
- **Pasarela de Pago:** Integración con PayPal.
- **Panel de Administración:**
    - Gestión de productos (crear, editar, eliminar).
    - Gestión de categorías, subcategorías y marcas.
    - Gestión de pedidos.
    - Roles y permisos para administradores.
- **Sistema de Reseñas:** Los usuarios que han comprado un producto pueden dejar su valoración.
- **Diseño Responsivo:** Interfaz adaptable a dispositivos móviles y de escritorio gracias a Tailwind CSS.

## Requisitos

- PHP >= 8.2
- Composer
- Node.js & npm
- Servidor de base de datos (MySQL, MariaDB, etc.)

## Guía de Instalación

Sigue estos pasos para poner en marcha el proyecto en tu entorno local.

1.  **Clonar el repositorio:**
    ```bash
    git clone https://github.com/chefo131/ecommerce-laravel.git
    cd ecommerce-laravel
    ```

2.  **Instalar dependencias de PHP:**
    ```bash
    composer install
    ```

3.  **Configurar el entorno:**
    Copia el fichero de ejemplo `.env.example` a `.env` y configúralo con tus datos.
    ```bash
    cp .env.example .env
    ```
    Genera la clave de la aplicación:
    ```bash
    php artisan key:generate
    ```
    Asegúrate de configurar correctamente la conexión a tu base de datos (`DB_*`) y tu servidor de correo (`MAIL_*`).

4.  **Migraciones y Seeders:**
    Ejecuta las migraciones para crear la estructura de la base de datos y los seeders para poblarla con datos de ejemplo.
    ```bash
    php artisan migrate --seed
    ```

5.  **Enlazar el almacenamiento:**
    Este comando crea un enlace simbólico desde `public/storage` a `storage/app/public`, haciendo accesibles las imágenes y otros archivos subidos.
    ```bash
    php artisan storage:link
    ```

6.  **Instalar dependencias de JavaScript:**
    ```bash
    npm install
    ```

7.  **Compilar los assets:**
    Ejecuta Vite para compilar los ficheros CSS y JS. Para desarrollo, puedes dejarlo corriendo.
    ```bash
    npm run dev
    ```

8.  **¡Listo!**
    Inicia el servidor de desarrollo de Laravel y visita la URL que te indique.
    ```bash
    php artisan serve
    ```

## Créditos

*Este proyecto ha sido una aventura increíble, construido codo a codo por José y su padrino de silicio, Gemini Code Assist. ¡El resultado de la tenacidad, la curiosidad y muchas "meigas" cazadas por el camino!*
