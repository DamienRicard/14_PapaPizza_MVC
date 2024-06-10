<?php

namespace App\Repository;

use App\Model\Order;
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
    $q = sprintf(
      'SELECT * FROM `%s` ORDER BY id DESC LIMIT 1',
      $this->getTableName()
    );

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
    $q = sprintf(
      'SELECT * FROM `%s` WHERE `user_id` = :user_id AND `status` = :status ORDER BY id DESC LIMIT 1',
      $this->getTableName()
    );

    //on prépare la requête
    $stmt = $this->pdo->prepare($q);
    //on vérifie qu'elle est bien exécutée
    if (!$stmt->execute(['user_id' => $user_id, 'status' => $status])) return false;

    //on récupère les résultats
    $result = $stmt->fetchObject();

    //si pas de résultats on retourne false
    if (!$result) return false;

    //si on a des résultats, on vérifie si la commande contient des lignes
    $count_row = $this->countOrderRow($result->id);
    //si on n'a pas de résultat on renvoi false
    if (!$count_row) return false;

    //si on a des résultats on renvoi true
    return true;
  }

  /**
   * methode qui retourne le nombre de lignes de commande
   * @param int $order_id
   * @return ?int
   */
  public function countOrderRow(int $order_id): ?int
  {
    //query qui additionne le nombre de lignes de commande
    $q = sprintf(
      'SELECT SUM(quantity) as count FROM `%s` WHERE `order_id` = :order_id',
      AppRepoManager::getRm()->getOrderRowRepository()->getTableName()
    );

    //on prépare la requête
    $stmt = $this->pdo->prepare($q);
    //on vérifie qu'elle est bien exécutée
    if (!$stmt->execute(['order_id' => $order_id])) return 0;

    //on récupère les résultats
    $result = $stmt->fetchObject();

    //si pas de résultats on retourne 0 sinon le nombre de lignes de commande
    if (!$result || is_null($result)) return 0;
    return $result->count;
  }


  /**
   * methode qui permet de créer/inserer une commande
   * @param array $data
   * @return ?int
   */
  public function insertOrder(array $data): ?int
  {
    //on crée la requête sql
    $q = sprintf(
      'INSERT INTO `%s` (`order_number`, `date_order`, `status`, `user_id`) VALUES (:order_number, :date_order, :status, :user_id)',
      $this->getTableName()
    );

    //on prépare la requête
    $stmt = $this->pdo->prepare($q);
    //on vérifie qu'elle est bien exécutée, sinon return null (mais elle s'exécute quand même, pas besoin de lui dire)
    if (!$stmt->execute($data)) return null;

    //on retourne l'id de la commande qui a été inserée
    return $this->pdo->lastInsertId();  //lastInsertId() appartient à PHP

  }

  /**
   * methode qui retourne l'id de la commande si status = IN_CART (mise au panier) pour UN utilisateur
   * @param int $user_id
   * @return ?int 
   */
  public function findOrderIdByStatus(int $user_id): ?int
  {
    $status = Order::IN_CART;

    //on crée la requête sql
    $q = sprintf(
      'SELECT * FROM `%s` WHERE `user_id` = :user_id AND `status` = :status ORDER BY id DESC LIMIT 1',  //:status signifie qu'on va passer des paramètres dynamiques (qui peuvent changer dans la requête)
      $this->getTableName()
    );

    //on prépare la requête
    $stmt = $this->pdo->prepare($q);
    //on vérifie qu'elle est bien exécutée
    if (!$stmt->execute(['user_id' => $user_id, 'status' => $status])) return null;   //si elle ne s'execute pas on retourne null

    //on récupère les résultats
    $result = $stmt->fetchObject();

    //si pas de resultat on retourne null
    if (!$result) return null;

    //sinon on retourne l'id de la commande qui a été inserée
    return $result->id;
  }

  /**
   * methode qui récupère la commande en cours d'un utilisateur avec les lignes de commande
   * @param int $user_id
   * @return ?object
   */
  public function findOrderInProgressWithOrderRow(int $user_id): ?object
  {
    //on crée la requête sql
    $q = sprintf(
      'SELECT * 
      FROM `%s` 
      WHERE `user_id` = :user_id 
      AND `status` = :status',
      $this->getTableName()
    );

    //on prépare la requête
    $stmt = $this->pdo->prepare($q);
    //on exécute la requête
    if(!$stmt->execute(['user_id' => $user_id, 'status' => Order::IN_CART])) return null;

    //on recupère les resultats
    $result = $stmt->fetchObject();

    //si pas de resultat on retourne null
    if (!$result) return null;

    //si on a un résultat on doit hydrater notre objet Order avec toutes ses lignes de commandes associées
    $result->order_rows = AppRepoManager::getRm()->getOrderRowRepository()->findOrderRowByOrder($result->id);

    return $result;
  }


  /**
     * methode qui permet de supprimer une commande
     * @param int $id
     * @return bool
     */
    public function deleteOrder(int $id): bool
    {
        //on cree la requete SQL
        $q = sprintf(
            'DELETE FROM `%s` 
            WHERE `id` = :id',
            $this->getTableName()
        );

        //on prepare la requete
        $stmt = $this->pdo->prepare($q);

        //on verifie que la requete est bien préparée
        if (!$stmt) return false;

        return $stmt->execute(['id' => $id]);
    }

}
