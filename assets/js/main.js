
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


document.addEventListener("DOMContentLoaded", () => {
    document.querySelector(".remove-btn").addEventListener("click", function() {

        let product_id = this.dataset.productid;
        let row = this.closest("tr");

        fetch("php/product.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "action=remove&product_id=" + product_id
        })
        .then(response => response.text())
        .then(data => {
            // Remove row from screen
            row.remove();
        })
        .catch(error => console.error("Error:", error));
    });
});
