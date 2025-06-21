document.addEventListener('DOMContentLoaded', function() {
    // Manejo del dropdown de navegación
    const dropdowns = document.querySelectorAll('.main-nav .dropdown');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('mouseenter', function() {
            this.querySelector('.dropdown-menu').style.display = 'block';
        });
        dropdown.addEventListener('mouseleave', function() {
            this.querySelector('.dropdown-menu').style.display = 'none';
        });
    });

    // Lógica para estrellas de calificación en Feedback
    const ratingStars = document.querySelector('.rating-stars');
    if (ratingStars) {
        const stars = ratingStars.querySelectorAll('.star');
        let currentRating = 0;

        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                currentRating = index + 1;
                updateStars(currentRating);
                console.log('Puntuación seleccionada:', currentRating);
            });

            star.addEventListener('mouseover', () => {
                updateStars(index + 1);
            });

            star.addEventListener('mouseout', () => {
                updateStars(currentRating); // Vuelve al rating actual si no hay selección
            });
        });

        function updateStars(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
        }
    }

    // Lógica para seleccionar monto de donación
    const donationOptions = document.querySelectorAll('.donation-option');
    if (donationOptions.length > 0) {
        donationOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Remover 'selected' de todos
                donationOptions.forEach(opt => opt.classList.remove('selected'));
                // Añadir 'selected' al clickeado
                option.classList.add('selected');

                // Opcional: actualizar un campo oculto en el formulario de donación
                const amountInput = document.getElementById('donation_amount_input');
                if (amountInput) {
                    amountInput.value = option.dataset.amount;
                }
            });
        });
    }
});