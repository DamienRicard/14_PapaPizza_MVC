<?php

namespace App\Controller;

use Core\View\View;
use App\AppRepoManager;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Core\Form\FormSuccess;
use Core\Controller\Controller;
use Laminas\Diactoros\ServerRequest;

class PizzaController extends Controller
{
  /**
   * methode qui renvoie la vue de la page d'accueil
   * @return void
   */
  public function home()
  {
    //préparation des données à transmettre à la vue
    $view_data = [
      'title' => 'Accueil',
      'pizza_list' => [
        'Margherita',
        'Hawaiana',
        'Napolitana'
      ]
    ];

    $view = new View('home/home');
    $view->render($view_data);
  }


  /**
   * methode qui renvoie la vue de la liste des pizzas
   * @return void
   */
  public function getPizzas(): void
  {
    //le controleur doit récupérer le tableau de pizzas via le repository, pour le donner à la vue
    $view_data = [
      'h1' => 'Notre carte',
      'pizzas' =>  AppRepoManager::getRm()->getPizzaRepository()->getAllPizzas()
    ];

    $view = new View('home/pizzas');
    $view->render($view_data);
  }

  /**
   * Methode qui renvoie la vue d'une pizza grâce à son id
   * @param int $id
   * @return void car appelle seulement une vue !
   */
  public function getPizzaById(int $id): void
  {
    $view_data = [
      'pizza' => AppRepoManager::getRm()->getPizzaRepository()->getPizzaById($id),
      'form_result' => Session::get(Session::FORM_RESULT),
      'form_success' => Session::get(Session::FORM_SUCCESS),
    ];

    $view = new View('home/pizza_detail');
    $view->render($view_data);
  }


  /**
   * methode qui permet d'ajouter une pizza custom
   * @param ServerRequest $request
   * @return void
   */
  public function addCustomPizzaForm(ServerRequest $request): void
  {
    $data_form = $request->getParsedBody();
    $form_result = new FormResult();
    //on redefinie les variables
    $name = $data_form['name'] ?? '';
    $user_id = $data_form['user_id'] ?? '';
    $ingredients = $data_form['ingredients'] ?? [];
    $size_id = $data_form['size_id'] ?? '';
    $array_ingredients = count($ingredients);
    $image_path = 'pizza-custom.png';

    //on passe aux vérifications des donées
    if (empty($name) || empty($user_id) || empty($ingredients) || empty($size_id)) {
      $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
    } elseif ($array_ingredients < 2) {
      $form_result->addError(new FormError('Veuillez choisir au moins 2 ingredients'));
    } elseif ($array_ingredients > 8) {
      $form_result->addError(new FormError('Veuillez choisir moins de 8 ingredients'));
    } else {
      //on définit un prix fixe par taille et par ingrédient
      if ($size_id == 1) {
        $price = 6 + ($array_ingredients * 1);
      } elseif ($size_id == 2) {
        $price = 7 + ($array_ingredients * 1.25);
      } else {
        $price = 8 + ($array_ingredients * 1.5);
      }

      //on peut reconstruire un tableau de données pour insérer la pizza
      $pizza_data = [
        'name' => htmlspecialchars(trim($name)),
        'image_path' => $image_path,
        'user_id' => intval ($user_id),
        'is_active' => 1,
      ];

      //on récupère l'id de la pizza qui vient d'être créee
      $pizza_id = AppRepoManager::getRm()->getPizzaRepository()->insertPizza($pizza_data);
      if(is_null($pizza_id)){
        $form_result->addError(new FormError('Une erreur est survenue lors de la création de la pizza'));
      }
      //on va boucler sur les ingrédients, $ingredients=données (ingrédients)du formulaire
      foreach ($ingredients as $ingredient) {
        //on reconstruit un tableau de données pour insérer les ingrédients
        $pizza_ingredient_data = [
          'pizza_id' => intval($pizza_id),
          'ingredient_id' => intval($ingredient),
          'unit_id' => 5,
          'quantity' => 1,
        ];
        //toujours dans la boucle on appelle la méthode qui insere les ingrédients dans table pizza_ingredient
        $pizza_ingredient = AppRepoManager::getRm()->getPizzaIngredientRepository()->insertPizzaIngredient($pizza_ingredient_data);
        if(!$pizza_ingredient){
          $form_result->addError(new FormError('Une erreur est survenue lors de l\'ajout des ingrédients de la pizza'));
        }
      }

      //on reconstruit un tableau pour inserer les prix
      $pizza_price_data = [
        'pizza_id' => intval($pizza_id),
        'price' => floatval($price),
        'size_id' => intval($size_id)
      ];

      $pizza_price = AppRepoManager::getRm()->getPriceRepository()->insertPrice($pizza_price_data);
      if(!$price){
        $form_result->addError(new FormError('Une erreur est survenue lors de l\'ajout des prix'));
      }

      //si tout est ok on peut ajouter un message success
      $form_result->addSuccess(new FormSuccess('La pizza a bien été ajoutée'));
    }
    //si on a des erreurs on les met en session pour les interpreter
    if ($form_result->hasErrors()) {
      Session::set(Session::FORM_RESULT, $form_result);
      //on redirige sur la page detail de la pizza
      self::redirect('/user/list-custom-pizza/' . $user_id);
    }

    //si on a des success on les met en session pour les interpreter
    if ($form_result->getSuccessMessage()) {
      Session::remove(Session::FORM_RESULT);
      Session::set(Session::FORM_SUCCESS, $form_result);
      //on redirige sur la page detail de la pizza
      self::redirect('/user/createPizza/' . $user_id);   //TODO : canger le redirection vers la liste des pizzas custom
    }
  }
}
