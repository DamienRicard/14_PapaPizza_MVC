<?php

namespace Core\Model;

class Model
{
  public int $id;
    
  public function __construct(array $data_row = []){
    //si on a des donnée on les met dans l'objet courant
    foreach($data_row as $column => $value){
      //si la propriété n'existe pas, on passe à la suivante
      if(!property_exists($this, $column))continue;
      
      //sinon on injecte la valeur dans la propriété
      $this->$column = $value;
    }
  }
}