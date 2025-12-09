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

