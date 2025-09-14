<?php

use App\Livewire\Admin\CreateProduct;
use App\Livewire\Admin\EditProduct;
use App\Livewire\Admin\ShowProduct;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CategoryController;
use App\Livewire\Admin\SubcategoryManager;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Livewire\ShoppingCart;
use App\Http\Controllers\TiendaController;
use App\Livewire\CreateOrder;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Livewire\Admin\BrandsComponent;
use App\Livewire\Admin\ShowOrders;
use App\Livewire\Admin\DepartmentComponent;
use App\Livewire\Admin\CityComponent;
use App\Livewire\Admin\DistrictComponent;
use App\Livewire\Admin\UserComponent;
use App\Livewire\Admin\ManageReviews;
use App\Livewire\CategoryFilter;
use App\Livewire\Admin\EditUser;


// La lógica de la ruta 'prueba' se ha movido a un comando de Artisan:
// php artisan orders:cancel-pending
// Y se ha programado para ejecutarse automáticamente en bootstrap/app.php

// Rutas aplicación web
Route::get('/', WelcomeController::class)->name('home');

Route::get('search', SearchController::class)->name('search');

// La ruta para mostrar una categoría ahora carga nuestro componente Livewire `CategoryFilter`
Route::get('categories/{category}', CategoryFilter::class)->name('categories.show');
// La ruta para mostrar un producto ahora carga nuestro componente Livewire `ShowProduct`
Route::get('products/{product}', App\Livewire\ShowProduct::class)->name('products.show');

Route::get('shopping-cart', ShoppingCart::class)->name('shopping-cart'); // Esta ruta ya estaba bien

Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda');

Route::middleware('auth')->group(function(){

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('orders/create', CreateOrder::class)->name('orders.create');

    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('orders/{order}/payment', [OrderController::class, 'payment'])->name('orders.payment');
    // ¡LÍNEA ELIMINADA!
    // Esta ruta era incorrecta. Estaba en el grupo de rutas de usuario normal y apuntaba
    // al controlador equivocado (OrderController en lugar de AdminOrderController).
    // Esto causaba que la lógica para rellenar 'order_product' no se ejecutara. La ruta correcta ya existe en el grupo de admin.
    Route::post('/orders/{order}/create-payment', [PaymentController::class, 'createPayment'])->name('payment.create');

    Route::post('/orders/{order}/capture-payment', [PaymentController::class, 'capturePayment'])->name('payment.capture');

    // Ruta para la pasarela de pago "Dummy" para pruebas locales
    Route::post('/orders/{order}/capture-dummy', [\App\Http\Controllers\DummyPaymentController::class, 'capture'])->name('payment.dummy.capture'); // Eliminamos la línea duplicada

    // Nueva ruta para la página de éxito del pago
    Route::get('orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');

});

Route::get('/cart', function () {
    return view('cart');
});



Route::get('dashboard', function () { // Cambiado de Route::view a Route::get para poder añadir lógica
    // Log para el acceso al dashboard
    Log::info('Dashboard route accessed. Session ID: ' . session()->getId() . ', Auth Check: ' . (Auth::check() ? 'true' : 'false') . ', User: ' . (Auth::user()?->email ?? 'Guest'));
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // La ruta raíz del admin será el listado de productos
    Route::get('/', ShowProduct::class)->name('index');

    // Rutas para la gestión de productos
    Route::get('products/create', CreateProduct::class)->name('products.create');
    Route::get('products/{product}/edit', EditProduct::class)->name('products.edit');

    // Ruta para la gestión de categorías
    Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    // Nueva ruta para mostrar una categoría y sus subcategorías
    Route::get('categories/{category}', \App\Livewire\Admin\ShowCategory::class)->name('categories.show');

    // Ruta para editar una categoría
    Route::get('categories/{category}/edit', \App\Livewire\Admin\EditCategory::class)->name('categories.edit');

    // ¡RUTA CORREGIDA! El nombre no debe repetir el prefijo del grupo.
    Route::get('categories/{category}/subcategories', SubcategoryManager::class)->name('categories.subcategories');

    // Rutas para Marcas
    Route::get('brands', BrandsComponent::class)->name('brands.index');

    // Rutas para ordenes
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

    // Rutas para Department (Provincias)
    Route::get('departments', DepartmentComponent::class)->name('departments.index');

    // Rutas para Ciudades (asociadas a un Departamento)
    Route::get('departments/{department}', CityComponent::class)->name('departments.show');

    // Rutas para Distritos (asociados a una Ciudad)
    Route::get('cities/{city}', \App\Livewire\Admin\DistrictComponent::class)->name('cities.show');

    // Ruta para la gestión de usuarios (CRUD)
    Route::get('users', \App\Livewire\Admin\UserComponent::class)->name('users.index');
    Route::get('users/{user}/edit', EditUser::class)->name('users.edit');

    // ¡AQUÍ ES DONDE DEBE ESTAR! Dentro del grupo de admin.
    Route::get('reviews', ManageReviews::class)->name('reviews.index');
});



Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Rutas de autenticación de Fortify

require __DIR__ . '/auth.php';
