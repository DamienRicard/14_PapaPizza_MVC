<?php

namespace App\Repository;

use Core\Repository\Repository;

class PizzaRepository extends Repository
{
  public function getTableName(): string
  {
    return 'pizza'; //retourne le nom de la table
  }
}