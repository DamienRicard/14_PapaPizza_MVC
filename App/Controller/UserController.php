<?php

namespace App\Controller;

use Core\View\View;
use App\AppRepoManager;
use Core\Session\Session;
use Core\Controller\Controller;

class UserController extends Controller
{
  /**
   * methode qui renvoie la vue du panier d'un utilisateur
   * @param int|string $id
   * @return void
   */
  public function order(int|string $id):void
  {
    //on récupère la commande en cours avec toutes ses lignes de commande
    $order = AppRepoManager::getRm()->getOrderRepository()->findOrderInProgressWithOrderRow($id);
    //on récupère le total de la commande
    $total = $order ? AppRepoManager::getRm()->getOrderRowRepository()->findTotalPriceByOrder($order->id) : 0;  //si order non null, on calcule le total, sinon 0
    //on récupère les quantités de pizzas pour chaque ligne de commande
    $count_row = $order ? AppRepoManager::getRm()->getOrderRowRepository()->countOrderRow($order->id): 0;

    $view_data = [
      'order' => $order,
      'total' => $total,
      'count_row' => $count_row,
      'form_result' => Session::get(Session::FORM_RESULT),
      'form_success' => Session::get(Session::FORM_SUCCESS)
    ];



    $view = new View('user/order');

    $view->render($view_data);
  }

}