<?php

namespace Core\Repository;

trait  RepositoryManagerTrait
{
  /**
   * Un trait permet de gérer une portion de code directement dans une classe
   * sans notion de hiérarchie, ce n'est pas un héritage
   * dans ce trait on va utiliser la notion de self qui fera référence à la classe qui utilise le trait
   * Ici on aura un design de singleton pattern
   */

   //on crée une propriété privée qui contiendra l'instance de la classe qui utilise le trait
   private static ?self $rm_instance = null;  //stocke une instance de lui même

   public static function getRm():self
   {
       if (is_null(self::$rm_instance)) {
           self::$rm_instance = new self();
       }
       return self::$rm_instance;
   }

   protected function __construct() {}
   protected function __clone() {}
}