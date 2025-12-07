<?php
session_start();
require_once("../tools.php");

$conn = Database::connect();

if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
}
