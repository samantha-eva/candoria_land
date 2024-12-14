let searchForm = document.querySelector('.search-form');

document.querySelector('#search-btn').onclick = () =>{
    searchForm.classList.toggle('active');
}

let shoppingCart = document.querySelector('.shopping-cart');

document.querySelector('#cart-btn').onclick = () =>{
    shoppingCart.classList.toggle('active');
}

let userLogin = document.querySelector('.user-login');

document.querySelector('#user-btn').onclick = () =>{
    userLogin.classList.toggle('active');
}