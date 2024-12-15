document.addEventListener('turbo:load', function() {
    let searchForm = document.querySelector('.search-form');
    let shoppingCart = document.querySelector('.shopping-cart');
    let userLogin = document.querySelector('.user-login');
    let navbar = document.querySelector('.navbar');

    // Réinitialisation du comportement pour l'icône de recherche
    document.querySelector('#search-btn').onclick = () => {
        searchForm.classList.toggle('active');
        userLogin.classList.remove('active');
        shoppingCart.classList.remove('active');
        navbar.classList.remove('active');
    };

    // Réinitialisation pour le panier
    document.querySelector('#cart-btn').onclick = () => {
        shoppingCart.classList.toggle('active');
        userLogin.classList.remove('active');
        searchForm.classList.remove('active');
        navbar.classList.remove('active');
    };

    // Réinitialisation du profil utilisateur
    // document.querySelector('#user-btn').onclick = () => {
    //     userLogin.classList.toggle('active');
    //     shoppingCart.classList.remove('active');
    //     searchForm.classList.remove('active');
    //     navbar.classList.remove('active');
    // };

    document.querySelector('#menu-btn').onclick = () => {
        navbar.classList.toggle('active');
        userLogin.classList.remove('active');
        shoppingCart.classList.remove('active');
        searchForm.classList.remove('active');
    };

    window.onscroll = () =>{
        userLogin.classList.remove('active');
        shoppingCart.classList.remove('active');
        searchForm.classList.remove('active');
        navbar.classList.remove('active');
    }



});
