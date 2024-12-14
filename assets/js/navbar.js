document.addEventListener('turbo:load', function() {
    let searchForm = document.querySelector('.search-form');
    let shoppingCart = document.querySelector('.shopping-cart');
    let userLogin = document.querySelector('.user-login');

    // Réinitialisation du comportement pour l'icône de recherche
    document.querySelector('#search-btn').onclick = () => {
        searchForm.classList.toggle('active');
    };

    // Réinitialisation pour le panier
    document.querySelector('#cart-btn').onclick = () => {
        shoppingCart.classList.toggle('active');
    };

    // Réinitialisation du profil utilisateur
    document.querySelector('#user-btn').onclick = () => {
        userLogin.classList.toggle('active');
    };
});
