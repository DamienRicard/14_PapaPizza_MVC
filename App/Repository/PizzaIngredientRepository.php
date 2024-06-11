<?php

namespace App\Repository;

use App\AppRepoManager;
use App\Model\Ingredient;
use Core\Repository\Repository;

class PizzaIngredientRepository extends Repository
{
  public function getTableName(): string
  {
    return 'pizza_ingredient'; //retourne le nom de la table
  }


  /**
   * méthode qui récupère les ingrédients d'une pizza par son id
   * @param int $pizza_id
   * @return array
   */
  public function getIngredientByPizzaId(int $pizza_id):array
  {
    //on déclare un tableau vide
    $array_result = [];
    //on crée notre requête sql
    $q = sprintf(
      'SELECT * 
      FROM %1$s AS pi
      INNER JOIN %2$s AS i ON pi.`ingredient_id` = i.`id`
      WHERE pi.`pizza_id` = :id',
      
      $this->getTableName(), //CORRESPOND au %1$s
      AppRepoManager::getRm()->getIngredientsRepository()->getTableName() //CORRESPOND au %2$s
    );

    //on prépare la requête
    $stmt = $this->pdo->prepare($q);

    //on verifie que la requête est bien exécutée
    if (!$stmt) return $array_result;

    //on execute en passant les paramètres (id de la pizza)
    $stmt->execute(['id' => $pizza_id]);

    //on recupere les résultats  $row_data on met le nom que l'on veut
    while($row_data = $stmt->fetch()){
      $array_result[] = new Ingredient($row_data);
    }

    //on retourne le tableau fraichement rempli
    return $array_result;
  }


  /**
   * methode qui permet d'ajouter des ingrédients de pizza
   * @param array $data
   * @return bool
   */
  public function insertPizzaIngredient(array $data): bool
  {
    //on crée la requête sql, on insère dans les colonnes name, image_path, user_id, is_active
    $q = sprintf('INSERT INTO `%s` (`pizza_id`, `ingredient_id`, `unit_id`, `quantity`) 
    VALUES (:pizza_id, :ingredient_id, :unit_id, :quantity)',
    $this->getTableName()
    );

    //on prepare la requête
    $stmt = $this->pdo->prepare($q);

    //on verifie que la requête est bien préparée
    if (!$stmt) return false;

    //on execute en passant les paramètres
    $stmt->execute($data);
    
    //on regarde si on a au moins une ligne qui a été inséré
    return $stmt->rowCount() > 0;
  }
}