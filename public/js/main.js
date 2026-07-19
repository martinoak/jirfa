//Získání tlačítka:
mybutton = document.getElementById("backToTop");

document.addEventListener("DOMContentLoaded", loadAfterDom);

function loadAfterDom() {
    filterSelection("vse");
    const elements = document.querySelectorAll(".dropdown-item, .reference-button-small");
    for (let index = 0; index < elements.length; index++) {
        elements[index].addEventListener("click", navigationItemClick);
    }
    document
        .getElementById("selectType")
        .addEventListener("change", selectChange);
    initNavToggle();
    initDropdown();
    initMapModal();
}

function navigationItemClick(event) {
    let value = event.target.getAttribute("data-value");
    filterSelection(value);
    document.getElementById("selectType").value = value;
    closeDropdown();
}

// -------------------------------------
// Mobilní navigace (nahrazuje Bootstrap collapse)
// -------------------------------------
function initNavToggle() {
    const toggle = document.getElementById("navToggle");
    const menu = document.getElementById("navbarSupportedContent");
    if (!toggle || !menu) return;

    toggle.addEventListener("click", function () {
        const opened = !menu.classList.toggle("hidden");
        toggle.setAttribute("aria-expanded", opened ? "true" : "false");
    });
}

// -------------------------------------
// Rozbalovací menu Reference (nahrazuje Bootstrap dropdown)
// -------------------------------------
function initDropdown() {
    const button = document.getElementById("navbarDropdown");
    const menu = document.getElementById("navbarDropdownMenu");
    if (!button || !menu) return;

    button.addEventListener("click", function (event) {
        event.stopPropagation();
        const opened = !menu.classList.toggle("hidden");
        button.setAttribute("aria-expanded", opened ? "true" : "false");
    });

    // Kliknutí mimo menu jej zavře
    document.addEventListener("click", function (event) {
        if (!menu.contains(event.target) && !button.contains(event.target)) {
            closeDropdown();
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") closeDropdown();
    });
}

function closeDropdown() {
    const button = document.getElementById("navbarDropdown");
    const menu = document.getElementById("navbarDropdownMenu");
    if (!button || !menu) return;
    menu.classList.add("hidden");
    button.setAttribute("aria-expanded", "false");
}

// -------------------------------------
// Modální okno s mapou (nahrazuje Bootstrap modal)
// -------------------------------------
function initMapModal() {
    const modal = document.getElementById("mapModal");
    if (!modal) return;

    const openers = document.querySelectorAll("[data-modal-open]");
    for (let index = 0; index < openers.length; index++) {
        openers[index].addEventListener("click", function () {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });
    }

    // Zavření křížkem nebo kliknutím na pozadí
    modal.addEventListener("click", function (event) {
        if (event.target === modal || event.target.closest("[data-modal-close]")) {
            closeMapModal();
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") closeMapModal();
    });
}

function closeMapModal() {
    const modal = document.getElementById("mapModal");
    if (!modal) return;
    modal.classList.add("hidden");
    modal.classList.remove("flex");
}

function selectChange(event) {
    let value = event.target.value;
    filterSelection(value);
}

// Pokud uživatel udělá scroll down větší než 20 pixelů, ukáže se back to top tlačítko
window.onscroll = function () {
    scrollFunction();
};

function scrollFunction() {
    if (
        document.body.scrollTop > 500 ||
        document.documentElement.scrollTop > 500
    ) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
}

// Pokud uživatel klikne na tlařítko, přesměruje jej to zpět nahoru
function topFunction() {
    document.body.scrollTop = 0; // Pro Safari
    document.documentElement.scrollTop = 0; // Pro Chrome, Firefox, IE a Opera prohlížeče
}

// ----------------------
// Filtr výběru reference
// ----------------------
function filterSelection(value) {
    var x, i;
    x = document.getElementsByClassName("filterClass");
    if (value === "vse") value = "";
    // Přidej třídu show (display:block) k vyfiltrovaným elementům a odstraň "show" od elementů které nebyly vybrány
    for (i = 0; i < x.length; i++) {
        w3RemoveClass(x[i], "show");
        if (x[i].className.indexOf(value) > -1) w3AddClass(x[i], "show");
    }
}

// Ukaž vyfiltrované elementy
function w3AddClass(element, name) {
    var i, arr1, arr2;
    arr1 = element.className.split(" ");
    arr2 = name.split(" ");
    for (i = 0; i < arr2.length; i++) {
        if (arr1.indexOf(arr2[i]) === -1) {
            element.className += " " + arr2[i];
        }
    }
}

// Schovej elementy které nebyly vybrány
function w3RemoveClass(element, name) {
    var i, arr1, arr2;
    arr1 = element.className.split(" ");
    arr2 = name.split(" ");
    for (i = 0; i < arr2.length; i++) {
        while (arr1.indexOf(arr2[i]) > -1) {
            arr1.splice(arr1.indexOf(arr2[i]), 1);
        }
    }
    element.className = arr1.join(" ");
}
