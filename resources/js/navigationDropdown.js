export default function dropdown() {
    //debugger; // Añade esto temporalmente
    console.log(
        'navigationDropdown.js: Estado inicial del componente:',
        'navigationDropdown.js: La función fábrica se está ejecutando.'
    ); // Añade esto también

    return {
        open: false,
        show() {
            //debugger; // Añade esto temporalmente
            if (this.open) {
                console.log(
                    'navigationDropdown.js: Clic en show(), el menú se cierra. open =',
                    this.open
                );
                //Se cierra el menú
                this.open = false;
                // La gestión del overflow se delega a x-init="$watch" en el HTML
            } else {
                //Se abre el menú
                this.open = true;
                // La gestión del overflow se delega a x-init="$watch" en el HTML
                console.log('Dropdown state (this.open):', this.open);
            }
        },
        close() {
            this.open = false;
            // La gestión del overflow se delega a x-init="$watch" en el HTML
        },
    };
}
