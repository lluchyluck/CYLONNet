<?php
require_once "../../config.php";

if (!isset($_SESSION["login"]) || !isset($_SESSION["developer"])) {
    echo 0;
    exit;
}

if ($_SESSION["login"] === true && $_SESSION["developer"] === true) {
    echo 1;
} else {
    echo 0;
}