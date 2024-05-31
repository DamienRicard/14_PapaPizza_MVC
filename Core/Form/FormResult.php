<?php

namespace Core\Form;

class FormResult
{
  //gestion des messages de réussite
  private FormSuccess $success_message;

  /**
   * methode qui récupère les messages de success
   * @param FormSuccess $success_message
   */

  public function getSuccessMessage(): FormSuccess
  {
    return $this->success_message;
  }

  /**
   * methode qui permet d'ajouter un message de success
   * @param FormSuccess $success
   * @return void
   */
  public function addSuccess(FormSuccess $success):void
  {
    $this->success_message = $success;
  }

  /**
   * méthode qui vérifie si un message de success existe
   */
  public function hasSuccess():bool
  {
    return !empty($this->success_message);
  }

  //gestion messages d'erreur
  private array $form_errors = [];

  /**
   * methode qui récupère les messages d'erreur
   * @return array
   */
  public function getErrors():array
  {
    return $this->form_errors;
  }


  /**
   * méthode qui permet d'ajouter un message d'erreur
   * @param FormError $error
   * @return void
   */
  public function addError(FormError $error):void
  {
    $this->form_errors[] = $error;
  }


  /**
   * methode qui vérifie si un message d'erreur existe
   * @return bool
   */
  public function hasErrors():bool
  {
    return !empty($this->form_errors);
  }
}