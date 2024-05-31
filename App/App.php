<?php
//point d'entrée de l'application
namespace App;

use MiladRahimi\PhpRouter\Router;
use App\Controller\AuthController;
use App\Controller\PizzaController;
use Core\Database\DatabaseConfigInterface;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use MiladRahimi\PhpRouter\Exceptions\InvalidCallableException;

class App implements DatabaseConfigInterface
{
  //on définit des constantes
  private const DB_HOST = 'database';
  private const DB_NAME = 'database_lamp';
  private const DB_USER = 'admin';
  private const DB_PASS = 'admin';

  private static ?self $instance = null;
  //on crée une méthode publique qui sera appellée au démarrage de l'appli dans index.php
  public static function getApp(): self
  {
    if(is_null(self::$instance)){
      self::$instance = new self();
    }
    return self::$instance;
  }

  //on crée une propriété privée pour stocker le routeur
  private Router $router;
  //méthode qui récupère les infos du routeur
  public function getRouter()
  {
    return $this->router;
  }

  private function __construct()
  {
    //on crée l'instance de Router
    $this->router = Router::create();
  }

  //on a 3 méthodes à définir
  //1ere : méthode start pour activer le routeur, elle va appeller les 2 autres méthodes qui sont privées
  public function start():void
  {
    //enregistrements des routes
    $this->registerRoutes();
    //demarrage du routeur
    $this->startRouter();
  }

  //2eme : méthode qui enregistre les routes
  private function registerRoutes():void
  {
    // PARTIE AUTH : 
    //connexion
    $this->router->get('/connexion', [AuthController::class, 'loginForm']);
    $this->router->get('/inscription', [AuthController::class, 'registerForm']);
    $this->router->post('/login',[AuthController::class, 'login']);
    $this->router->post('/register',[AuthController::class, 'register']);

    // PARTIE PIZZA: 
    $this->router->get('/', [PizzaController::class, 'home']);
  }

  //3eme : la méthode qui démarre le routeur
  private function startRouter():void
  {
    try {
      $this->router->dispatch();
    } catch (RouteNotFoundException $e) {
      echo $e;
    } catch (InvalidCallableException $e) {
      echo $e;
    }
  }








  public function getHost(): string
  {
    return self::DB_HOST;
  }

  public function getName(): string
  {
    return self::DB_NAME;
  }

  public function getUser(): string
  {
    return self::DB_USER;
  }

  public function getPass(): string
  {
    return self::DB_PASS;
  }

}