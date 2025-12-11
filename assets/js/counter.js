document.addEventListener('DOMContentLoaded', () => {
    let qty = 1;

    const decBtn = document.getElementById('dec-btn');
    const incBtn = document.getElementById('inc-btn');
    const qtyDisplay = document.getElementById('qty-display');
    const qtyInput = document.getElementById('add-to-cart-qty');
    const addToCartForm = document.getElementById('add-to-cart-form');

    // Function to sync span and hidden input
    function updateQty() {
        qtyDisplay.textContent = qty;
        qtyInput.value = qty;
    }

    decBtn.addEventListener('click', () => {
        if (qty > 1) qty--;
        updateQty();
    });

    incBtn.addEventListener('click', () => {
        qty++;
        updateQty();
    });

    // This is the crucial part: ensure the latest quantity is sent
    addToCartForm.addEventListener('submit', (e) => {
        updateQty(); // must run right before submit
    });

    // Initialize
    updateQty();
});