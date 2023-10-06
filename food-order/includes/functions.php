<?php
function is_user_logged_in() {
    return isset($_SESSION['user_id']);
}

function redirect_if_not_logged_in() {
    if (!is_user_logged_in()) {
        header('Location: login.php');
        exit();
    }
}
?>