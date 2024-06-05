<?php 

namespace App\Controller;

use App\Model\Order;
use App\AppRepoManager;
use Core\Form\FormResult;
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
    $year = date ('Y');
    $month = date ('m');

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
  }
}