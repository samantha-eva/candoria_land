document.addEventListener('turbo:load', function () {
    // Supprimer un produit
    document.querySelectorAll('.remove-btn').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');

            fetch(`/cart/remove/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
            .then(response => {
                if (response.ok) {
                    // Supprimer la ligne correspondante du tableau
                    const row = this.closest('tr');
                    row.remove();

                    // Mettre à jour le total du panier
                    updateCartTotal();
                } else {
                    alert('Erreur lors de la suppression de l\'élément.');
                }
            })
            .catch(error => console.error('Erreur:', error));
        });
    });

    // Mettre à jour la quantité et recalculer les totaux
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            const newQuantity = parseInt(this.value, 10);
            const productId = this.getAttribute('data-id');
            const row = this.closest('tr');
            const priceCell = row.querySelector('td:nth-child(4)'); // 4e colonne = Prix
            const totalCell = row.querySelector('td:nth-child(6)'); // 6e colonne = Total

            if (isNaN(newQuantity) || newQuantity < 1) {
                alert('Veuillez entrer une quantité valide.');
                this.value = 1;
                return;
            }

            // Envoyer la nouvelle quantité au backend
            fetch(`/cart/update/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ quantity: newQuantity }),
            })
            .then(response => {
                if (response.ok) {
                    const price = parseFloat(priceCell.textContent.replace('€', '').trim());
                    const newTotal = price * newQuantity;

                    // Mettre à jour le total pour cette ligne
                    totalCell.textContent = `€${newTotal.toFixed(2)}`;

                    // Recalculer le total général du panier
                    updateCartTotal();
                } else {
                    alert('Erreur lors de la mise à jour de la quantité.');
                }
            })
            .catch(error => console.error('Erreur:', error));
        });
    });

    // Recalculer le total général du panier
    function updateCartTotal() {
        let total = 0;

        // Parcourir toutes les lignes restantes pour recalculer le total
        document.querySelectorAll('tbody tr').forEach(row => {
            const totalCell = row.querySelector('td:nth-child(6)'); // 6e colonne = Total
            if (totalCell) {
                const value = parseFloat(totalCell.textContent.replace('€', '').trim());
                if (!isNaN(value)) {
                    total += value;
                }
            }
        });

        // Mettre à jour l'affichage du total
        const totalPriceElement = document.querySelector('.total-price');
        if (totalPriceElement) {
            totalPriceElement.textContent = `€${total.toFixed(2)}`;
        }

        // Gérer le cas où le panier est vide
        const rows = document.querySelectorAll('tbody tr');
        if (rows.length === 0) {
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = '<tr><td colspan="6">Votre panier est vide.</td></tr>';
        }
    }
});
