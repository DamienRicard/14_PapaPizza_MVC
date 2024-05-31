<?php

namespace App\Repository;

use Core\Repository\Repository;

class SizeRepository extends Repository
{
  public function getTableName(): string
  {
    return 'size'; //retourne le nom de la table
  }
}