const params = new URLSearchParams(window.location.search);
const errorParam = params.get("error");

if (errorParam) {
    document.getElementById("incorrect").hidden = false;
}


// invalid username
if (errorParam === "invalid") {
    document.getElementById("invalid").hidden = false;
}

// already registered / rejected
if (errorParam === "alreadyused") {
    document.getElementById("alreadyused").hidden = false;
}

// Check if login was successful via URL
const param = new URLSearchParams(window.location.search);
if (param.get("success") === "1") {
    // Mark user as logged in
    localStorage.setItem("userLoggedIn", "true");
}

// Toggle login/logout buttons
const loginBtn = document.getElementById("login-btn");
const logoutBtn = document.getElementById("logout-btn");

if (localStorage.getItem("userLoggedIn")) {
    loginBtn.hidden = true;
    logoutBtn.hidden = false;
}

logoutBtn.addEventListener("click", () => {
    localStorage.removeItem("userLoggedIn");
    loginBtn.hidden = false;
    logoutBtn.hidden = true;

    // Optionally, redirect to home page after logout
    window.location.href = "login.html";
});
