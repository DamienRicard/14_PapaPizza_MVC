<?php 

namespace App\Controller;

use Core\Controller\Controller;
use Laminas\Diactoros\ServerRequest;

class OrderController extends Controller
{
  public function addOrder(ServerRequest $request)
  {
    $form_data = $request->getParsedBody();
    var_dump($form_data);
  }
}