<?php

namespace App\Repository;

use Core\Repository\Repository;

class PizzaIngredientRepository extends Repository
{
  public function getTableName(): string
  {
    return 'pizza_ingredient'; //retourne le nom de la table
  }
}