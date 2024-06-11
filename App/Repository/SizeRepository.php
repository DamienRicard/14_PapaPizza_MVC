<?php

namespace App\Repository;

use App\Model\Size;
use Core\Repository\Repository;

class SizeRepository extends Repository
{
  public function getTableName(): string
  {
    return 'size'; //retourne le nom de la table
  }

  /**
   * methode qui récupère la liste des tailles
   * @return array
   * 
   */
  public function getAllSize(): array
  {
    return $this->readAll(Size::class);
  }

}