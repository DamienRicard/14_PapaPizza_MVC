<?php

namespace Core\Repository;

use PDO;
use Core\Database\Database;
use Core\Database\DatabaseConfigInterface;

abstract class Repository
{
  //on crée une prop privée qui contiendra l'instance de PDO
  protected PDO $pdo;

  abstract public function getTableName(): string;

  public function __construct(DatabaseConfigInterface $config)
  {
    $this->pdo = Database::getPDO($config);
  }

  //ici on peut définir des méthodes génériques pour les repositories
  /**
   * methode qui récupère tous les éléments de la table
   * ex: SELECT * FROM table
   * @return array
   * @param string $class_name
   */
  public function readAll(string $class_name):array
  {
    //on déclare un tableau vide
    $array_result = [];
    //on crée notre requête sql
    $q = sprintf('SELECT * FROM %s', $this->getTableName());  // %s = s'attend à recevoir une string
    //on execute la requête
    $stmt = $this->pdo->query($q);
    // si la requête n'est pas valide on retourne un tableau vide
    if (!$stmt) {return $array_result;}
    // on récupère les données
    while($row_data = $stmt->fetch()){  //fetch retourne un tableau associatif
      $array_result[] = new $class_name($row_data);
    }
      return $array_result;
  }


    /**
   * methode qui récupère un élément de la table par son id
   * ex: SELECT * FROM table WHERE id = $id
   * @return object
   * @param string $class_name
   * @param int $id
   */
  public function readById(string $class_name, int $id): ?Object
  {
    //on crée notre requête sql
    $q = sprintf('SELECT * FROM %s WHERE id = :id', $this->getTableName());
    //on prépare la requête
    $stmt = $this->pdo->prepare($q);
    //on vérifie que la requête est bien préparée
    if (!$stmt) return null;
    //si tout est bon on bind les param
    $stmt->execute(['id' => $id]);  // tableau avec clé => valeur, la clé correspond au :id ligne 56 de la requête sprinftf
    $row_data = $stmt->fetch();

    return !empty($row_data) ? new $class_name($row_data) : null;
  }

 }