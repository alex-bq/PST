const Toast = {
    element: null,
    textElement: null,
    loaderElement: null,
    timeoutId: null,
    loaderInterval: null,
    currentChar: 0,
    loaderChars: ["⠋", "⠙", "⠹", "⠸", "⠼", "⠴", "⠦", "⠧", "⠇", "⠏"],

    init() {
        this.element = document.getElementById("toast");
        this.textElement = document.getElementById("toast-text");
        this.loaderElement = document.querySelector(".loader");
    },

    animateLoader() {
        if (this.loaderInterval) clearInterval(this.loaderInterval);
        this.currentChar = 0;
        this.loaderInterval = setInterval(() => {
            this.loaderElement.textContent = this.loaderChars[this.currentChar];
            this.currentChar = (this.currentChar + 1) % this.loaderChars.length;
        }, 100);
    },

    show(mensaje = "Cargando...") {
        if (!this.element) this.init();
        this.element.style.display = "block";
        this.textElement.textContent = mensaje;
        this.animateLoader();
    },

    hide() {
        if (!this.element) this.init();
        this.element.style.display = "none";
        if (this.loaderInterval) {
            clearInterval(this.loaderInterval);
            this.loaderInterval = null;
        }
    },

    showTimed(mensaje = "Cargando...", duracion = 3000) {
        this.show(mensaje);
        clearTimeout(this.timeoutId);
        this.timeoutId = setTimeout(() => this.hide(), duracion);
    },
};
