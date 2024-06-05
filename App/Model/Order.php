<?php

namespace App\Model;
use Core\Model\Model;

class Order extends Model
{
  //en attente au panier
  public string $order_number;
  public string $date_order;
  public string $date_delivered;
  public string $status;
  public string $user_id;

  //propriété d'hydratation
  public ?User $user;
  
  public array $order_rows;
}