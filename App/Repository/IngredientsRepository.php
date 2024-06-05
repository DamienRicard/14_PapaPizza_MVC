<?php

namespace App\Repository;

use Core\Repository\Repository;

class IngredientsRepository extends Repository
{
  public function getTableName(): string
  {
    return 'ingredient'; //retourne le nom de la table
  }
}