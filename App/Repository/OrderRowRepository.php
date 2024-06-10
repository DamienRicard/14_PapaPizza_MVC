<?php

namespace App\Repository;

use App\Model\Pizza;
use App\AppRepoManager;
use App\Model\OrderRow;
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
      'INSERT INTO `%s` (`order_id`, `pizza_id`, `quantity`, `price`, `size_id`) VALUES (:order_id, :pizza_id, :quantity, :price, :size_id)',
      $this->getTableName()
    );

    //on prépare la requête
    $stmt = $this->pdo->prepare($q);
    //on verifie qu'elle est bien exécutée, si ça fonctionne on execute sinon on return false
    if(!$stmt->execute($data)) return false;

    return true;
   }

   /**
    * methode qui récupère les lignes de commande liée à une commande
    * @param int $order_id
    * @return array
    */
    public function findOrderRowByOrder(int $order_id): ?array
    {
      //on déclare un tableau vide, mais plus une obligation depuis PHP8
      $array_result = [];

      //on crée la requête sql
      $q = sprintf(
        'SELECT * FROM `%s` WHERE `order_id` = :order_id',
        $this->getTableName()
      );

      //on prépare la requête
      $stmt = $this->pdo->prepare($q);

      //on vérifie qu'elle est bien exécutée
      if(!$stmt->execute(['order_id' => $order_id])) return null;

      //on récupère les résultats dans une boucle
      while($result = $stmt->fetch()){
        $orderRow = new OrderRow($result);
        //on va hydrater OrderRow pour avoir les infos de la pizza
        $orderRow->pizza = AppRepoManager::getRm()->getPizzaRepository()->readById(Pizza::class, $orderRow->pizza_id);

        //on ajoute l'objet orderRow dans le tableau
        $array_result[] = $orderRow;
      }

      return $array_result;
    }

    /**
     * methode qui calcule le montant total d'une commande
     * @param int $order_id
     * @return ?float
     */
    public function findTotalPriceByOrder(int $order_id): ?float
    {
      //on crée la requête sql
      $q = sprintf(
        'SELECT SUM(`price`) as total_price FROM `%s` WHERE `order_id` = :order_id',
        $this->getTableName()
      );

      //on prépare la requête
      $stmt = $this->pdo->prepare($q);

      //on vérifie qu'elle est bien exécutée
      if(!$stmt->execute(['order_id' => $order_id])) return null;

      //on récupère le résultat
      $result = $stmt->fetchObject();

      return $result->total_price ?? 0;
    }


    /**
     * methode qui additionne le nombre de pizzas pour chaque ligne de commande
     * @param int $order_id
     * @return ?int
     */
    public function countOrderRow(int $order_id): ?int
    {
      //on crée la requête sql
      $q = sprintf(
        'SELECT SUM(`quantity`) as count 
        FROM `%s` 
        WHERE `order_id` = :order_id',
        $this->getTableName()
      );

      //on prépare la requête
      $stmt = $this->pdo->prepare($q);

      //on vérifie qu'elle est bien exécutée
      if(!$stmt->execute(['order_id' => $order_id])) return 0;

      //on récupère le résultat
      $result = $stmt->fetchObject();

      return $result->count ?? 0;
    }


    /**
     * methode qui permet de mettre à jour une ligne de commandes
     * @param array $data
     * @return bool
     */
    public function updateOrderRow(array $data):bool
    {
      //on doit récupérer le prix de la pizza
      $pizza_price = AppRepoManager::getRm()->getPriceRepository()->getPriceByPizzaIdBySize($data['pizza_id'], $data['size_id']);
      //on va recalculer le prix total avec la nouvelle quantité
      $price = $pizza_price * $data['quantity'];

      //on crée la requête sql
      $q = sprintf(
        'UPDATE `%s` 
        SET `quantity` = :quantity, `price` = :price 
        WHERE `id` = :id',
        $this->getTableName()
      );

      //on prépare la requête
      $stmt = $this->pdo->prepare($q);

      //on verifie qu'elle est bien exécutée, si cela fonctionne on execute sinon on return false
      if(!$stmt) return false;

      return $stmt->execute([
        'id' => $data['id'],
        'quantity' => $data['quantity'],
        'price' => $price
      ]);
    }


    /**
     * methode qui permet de supprimer une ligne de commande
     * @param int $id
     * @return bool
     */
    public function deleteOrderRow(int $id):bool 
    {
        //on cree la requete SQL
        $q= sprintf(
            'DELETE FROM `%s` 
            WHERE `id` = :id',
            $this->getTableName()
        );

        //on prepare la requete
        $stmt = $this->pdo->prepare($q);

        //on verifie que la requete est bien préparée
        if(!$stmt) return false;

        return $stmt->execute(['id' => $id]);
    }


    /**
     * methode qui récupère le nombre de lignes de commandes d'une commande
     * @param int $order_id
     * @return ?int
     */
    public function countOrderRowByOrder(int $order_id):int
    {
        //on cree la requete SQL
        $q = sprintf(
            'SELECT COUNT(*) AS count 
            FROM `%s` 
            WHERE `order_id` = :order_id',
            $this->getTableName()
        );

        //on prepare la requete
        $stmt = $this->pdo->prepare($q);

        //on verifie que la requete est bien executée
        if(!$stmt->execute(['order_id' => $order_id])) return 0;

        //on recupere le resultat
        $result = $stmt->fetchObject();

        return $result->count;
    }
}