
document.getElementById("myImage").onclick = function() {
    window.location.href = "shop.php";
};
document.getElementById("myImage1").onclick = function() {
    window.location.href = "shop.php";
};
document.getElementById("myImage2").onclick = function() {
    window.location.href = "shop.php";
};

document.getElementById("home-login").onclick = function() {
    window.location.href = "index.php";
};


// Remove product
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".remove-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const productId = this.dataset.productid;
            // Create a temporary form and submit
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "php/remove.php";
            form.style.display = "none";

            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "product_id";
            input.value = productId;
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        });
    });
});