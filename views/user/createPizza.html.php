<?php

use App\AppRepoManager;
use Core\Session\Session; ?>
<main class="container-form">
  <h1 class="title title-detail">Créez votre pizza</h1>
  <!--on importe le template de gestion d'erreurs et de success, PATH_ROOT renvoi à la racine du projet, on est sûr de naviguer jusqu'au template comme ça -->
  <?php include(PATH_ROOT . 'views/_templates/_message.html.php') ?>

  <!-- "enctype" sert à gérer les fichiers, envoyer des fichiers -->
  <form class="auth-form" action="/add-custom-pizza-form" method="POST" enctype="multipart/form-data">
    <!-- envoie l'utilisateur en session -->
    <input type="hidden" name="user_id" value="<?= Session::get(Session::USER)->id ?>">
    <!-- nom de la pizza -->
    <h3 class="sub-title">Je choisis un nom :</h3>
    <div class="box-auth-input">
      <input type="text" name="name" class="form-control">
    </div>
    <!-- Liste des ingrédients -->
    <h3 class="sub-title">Je choisis mes ingrédients :</h3>
    <div class="box-auth-input list-ingredient">
      <!-- on va boucler sur notre tableau d'ingrédients -->
      <?php foreach (AppRepoManager::getRm()->getIngredientsRepository()->getIngredientActiveByCategory() as $category => $ingredients) : ?>
        <div class="list-ingredient-box-update">
          <h5 class="title-update"> <?= ucfirst($category) ?> </h5>
          <?php foreach ($ingredients as $ingredient) : ?>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="ingredients[]" value="<?= $ingredient->id ?>" role="switch">
              <label class="form-check-label footer-description m-1"> <?= $ingredient->label ?> </label>
            </div>
          <?php endforeach; ?>
        </div>

      <?php endforeach; ?>
    </div>

    <!-- choix de la taille -->
    <div class="box-auth-input list-size">
      <h3 class="sub-title">Je choisis la taille></h3>
      <!-- affichage les différentes tailles -->
      <?php foreach (AppRepoManager::getRm()->getSizeRepository()->getAllSize() as $size) : ?>
        <div class="d-flex align-items-center">
          <div class="list-size-input me-2">
            <input type="radio" name="size_id" value="<?= $size->id ?>">
          </div>
          <label class="footer-description"> <?= $size->label ?> </label>
        </div>
      <?php endforeach; ?>

    </div>
    <!-- affichage du bouton submit -->
    <button type="submit" class="call-action">Creer ma pizza</button>
  </form>

</main>