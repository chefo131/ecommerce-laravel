<?php

namespace App\Providers;

use App\View\Composers\NavigationComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registramos nuestra directiva personalizada para formatear moneda
        Blade::directive('money', function ($expression) {
            // La directiva devolverá el código PHP para formatear el número
            // con el símbolo del Euro, 2 decimales, una coma decimal y un punto para los miles.
            return "<?php echo '€ ' . number_format($expression, 2, ',', '.'); ?>";
        });

        // Asocia el NavigationComposer con la vista de navegación.
        View::composer('livewire.navigation', NavigationComposer::class);
    }
}
