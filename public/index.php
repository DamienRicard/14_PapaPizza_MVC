<?php

use App\App;

const DS = DIRECTORY_SEPARATOR; //permet de gérer les slash
define('PATH_ROOT', dirname(__DIR__) . DS); //path_root ramène à la racine du projet

define('STRIPE_PK', 'pk_test_51PMWfND1oEiT5WrQcAGpiQUZl03Bk1lLFWfsmeWRnFbX0BOa4A64WBZdpHrA2qzvbTaWJXpoqjZP6bRqT4FXc07U000k7A9Yfj');

define('STRIPE_SK', 'sk_test_51PMWfND1oEiT5WrQdwXV5Y3FujGCUNqIJgSCVCG8UqXnmDC9FOZ6aYuYhobyZvfaUfPBHWV1vcb2FJOFrmfjAtc800lMHP6ypk');

require_once (PATH_ROOT . 'vendor/autoload.php');

App::getApp()->start();