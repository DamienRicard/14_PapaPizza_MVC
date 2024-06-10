<?php

use Core\Session\Session; ?>
<main class="d-flex flex-column align-items-center">
  <h1 class="title title-detail">Mon panier</h1>
  <!-- si j'ai un message d'erreur on l'affiche -->
  <?php if ($form_result && $form_result->hasErrors()) : ?>
    <div class="alert alert-danger" role="alert">
      <?= $form_result->getErrors()[0]->getMessage() ?>
    </div>
    <script>
      setTimeout(function() {
        <?php
        Session::remove(Session::FORM_RESULT);
        ?>
      }, 300);
      setTimeout(function() {
        document.querySelector('.alert-danger').remove();
      }, 3000);
    </script>
  <?php endif ?>
  <!-- si j'ai un message de succes on l'affiche -->
  <?php if ($form_success && $form_success->hasSuccess()) : ?>
    <div class="alert alert-success" role="alert">
      <?= $form_success->getSuccessMessage()->getMessage() ?>
    </div>
    <script>
      setTimeout(function() {
        <?php
        Session::remove(Session::FORM_SUCCESS);
        ?>
      }, 300);
      setTimeout(function() {
        document.querySelector('.alert-success').remove();
      }, 3000);
    </script>
  <?php endif ?>

  <?php if ($count_row <= 0) : ?>
    <div class="alert alert-info">
      Votre panier est vide
    </div>
  <?php else :
    $dateTime = new DateTime($order->date_order);
  ?>
    <div>
      <p class="header-description">Commande : <?= $order->order_number ?></p>
      <p class="header-description">Commande passé le : <?= $dateTime->format("d/m/Y H:i:s") ?></p>
    </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th class="footer-description">Nom de pizza</th>
          <th class="footer-description">Nombre de pizzas</th>
          <th class="footer-description">Modifier quantité</th>
          <th class="footer-description">Prix total</th>
          <th class="footer-description">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($order->order_rows as $row) :
          //on formate l'affichage du nombre de pizza
          $total_pizza = $count_row > 1 ? $count_row . ' pizzas' : $count_row . ' pizza';
        ?>
          <tr class="footer-description">
            <td class="footer-description"><?= $row->pizza->name ?></td>
            <td class="footer-description"><?= $row->quantity ?></td>
            <td class="footer-description">
              <!-- afficher la quantité avec possibilité de modifier avec un formulaire -->
              <form action="/order/update/<?= $row->id ?>" method="POST">
                <input type="hidden" name="order_row_id" value="<?= $row->id ?>">
                <input type="hidden" name="pizza_id" value="<?= $row->pizza_id ?>">
                <input type="hidden" name="size_id" value="<?= $row->size_id ?>">
                <input type="number" name="quantity" value="<?= $row->quantity ?>" min="1" max="10" class="quantity">
                <button class="call-action py-1 px-2" type="submit">
                  <i class="bi bi-check-circle"> </i>Modifier la quantité
                </button>
              </form>
            </td>
            <td class="footer-description"><?= number_format($row->price, 2, ',', '.') ?> €</td>
            <td class="footer-description">
              <form action="/order-row/delete/<?= $row->id ?>" method="POST">
                <input type="hidden" name="order_id" value="<?= $order->id ?>">
                <input type="hidden" name="pizza_id" value="<?= $row->pizza_id ?>">
                <button class="call-action py-1 px-2" type="submit">
                  <i class="bi bi-trash"> </i>Supprimer
                </button>
              </form>
            </td>
          </tr>

        <?php endforeach ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>