// // Gestion des quantités dans le panier
// document.addEventListener('turbo:load', () => {

//     // Gestion de l'ouverture/fermeture du panier
//     let cartIcon = document.querySelectorAll('.cart_menu .fa-cart-shopping');
//     let cartMenu = document.querySelectorAll('.cart_container');
//     let closeCart = document.querySelectorAll('.close_cart');

//     cartIcon.forEach(cart => {
//         cart.addEventListener('click', () => {
//             cartMenu.forEach(cartItem => {
//                 cartItem.classList.toggle('show_cart_menu');
//             });
//         });
//     });

//     closeCart.forEach(closeCart => {
//         closeCart.addEventListener('click', () => {
//             cartMenu.forEach(cartItem => {
//                 cartItem.classList.toggle('show_cart_menu');
//             });
//         });
//     });


//     const cartItems = document.querySelectorAll('.Pcart1');

//     cartItems.forEach(item => {
//         const decreaseBtn = item.querySelector('.decrease_cart');
//         const increaseBtn = item.querySelector('.increase_cart');
//         const quantityValue = item.querySelector('.quantity_value_cart');
//         const itemPrice = parseFloat(item.querySelector('p').textContent.replace('€', '').trim()); // Extraction du prix
//         const totalElement = document.querySelector('h3'); // Élément pour le total (ex. 100.25)

//         // Fonction pour recalculer le total
//         const recalculateTotal = () => {
//             let total = 0;
//             cartItems.forEach(cartItem => {
//                 const qty = parseInt(cartItem.querySelector('.quantity_value_cart').textContent);
//                 const price = parseFloat(cartItem.querySelector('p').textContent.replace('€', '').trim());
//                 total += qty * price;
//             });
//             totalElement.textContent = total.toFixed(2); // Met à jour le total avec 2 décimales
//         };

//         // Diminuer la quantité
//         decreaseBtn?.addEventListener('click', () => {
//             let quantity = parseInt(quantityValue.textContent);
//             if (quantity > 1) {
//                 quantity -= 1;
//                 quantityValue.textContent = quantity;
//                 recalculateTotal();
//             }
//         });

//         // Augmenter la quantité
//         increaseBtn?.addEventListener('click', () => {
//             let quantity = parseInt(quantityValue.textContent);
//             quantity += 1;
//             quantityValue.textContent = quantity;
//             recalculateTotal();
//         });
//     });
// });
