// resources/js/app.js

// NO importamos Alpine manualmente. Livewire 3 ya lo incluye.
// Solo importamos Dropzone.
import Dropzone from 'dropzone';

// Importamos el CSS de Glider.js directamente aquí.
// Vite se encargará de inyectarlo en el CSS final de forma optimizada.
import 'glider-js/glider.min.css';
// Importamos el fichero glider.js para asegurarnos de que la función
// window.initProductGlider esté disponible globalmente.
import './glider.js';

// Función para inicializar todos los scripts que dependen del DOM
function initializeScripts() {
    console.log('app.js: Inicializando scripts (Glider, FlexSlider, etc.)...');

    // Buscamos todos los contenedores de sliders y los inicializamos.
    // Usamos un atributo data-* para identificarlos.
    document
        .querySelectorAll('[data-glider-container]')
        .forEach((container) => {
            window.initProductGlider(container);
        });

    // La inicialización de FlexSlider se ha movido a la vista 'products.show.blade.php'
    // usando Alpine.js (x-init) para un control más localizado y robusto,
    // evitando así la necesidad de una función de inicialización global aquí.
}

// Hacemos que Dropzone no se auto-descubra en toda la página.
// Lo controlaremos nosotros con Alpine.
Dropzone.autoDiscover = false;

// (Opcional pero recomendado) Si tienes un archivo bootstrap.js de Laravel (ej. de Breeze/Jetstream),
// impórtalo aquí. A menudo maneja la inicialización de Alpine si no usas Livewire,
// o puede contener otras configuraciones útiles como Axios.
// import './bootstrap';

console.log('app.js: Script principal cargado.');

// Importa tus componentes de Alpine.js
import navigationDropdown from './navigationDropdown';
console.log('app.js: Módulo navigationDropdown importado:', navigationDropdown);

// El evento 'alpine:init' se dispara después de que Alpine.js se haya inicializado completamente.
// Esta es la forma segura y recomendada de registrar componentes y extender Alpine.
document.addEventListener('alpine:init', () => {
    console.log('app.js: Evento alpine:init disparado.');

    // Registra tus componentes Alpine aquí
    if (navigationDropdown) {
        window.Alpine.data('navigationDropdown', navigationDropdown);
        console.log(
            'app.js: Componente navigationDropdown registrado con Alpine.',
        );
    } else {
        console.error(
            'app.js: El módulo navigationDropdown no se pudo cargar correctamente.',
        );
    }

    // Registramos nuestro componente Alpine para Dropzone
    window.Alpine.data('dropzone', (config) => ({
        init() {
            let dropzone = new Dropzone(this.$el, config);
            this.$watch(
                'config.maxFiles',
                (value) => (dropzone.options.maxFiles = value),
            );
        },
    }));

    // Puedes registrar otros componentes Alpine aquí también:
    // import otroComponente from './otroComponente';
    // window.Alpine.data('otroComponente', otroComponente);

    console.log(
        'app.js: Registros de Alpine completados dentro de alpine:init.',
    );
});

console.log(
    'app.js: Fin del script principal. Esperando eventos como alpine:init.',
);

// --- La forma moderna y robusta de inicializar JS con Livewire 3 ---

// 1. Ejecutar en la carga inicial de la página, cuando el DOM esté listo.
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOMContentLoaded -> Inicialización de scripts.');
    initializeScripts();
});

// 2. Ejecutar después de cada navegación "SPA" de Livewire.
document.addEventListener('livewire:navigated', () => {
    console.log('livewire:navigated -> Re-inicialización para nueva página.');
    initializeScripts();
});

// 3. (COMENTADO) Ejecutar después de cada actualización parcial de un componente.
// Se comenta esta sección porque es la causa del conflicto. Al actualizar el componente del carrito,
// se re-inicializaban innecesariamente todos los scripts de la página, incluyendo FlexSlider,
// lo que rompía el motor de JavaScript de Livewire. Los componentes actuales no necesitan esta re-inicialización global.
// Livewire.hook('morph.updated', ({ el, component }) => {
//     console.log('livewire:morph.updated -> Re-inicialización para componente:', component.name);
//     initializeScripts();
// });
