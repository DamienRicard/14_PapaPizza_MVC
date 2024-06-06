<?php

namespace App\Repository;

use Core\Repository\Repository;

class OrderRowRepository extends Repository
{
  public function getTableName(): string
  {
    return 'order_row'; //retourne le nom de la table
  }

  /**
   * methode qui permet d'ajouter une ligne de commande
   * @param array $data
   * @return bool
   */

   public function insertOrderRow(array $data): bool
   {
    //on crée la requête sql
    $q = sprintf(
      'INSERT INTO `%s` (`order_id`, `pizza_id`, `quantity`, `price`) VALUES (:order_id, :pizza_id, :quantity, :price)',
      $this->getTableName()
    );

    //on prépare la requête
    $stmt = $this->pdo->prepare($q);
    //on verifie qu'elle est bien exécutée, si ça fonctionne on execute sinon on return false
    if(!$stmt->execute($data)) return false;

    return true;
   }
}