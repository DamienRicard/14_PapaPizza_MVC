<?php

namespace App\Repository;

use App\Model\Pizza;
use App\AppRepoManager;
use Core\Repository\Repository;

class PizzaRepository extends Repository
{
  public function getTableName(): string
  {
    return 'pizza'; //retourne le nom de la table
  }

  /**
   * methode qui permet de récupérer toutes les pizzas de l'admin (de la pizzeria, de la carte)
   * @return array
   */
  public function getAllPizzas():array
  {
    //on déclare un tableau vide
    $array_result = [];

    //on crée la requête sql
    //1$ et 2$ pour indiquer que c'est une jointure, 2 tables différentes
    $query = sprintf(
      'SELECT p.`id`, p.`name`, p.`image_path`
      FROM %1$s   AS p           
      INNER JOIN %2$s AS u on p.`user_id` = u.`id`
      WHERE u.`is_admin` = 1
      AND p.`is_active` = 1
      ',
      $this->getTableName(),  //correspond au %1$s
      //appRepoManager renvoie au fichier AppRepoManager.php
      AppRepoManager::getRm()->getUserRepository()->getTableName()  //correspond au %2$s
    );

    //on peut directement executer la requête
    $stmt = $this->pdo->query($query);
    //on vérifei que la requête est bien exécutée
    if (!$stmt) return $array_result;
    //on récupère les données que l'on met dans notre tableau
    while($row_data = $stmt->fetch()){
      //à chaque passage de la boucle on instancie un objet Pizza, à chaque passage de la boucle on met les données dans le tableau
      $array_result[] = new Pizza($row_data);
    }
    return $array_result;
  }

  /**
   * methode qui permet de récupérer une pizza grâce à son id
   * @param int $pizza_id
   * @return ?Pizza
   */
  public function getPizzaById(int $pizza_id): ?Pizza
  {
    //on crée la requête sql
    $q = sprintf('SELECT * FROM %s WHERE `id` = :id', 
    $this->getTableName()
    );

    //on prépare la requête
    $stmt = $this->pdo->prepare($q);
    //on verifie que la requête est bien préparée
    if (!$stmt) return null;
    
    //on execute en passant les paramètres
    $stmt->execute(['id' => $pizza_id]);

    //on récupère le résultat
    $result = $stmt->fetch();

    //si je n'ai pas de résultat je retourne null
    if (!$result) return null;

    //si j'ai un résultat j'instancie un objet Pizza
    $pizza = new Pizza($result);

    //on va hydrater les ingredients de la pizza
    $pizza->ingredients = AppRepoManager::getRm()->getPizzaIngredientRepository()->getIngredientByPizzaId($pizza_id);

    //on va hydrater les prix de la pizza
    $pizza->prices = AppRepoManager::getRm()->getPriceRepository()->getPriceByPizzaId($pizza_id);

    //je retourne l'objet pizza
    return $pizza;


  }


  

}