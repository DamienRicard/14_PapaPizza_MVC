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
}