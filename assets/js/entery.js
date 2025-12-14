// Login errors
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


// login success
const success = new URLSearchParams(window.location.search).get("success");

if (success === "1") {
    localStorage.setItem("userLoggedIn", "true");
}

// show/ hide login and logout buttons
const loginBtn = document.getElementById("login-btn");
const logoutBtn = document.getElementById("logout-btn");

// Handle login button click
if (loginBtn) {
    loginBtn.addEventListener("click", () => {
        window.location.href = "login.php";
    });
}

if (localStorage.getItem("userLoggedIn") === "true") {
    if (loginBtn) loginBtn.hidden = true;
    if (logoutBtn) logoutBtn.hidden = false;
} else {
    if (loginBtn) loginBtn.hidden = false;
    if (logoutBtn) logoutBtn.hidden = true;
}


logoutBtn.addEventListener("click", () => {
    localStorage.removeItem("userLoggedIn");
    window.location.href = "logout.php";
});
