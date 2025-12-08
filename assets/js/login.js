const params = new URLSearchParams(window.location.search);
const errorParam = params.get("error");

if (errorParam) {
    // show only the combined error message
    document.getElementById("incorrect").hidden = false;
}
