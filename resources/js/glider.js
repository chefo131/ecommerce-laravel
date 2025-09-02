// resources/js/glider.js
import Glider from 'glider-js';

window.initProductGlider = function (gliderContainerElement) {
    if (!gliderContainerElement) {
        console.warn('Glider container element not provided for initProductGlider.');
        return;
    }

    const gliderElement = gliderContainerElement.querySelector('.glider');
    const dotsElement = gliderContainerElement.querySelector('.dots');
    const prevArrow = gliderContainerElement.querySelector('.glider-prev');
    const nextArrow = gliderContainerElement.querySelector('.glider-next');

    if (gliderElement) {
        // Hacemos la inicialización "defensiva". Si Glider ya está inicializado
        // en este elemento, no hacemos nada para evitar errores.
        // La librería Glider.js adjunta la instancia al propio elemento DOM.
        if (gliderElement.glider) {
            // Opcional: podríamos refrescarlo, pero es más seguro simplemente salir.
            // gliderElement.glider.refresh(true);
            return;
        }

        // Inicializamos Glider directamente. A veces, requestAnimationFrame puede ser problemático
        // si el elemento no es visible de inmediato.
        try {
            console.log('glider.js: Intentando inicializar Glider en', gliderElement);
            new Glider(gliderElement, {
                slidesToShow: 1,
                slidesToScroll: 1,
                draggable: true,
                dots: dotsElement,
                arrows: {
                    prev: prevArrow,
                    next: nextArrow,
                },
                responsive: [
                    {
                        breakpoint: 640,
                        settings: {
                            slidesToShow: 2.5,
                            slidesToScroll: 2,
                        },
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3.5,
                            slidesToScroll: 3,
                        },
                    },
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 5,
                            slidesToScroll: 5,
                        },
                    },
                ],
            });
            // ¡LA CLAVE! Añadimos la clase que hace visible el carrusel.
            // El CSS en glider.css busca esta clase para cambiar la opacidad a 1.
            gliderElement.classList.add('glider-initialized-custom');
            console.log('glider.js: Glider inicializado con éxito.');
        } catch (error) {
            console.error('glider.js: Error inicializando Glider:', error, gliderElement);
        }
    } else {
        console.warn(
            'Element .glider not found within the provided container for initProductGlider:',
            gliderContainerElement
        );
    }
};
