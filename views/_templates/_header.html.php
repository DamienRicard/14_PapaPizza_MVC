<?php

use App\Controller\OrderController;
use Core\Session\Session;

//juste pour récupérer le client en session et utiliser $user_id plus bas
if ($auth::isAuth()) $user_id = Session::get(Session::USER)->id;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- import librairie bootstrap icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
    <!-- import librairie bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- import mon fichier de style -->
    <link rel="stylesheet" href="/style_homepage.css">
    <link rel="stylesheet" href="/style_pizza.css">
    <link rel="stylesheet" href="/auth_style.css">

    <title>Maquette Pizza</title>
</head>

<body>

    <header>
       <?php include(PATH_ROOT . '/views/_templates/_navbar.html.php'); ?>
    </header>