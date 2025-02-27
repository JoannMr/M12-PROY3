window.addEventListener('scroll', () => {
    const header = document.querySelector('header');
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Ejemplo: Animar el icono del carrito al añadir un producto
const cartIcon = document.querySelector('.cart-icon');
let cartCount = 0; 

// Interceptar el clic en los botones de "Añadir al carrito"
document.querySelectorAll('.btn-add-to-cart').forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault(); 

        const productId = button.getAttribute('data-product-id');

        // Enviar la solicitud al backend sin recargar la página
        fetch('api/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`
        })
        .then(response => response.text())
        .then(data => {
            console.log('Respuesta del servidor:', data);

            // Animar el icono del carrito
            cartIcon.classList.add('shake');
            setTimeout(() => {
                cartIcon.classList.remove('shake');
            }, 500);
            
            // Comprobar si ya existe un mensaje de éxito
            let message = document.querySelector('.cart-message-success');
            
            if (!message) {
                // Crear el mensaje si no existe
                message = document.createElement('div');
                message.className = 'cart-message-success';
                document.body.appendChild(message);
            }
            
            // Actualizar el contenido del mensaje
            message.innerText = 'Producto añadido al carrito!';

            // Reiniciar la animación correctamente
            message.classList.remove('slide-animation');
            void message.offsetWidth; 
            message.classList.add('slide-animation');

            // Eliminar el mensaje después de la animación
            setTimeout(() => {
                message.remove();
            }, 2000);

            // Actualizar el contador del carrito
            cartCount++;
            let cartCounter = document.querySelector('.cart-counter');
            if (!cartCounter) {
                cartCounter = document.createElement('span');
                cartCounter.className = 'cart-counter';
                cartIcon.parentElement.appendChild(cartCounter);
            }
            cartCounter.textContent = cartCount;

        })
        .catch(error => console.error('Error al añadir al carrito:', error));
    });
});




