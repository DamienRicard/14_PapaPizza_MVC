<?php

namespace App\Controller;

use Core\View\View;
use Core\Session\Session;
use Core\Controller\Controller;
use Laminas\Diactoros\ServerRequest;

class AuthController extends Controller
{
  /**
   * methode qui renvoie la vue du formulaire de connexion
   * @return void
   * 
   */
  public function loginForm()
  {
    $view = new View('auth/login');
    $view_data = [
      'form_result' => Session::get(Session::FORM_RESULT)
    ];

    $view->render($view_data);
  }

  /**
   * methode qui renvoie la vue du formulaire d'enregistrement
   * @return void
   * 
   */
  public function registerForm()
  {
    $view = new View('auth/register');
    $view_data = [
      'form_result' => Session::get(Session::FORM_RESULT)
    ];

    $view->render($view_data);
  }

  /**
   * methode qui permet de traiter le formulaire d'enregistrement
   */
  public function register(ServerRequest $request)
  {
    $data_form = $request->getParsedBody();
    var_dump($data_form);
  }

  /**
   * methode qui permet de traiter le formulaire de connexion
   */
  public function login()
  {
    
  }
}