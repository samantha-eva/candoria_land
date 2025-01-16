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
