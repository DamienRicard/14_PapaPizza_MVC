<?php

namespace App\Repository;

use App\AppRepoManager;
use Core\Repository\Repository;

class OrderRepository extends Repository
{
  public function getTableName(): string
  {
    return 'order'; //retourne le nom de la table
  }


  /**
   * methode qui permet de récupérer la derniere commande
   * @return ?int
   */
  public function findLastOrder(): ?int
  {
    $q = sprintf('SELECT * FROM %s ORDER BY id DESC LIMIT 1', 
    $this->getTableName());

    $stmt = $this->pdo->query($q);

    if (!$stmt) return null;

    $result = $stmt->fetchObject();

    return $result->id ?? 0;
  }


  /**
   * methode qui retourne une commande si elle est dans le panier
   * @param int $user_id
   * @param string $status
   * @return bool
   */
  public function findLastStatusByUser(int $user_id, string $status): bool
  {
    $q = sprintf('SELECT * FROM %s WHERE `user_id` = :user_id AND `status` = :status ORDER BY id DESC LIMIT 1',
    $this->getTableName());

    //on prépare la requête
    $stmt = $this->pdo->prepare($q);
    //on vérifie qu'elle est bien exécutée
    if(!$stmt->execute(['user_id' => $user_id, 'status' => $status])) return false;

    //on récupère les résultats
    $result = $stmt->fetchObject();

    //si pas de résultats on retourne false
    if(!$result) return false;

    //si on a des résultats, on vérifie si la commande contient des lignes

  }

  /**
   * methode qui retourne le nombre de lignes de commande
   * @param int $order_id
   * @return ?int
   */
  public function countOrderRow(int $order_id): ?int
  {
    //query qui additionne le nombre de lignes de commande
    $q = sprintf('SELECT SUM(quantity) as count FROM %s WHERE `order_id` = :order_id',
    AppRepoManager::getRm()->getOrderRowRepository()->getTableName());

    //on prépare la requête
    $stmt = $this->pdo->prepare($q);
    //on vérifie qu'elle est bien exécutée
    if(!$stmt->execute(['order_id' => $order_id])) return 0;

    //on récupère les résultats
    $result = $stmt->fetchObject();

    //si pas de résultats on retourne 0 sinon le nombre de lignes de commande
    if(!$result || is_null($result)) return 0;
    return $result->count;
  }
}