const qtyDisplay = document.getElementById("qty-display");
const actionInput = document.getElementById("action-input");
const form = document.getElementById("quantity-form");

async function updateQuantity(action) {
    const formData = new FormData(form);
    formData.set("action", action);

    try {
        const response = await fetch("php/product.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        // If PHP sends back a value, update the display
        if (result.quantity !== null) {
            // The frontend qty is already changed, so nothing here
        }
    } catch (error) {
        console.error("Error updating quantity:", error);
    }
}

document.getElementById("inc-btn").onclick = function () {
    let qty = parseInt(qtyDisplay.innerText);
    qty++;
    qtyDisplay.innerText = qty;
    updateQuantity("increase");
};

document.getElementById("dec-btn").onclick = function () {
    let qty = parseInt(qtyDisplay.innerText);
    if (qty > 1) {
        qty--;
        qtyDisplay.innerText = qty;
        updateQuantity("decrease");
    }
};

async function updateQuantity(action) {
    const formData = new FormData(form);
    formData.set("action", action);

    try {
        const response = await fetch("php/update_quantity.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (result.quantity !== null) {
            qtyDisplay.innerText = result.quantity;
        }
    } catch (error) {
        console.error("Error updating quantity:", error);
    }
}

