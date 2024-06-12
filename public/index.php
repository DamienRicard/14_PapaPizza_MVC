<?php


use App\App;
use Dotenv\Dotenv;

const DS = DIRECTORY_SEPARATOR; //permet de gérer les slash

define('PATH_ROOT', dirname(__DIR__) . DS); //path_root ramène à la racine du projet

require_once (PATH_ROOT . 'vendor/autoload.php');

//permet de charger le fichier .env
Dotenv::createImmutable(PATH_ROOT)->load();

//pour récupérer les infos du .env on utilise $_ENV['NOM_DE_LA_KEY']
define('STRIPE_PK', $_ENV['STRIPE_PK']);
define('STRIPE_SK', $_ENV['STRIPE_SK']);

//on définit des constantes
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);


App::getApp()->start();