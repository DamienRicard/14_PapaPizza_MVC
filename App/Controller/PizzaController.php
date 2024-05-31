<?php

namespace App\Controller;

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
  public function getPizzas()
  {

  }
}