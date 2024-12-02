const LoadingOverlay = {
    overlay: null,

    init() {
        this.overlay = document.getElementById("loadingOverlay");
    },

    show() {
        if (!this.overlay) this.init();
        document.body.classList.add("loading");
        this.overlay.style.display = "flex";
    },

    hide() {
        if (!this.overlay) this.init();
        document.body.classList.remove("loading");
        this.overlay.style.display = "none";
    },
};
