<?php

namespace App\Controller;

use App\AppRepoManager;
use Core\View\View;
use Core\Controller\Controller;

class PizzaController extends Controller
{
  /**
   * methode qui renvoie la vue de la page d'accueil
   * @return void
   */
  public function home()
  {
   //préparation des données à transmettre à la vue
   $view_data = [
     'title' => 'Accueil',
     'pizza_list' => [
       'Margherita',
       'Hawaiana',
       'Napolitana'
     ]
   ];

   $view = new View('home/home');
   $view->render($view_data);
  }

  
  /**
   * methode qui renvoie la vue de la liste des pizzas
   * @return void
   */
  public function getPizzas():void
  {
    //le controleur doit récupérer le tableau de pizzas via le repository, pour le donner à la vue
    $view_data = [
      'h1' => 'Notre carte',
      'pizzas' =>  AppRepoManager::getRm()->getPizzaRepository()->getAllPizzas()
    ];

    $view = new View('home/pizzas');
    $view->render($view_data);
  }

  /**
   * Methode qui renvoie la vue d'une pizza grâce à son id
   * @param int $id
   * @return void car appelle seulement une vue !
   */
  public function getPizzaById(int $id):void
  {
    $view_data = [
      'pizza' => AppRepoManager::getRm()->getPizzaRepository()->getPizzaById($id)
    ];

    $view = new View('home/pizza_detail');
    $view->render($view_data);
  }
}