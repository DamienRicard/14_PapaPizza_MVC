<?php

namespace App\Repository;

use App\Model\Ingredient;
use Core\Repository\Repository;

class IngredientsRepository extends Repository
{
  public function getTableName(): string
  {
    return 'ingredient'; //retourne le nom de la table
  }

  /**
   * methode qui récupère tous les ingrédients actifs rangés par catégorie
   * @return array
   * @param int $category_id
   */
  public function getIngredientActiveByCategory(): array
  {
    //on crée un tableau vide
    $array_result = [];
    //on crée notre requête sql
    $q = sprintf(
      'SELECT * 
      FROM `%s`
      WHERE `is_active` = 1
      ORDER BY `category` ASC',
      $this->getTableName()
    );
    //on execute la requête
    $stmt = $this->pdo->query($q);
    //on verifie que la requête est bien exécutée
    if (!$stmt) return $array_result;
    //on recupère les données que l'on met dans notre tableau
    while ($row_data = $stmt->fetch()) {
      //dans mon tableau je crée une clé de row data category
      $array_result[$row_data['category']] []= new Ingredient($row_data);
    }
      return $array_result;
  }
}