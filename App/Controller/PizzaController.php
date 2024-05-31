<?php

namespace App\Controller;

use Core\View\View;
use Core\Controller\Controller;

class PizzaController extends Controller
{
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
}