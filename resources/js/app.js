import Alpine from 'alpinejs';

/**
 * Sdílený lightbox pro certifikáty i reference.
 *
 * Nahrazuje lightbox2, který vyžadoval jQuery. Galerie se otevírá voláním
 * $store.lightbox.show(...) přímo z markupu.
 */
Alpine.store('lightbox', {
    images: [],
    index: 0,
    title: '',
    open: false,

    show(images, index = 0, title = '') {
        this.images = images;
        this.index = index;
        this.title = title;
        this.open = true;
        document.body.classList.add('overflow-hidden');
    },

    close() {
        this.open = false;
        document.body.classList.remove('overflow-hidden');
    },

    next() {
        this.index = (this.index + 1) % this.images.length;
    },

    prev() {
        this.index = (this.index - 1 + this.images.length) % this.images.length;
    },

    get current() {
        return this.images[this.index] || '';
    },

    get hasMultiple() {
        return this.images.length > 1;
    },
});

/**
 * Filtrování referencí podle kategorie. Nahrazuje původní filterSelection()
 * z main.js -- kategorie se nyní řeší přes x-show místo přidávání tříd.
 */
Alpine.data('referenceFilter', () => ({
    selected: 'vse',

    select(value) {
        this.selected = value;
    },

    matches(category) {
        return this.selected === 'vse' || this.selected === category;
    },
}));

/**
 * Navigace -- mobilní menu, rozbalovací nabídka a stav při odscrollování.
 */
Alpine.data('siteNav', () => ({
    mobileOpen: false,
    dropdownOpen: false,
    scrolled: false,

    init() {
        this.onScroll();
        window.addEventListener('scroll', () => this.onScroll(), { passive: true });
    },

    onScroll() {
        this.scrolled = window.scrollY > 20;
    },

    closeAll() {
        this.mobileOpen = false;
        this.dropdownOpen = false;
    },
}));

/**
 * Tlačítko pro návrat na začátek stránky.
 */
Alpine.data('backToTop', () => ({
    visible: false,

    init() {
        this.onScroll();
        window.addEventListener('scroll', () => this.onScroll(), { passive: true });
    },

    onScroll() {
        this.visible = window.scrollY > 500;
    },

    toTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },
}));

window.Alpine = Alpine;
Alpine.start();
