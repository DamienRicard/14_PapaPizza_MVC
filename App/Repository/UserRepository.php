<?php

namespace App\Repository;

use App\Model\User;
use Core\Repository\Repository;

class UserRepository extends Repository
{
  public function getTableName(): string
  {
    return 'user';
  }

  /**
   * methode pour ajouter un utilisateur
   * @param array $data
   * @return User|null
   */
  public function addUser(array $data): ?User
  {
    //on crée un tableau pour que le client ne soit pas admin et soit actif par defaut
    $data_more = [
      'is_admin' => 0,
      'is_active' => 1
    ];
    //on fusionne les 2 tableaux
    $data = array_merge($data, $data_more);

    //on peut créer la requête SQL
    $query = sprintf(
      'INSERT INTO %s (`email`, `password`, `firstname`, `lastname`, `phone`, `is_admin`, `is_active`) 
      VALUES (:email, :password, :firstname, :lastname, :phone, :is_admin, :is_active)',
      $this->getTableName()
    );
    //on prépare la requête
    $stmt = $this->pdo->prepare($query);
    //on vérifie que la requête est bien préparée
    if (!$stmt) return null;
    //on execute en passant les valeurs
    $stmt->execute($data);
    
    //on récupère l'id de l'utilisateur fraichenement crée
    $id = $this->pdo->lastInsertId();
    //on peut retourner l'objet User grâce à son id
    return $this->readById(User::class, $id);
  }
}
