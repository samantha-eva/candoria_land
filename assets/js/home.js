$(document).ready(function() {
    $('.customer-logos').slick({
        dots: false,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 2000,
        slidesToShow: 5,
        slidesToScroll: 1,
        arrows: false,
        pauseOnHover: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 520,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            }
        ]
    });

    var $grid = $('.product-container').isotope({
        itemSelector: '.product-box', // Classe des éléments à trier
        layoutMode: 'fitRows'        // Mode de disposition
    });

    // Filtrage au clic sur les boutons
    $('.product-menu a').on('click', function (e) {
        e.preventDefault(); // Empêche le rechargement de la page
        
        var filterValue = $(this).attr('data-filter'); // Récupère la valeur du filtre
        $grid.isotope({ filter: filterValue }); // Applique le filtre

        // Gère l'état actif des boutons
        $('.product-menu a').removeClass('active'); // Supprime la classe active
        $(this).addClass('active'); // Ajoute la classe active au bouton actuel
    });


});

