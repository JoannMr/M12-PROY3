function updateQuantity(productId, delta) {
    const input = document.querySelector(`input[data-product-id="${productId}"]`);
    if (input) {
        let currentValue = parseInt(input.value);
        if (!isNaN(currentValue)) {
            input.value = Math.max(1, currentValue + delta);
            autoUpdateQuantity(productId, input.value);
        }
    }
}

function autoUpdateQuantity(productId, quantity) {
    fetch('carrito.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${productId}&quantity=${quantity}&update_quantity=true`
    }).then(response => {
        if (response.ok) {
            window.location.reload();
        }
    }).catch(error => console.error('Error al actualizar la cantidad:', error));
}

document.querySelectorAll('.btn-remove').forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        
        const productId = button.getAttribute('data-product-id');
        const productRow = document.querySelector(`.product-row[data-product-id="${productId}"]`);

        if (productRow) {
            productRow.classList.add('removing');

            setTimeout(() => {
                fetch('carrito.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `product_id=${productId}&remove=true`
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                }).catch(error => console.error('Error al eliminar el producto:', error));
            }, 500); 
        }
    });
});

