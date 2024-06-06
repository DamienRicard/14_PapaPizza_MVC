<?php

namespace App\Controller;

use App\Model\Order;
use App\AppRepoManager;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Core\Form\FormSuccess;
use Core\Controller\Controller;
use Laminas\Diactoros\ServerRequest;

class OrderController extends Controller
{
  /**
   * methode qui permet de générer un numéro de commande unique
   */
  private function generateOrderNumber()
  {
    //je veux un numéro de commande de type: FACT2406_00001 par exemple
    $order_number = 1;
    $order = AppRepoManager::getRm()->getOrderRepository()->findLastOrder();
    $order_number = str_pad($order + 1, 5, "0", STR_PAD_LEFT);  // 5 caractères au max pour le numero de commande
    $year = date('Y');
    $month = date('m');

    $final = "FACT{$year}{$month}_{$order_number}"; // accolades pour pouvoir passer les variables dedans

    return $final;
  }


  public function addOrder(ServerRequest $request)
  {
    //on réceptionne les données
    $form_data = $request->getParsedBody();
    $form_result = new FormResult();

    //on redefini nos variables
    $order_number = $this->generateOrderNumber();
    $date_order = date('Y-m-d H:i:s');
    $status = Order::IN_CART;
    $user_id = $form_data['user_id'];
    $has_order_in_cart = AppRepoManager::getRm()->getOrderRepository()->findLastStatusByUser($user_id, Order::IN_CART);
    // var_dump($has_order_in_cart);
    $pizza_id = $form_data['pizza_id'];
    $quantity = $form_data['quantity'];
    $price = $form_data['price'] * $quantity;

    //on vérifie que la quantité est bien supérieure à 0
    if ($quantity <= 0) {
      $form_result->addError(new FormError('La quantité doit être superieure à 0'));
      //on vérifie que la quantité est bien inférieure à 10
    } elseif ($quantity > 10) {
      $form_result->addError(new FormError('La quantité ne peut pas être supérieure à 10'));
      //on vérifie que l'utilisateur n'a pas déjà une commande "mise au panier"
    } elseif (!$has_order_in_cart) {
      //on doit créer une nouvelle commande (order)
      //on reconstruit un tableau de données pour la commande
      $data_order = [
        'order_number' => $order_number,
        'date_order' => $date_order,
        'status' => $status,
        'user_id' => $user_id
      ];
      $order_id = AppRepoManager::getRm()->getOrderRepository()->insertOrder($data_order);

      //si $order_id est différent de null, on affiche un message d'erreur
      if ($order_id) {
        //on doit ajouter une nouvelle ligne de commande
        //on reconstruit un tableau de données pour la nouvelle ligne de commande
        $data_order_row = [
          'pizza_id' => $pizza_id,
          'quantity' => $quantity,
          'price' => $price,
          'order_id' => $order_id
        ];
        $order_line = AppRepoManager::getRm()->getOrderRowRepository()->insertOrderRow($data_order_row);
        if ($order_line) {
          $form_result->addSuccess(new FormSuccess('Pizza ajoutée au panier'));
        } else {
          $form_result->addError(new FormError('Une erreur est survenue lors de la creation de la commande'));
        }
      } else {
        $form_result->addError(new FormError('Une erreur est survenue lors de la creation de la commande'));
      }
    }else{
      //si l'utilisateur a déjà une commande en cours
      //on récupère l'id de la commande en cours
      $order_id = AppRepoManager::getRm()->getOrderRepository()->findOrderIdByStatus($user_id);
      if ($order_id) {
        //on doit ajouter une nouvelle ligne de commande
        //on reconstruit un tableau de données pour la nouvelle ligne de commande
        $data_order_row = [
          'pizza_id' => $pizza_id,
          'quantity' => $quantity,
          'price' => $price,
          'order_id' => $order_id
        ];
        $order_line = AppRepoManager::getRm()->getOrderRowRepository()->insertOrderRow($data_order_row);
        if ($order_line) {
          $form_result->addSuccess(new FormSuccess('Pizza ajoutée au panier'));
        } else {
          $form_result->addError(new FormError('Une erreur est survenue lors de la creation de la commande'));
        }
      } else {
        $form_result->addError(new FormError('Une erreur est survenue lors de la récupération de l\'id de la commande'));
      }
    }
    //si on a des erreurs on les met en session pour les interpreter
    if($form_result->hasErrors()){
      Session::set(Session::FORM_RESULT, $form_result);
      //on redirige sur la page detail de la pizza
      self::redirect('/pizza/' . $pizza_id);
    }

    //si on a des success on les met en session pour les interpreter
    if($form_result->getSuccessMessage()){
      Session::remove(Session::FORM_RESULT);
      Session::set(Session::FORM_SUCCESS, $form_result);
      //on redirige sur la page detail de la pizza
      self::redirect('/pizza/' . $pizza_id);
    }
  }
}
