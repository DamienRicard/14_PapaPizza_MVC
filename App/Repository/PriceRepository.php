<?php

namespace App\Repository;

use Core\Repository\Repository;

class PriceRepository extends Repository
{
  public function getTableName(): string
  {
    return 'price'; //retourne le nom de la table
  }
}