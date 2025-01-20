document.addEventListener("DOMContentLoaded", function() {
    const decreaseButtons = document.querySelectorAll(".decrease");
    const increaseButtons = document.querySelectorAll(".increase");
    const quantities = document.querySelectorAll(".quantity");

    // Gérer le clic sur le bouton "-" (réduire la quantité)
    decreaseButtons.forEach((button, index) => {
        button.addEventListener("click", function() {
            const quantityElement = quantities[index];
            let currentQuantity = parseInt(quantityElement.textContent, 10);
            if (currentQuantity > 1) {
                quantityElement.textContent = currentQuantity - 1;
            }
        });
    });

    // Gérer le clic sur le bouton "+" (augmenter la quantité)
    increaseButtons.forEach((button, index) => {
        button.addEventListener("click", function() {
            const quantityElement = quantities[index];
            let currentQuantity = parseInt(quantityElement.textContent, 10);
            quantityElement.textContent = currentQuantity + 1;
        });
    });
});



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

let debounceTimer;
function submitSearchForm() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 800);
}

window.updateCategories = updateCategories;
window.submitSearchForm = submitSearchForm;
