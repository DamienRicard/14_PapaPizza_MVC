<?php

use App\App;

const DS = DIRECTORY_SEPARATOR; //permet de gérer les slash
define('PATH_ROOT', dirname(__DIR__) . DS); //path_root ramène à la racine du projet

require_once (PATH_ROOT . 'vendor/autoload.php');

App::getApp()->start();