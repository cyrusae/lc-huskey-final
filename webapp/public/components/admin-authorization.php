<?php

if ($_COOKIE['isSiteAdministrator'] != true) {
    header('Location: /index.php');
    exit;
}

?>