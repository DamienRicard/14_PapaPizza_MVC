<?php

namespace App\Repository;

use App\Model\Size;
use App\Model\Price;
use App\AppRepoManager;
use Core\Repository\Repository;

class PriceRepository extends Repository
{
  public function getTableName(): string
  {
    return 'price'; //retourne le nom de la table
  }

  /**
   * Methode qui permet de récupérer les prix d'une pizza par son id avec sa taille associé
   * @param int $pizza_id
   * @return array
   */
  public function getPriceByPizzaId(int $pizza_id):array
  {
    //on déclare un tableau vide
    $array_result = [];
    //on crée notre requête sql
    $q = sprintf(
      'SELECT price.*, size.`label`
      FROM %1$s AS price
      INNER JOIN %2$s AS size ON price.`size_id` = size.`id`
      WHERE price.`pizza_id` = :id',
      $this->getTableName(), //CORRESPOND au %1$s
      AppRepoManager::getRm()->getSizeRepository()->getTableName() //CORRESPOND au %2$s
    );

    //on prépare la requête
    $stmt = $this->pdo->prepare($q);

    //on verifie que la requête est bien exécutée
    if (!$stmt) return $array_result;

    //on execute en passant les paramètres (id de la pizza)
    $stmt->execute(['id' => $pizza_id]);

    //on recupere les résultats  $row_data on met le nom que l'on veut
    while($row_data = $stmt->fetch()){
      //à chaque passage de la boucle on instancie un objet price
      $price = new Price($row_data);

      //on va reconstruire à la main un tableau pour créer une instance de Size
      $size_data = [
        'id' => $row_data['size_id'],
        'label' => $row_data['label']
      ];

      //on peut maintenant instancier un objet Size
      $size = new Size($size_data);

      //on va hydrater Price avec Size
      $price->size = $size;

      //on rempli le tableau avec l'objet Price
      $array_result[] = $price;
    }

    //on retourne le tableau fraichement rempli
    return $array_result;
  }
}