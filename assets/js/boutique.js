document.addEventListener("turbo:load", function () {
    initializeShopScripts();
});

function initializeShopScripts() {
    const shopContainer = document.querySelector('.shop_container');
    if (shopContainer) {
        // Initialise le MultiSelectTag uniquement si on est dans `shop_container`
        new MultiSelectTag('marques', {
            rounded: true,   // Coins arrondis
            shadow: true,    // Ombre autour du tag
            placeholder: 'Rechercher...', // Texte par défaut dans la barre de recherche
            tagColor: {
                textColor: '#327b2c', // Couleur du texte (vert foncé)
                borderColor: '#92e681', // Couleur de la bordure des tags
                bgColor: '#eaffe6', // Couleur de fond des tags
            },
            onChange: function (values) {
                console.log(values); 
                updateMarques()
              
            }
        });
    }

    const decreaseButtons = document.querySelectorAll(".decrease");
    const increaseButtons = document.querySelectorAll(".increase");
    const quantities = document.querySelectorAll(".quantity");

    // Gérer le clic sur le bouton "-" (réduire la quantité)
    decreaseButtons.forEach((button, index) => {
        button.addEventListener("click", function () {
            const quantityElement = quantities[index];
            let currentQuantity = parseInt(quantityElement.textContent, 10);
            if (currentQuantity > 1) {
                quantityElement.textContent = currentQuantity - 1;
            }
        });
    });

    // Gérer le clic sur le bouton "+" (augmenter la quantité)
    increaseButtons.forEach((button, index) => {
        button.addEventListener("click", function () {
            const quantityElement = quantities[index];
            let currentQuantity = parseInt(quantityElement.textContent, 10);
            quantityElement.textContent = currentQuantity + 1;
        });
    });
}


function updateCategories() {
    const checkboxes = document.querySelectorAll('.shop_menu_categories input[type="checkbox"]');
    const selectedCategories = Array.from(checkboxes)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => parseInt(checkbox.value));

    // Convertir le tableau en JSON et définir la valeur du champ caché
    document.getElementById('categoriesField').value = JSON.stringify(selectedCategories);

    // Soumettre le formulaire avec un délai (debounce)
    submitSearchForm();
}

function updateMarques() {
    const marquesSelect = document.getElementById('marques');
    const selectedMarques = Array.from(marquesSelect.selectedOptions)
        .map(option => parseInt(option.value)); // Récupère les IDs des marques sélectionnées
        
    document.getElementById('marquesField').value = JSON.stringify(selectedMarques);


    // Soumettre le formulaire
    submitSearchForm();
}





let debounceTimer;
function submitSearchForm() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 800);
}

window.updateCategories = updateCategories;
window.submitSearchForm = submitSearchForm;
