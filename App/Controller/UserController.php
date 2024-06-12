<?php

namespace App\Controller;

use Core\View\View;
use App\AppRepoManager;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Core\Form\FormSuccess;
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

  /**
   * methode pour afficher le formulaire de création de pizza custom
   * @param int $id
   * @return void , car retourne une vue
   * @throws \Exception
   */
  public function createPizza(int $id):void
  {
    //ces 2 variables permettent de récupérer les erreurs et les afficher dans le formulaire
    $view_data = [
      'form_result' => Session::get(Session::FORM_RESULT),
      'form_success' => Session::get(Session::FORM_SUCCESS),
    ];

    $view = new View('user/createPizza');

    $view->render($view_data);
  }


  /**
   * methode qui retourne la liste des pizzas custom d'un utilisateur
   * @param int $id
   * @return void
   */
  public function listCustomPizza(int $id):void
  {
    //on doit récupérer les pizzas custom de l'utilisateur getPizzaByUser
    $view_data = [
      'h1' => 'Liste de vos pizzas',
      'pizzas' => AppRepoManager::getRm()->getPizzaRepository()->getPizzaByUser($id),
      'form_result' => Session::get(Session::FORM_RESULT),
      'form_success' => Session::get(Session::FORM_SUCCESS),
    ];

    $view = new View('user/pizzas');
    $view->render($view_data);
  }


  /**
   * methode qui "supprime" une pizza
   * @param int $id
   * @return void
   */
  public function deletePizza(int $id):void
  {
    $form_result = new FormResult();
    $user_id = Session::get(Session::USER)->id;

    //appel de la methode qui désactive la pizza
    $deletePizza = AppRepoManager::getRm()->getPizzaRepository()->deletePizza($id);

    //on vérifie le retour de la methode
    if (!$deletePizza) {
      $form_result->addError(new FormError('Erreur lors de la suppression de la pizza'));
    } else {
      $form_result->addError(new FormError('La pizza a bien été supprimée'));
    }
    //si on a des erreurs on les met en session pour les interpreter
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      //on redirige sur la page liste des pizzas custom
      self::redirect('/user/list-custom-pizza/' . $user_id);
    }

    //si on a des success on les met en session pour les interpreter
    if ($form_result->getSuccessMessage()) {
      Session::remove(Session::FORM_RESULT);
      Session::set(Session::FORM_SUCCESS, $form_result);
      //on redirige sur la page liste des pizzas custom
      self::redirect('/user/list-custom-pizza/' . $user_id);
    }
  }


  /**
   * methode qui retourne la liste de commandes d'un utilisateur
   * @param int $id
   * @return void
   */
  public function listOrder(int $id):void
  {
    $view_data = [
      'orders' => AppRepoManager::getRm()->getOrderRepository()->findOrderByUser($id),
      'form_result' => Session::get(Session::FORM_RESULT),
      'form_success' => Session::get(Session::FORM_SUCCESS)
    ];

    $view = new View('user/listOrder');

    $view->render($view_data);
  }


}