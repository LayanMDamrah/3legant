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

// =====================================
//  HANDLE LOGIN ERRORS
// =====================================
const param = new URLSearchParams(window.location.search);
const errorParams = param.get("error");

if (errorParams) {
    const incorrectMsg = document.getElementById("incorrect");
    if (incorrectMsg) incorrectMsg.hidden = false;
}

// username invalid
if (errorParams === "invalid") {
    const invalid = document.getElementById("invalid");
    if (invalid) invalid.hidden = false;
}

// already used
if (errorParams === "alreadyused") {
    const used = document.getElementById("alreadyused");
    if (used) used.hidden = false;
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




//  Logout 
if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
        localStorage.removeItem("userLoggedIn");
        window.location.href = "login.php";
    });
}


// block everything before logingin
document.addEventListener("DOMContentLoaded", () => {
    const loggedIn = localStorage.getItem("userLoggedIn") === "true";

    if (!loggedIn) {
        const protectedElements = document.querySelectorAll("a, button, img");

        protectedElements.forEach(el => {
            el.addEventListener("click", (e) => {

                // allow form submission
                if (el.type === "submit") return;

                // allow login button (navbar)
                if (el.id === "login-btn") return;

                // allow signup 
                if (el.id === "signup-btn") return;

                e.preventDefault();
                window.location.href = "login.php";
            });
        });
    }
});
