<div class="admin-container">
  <h1> <?= $h1 ?> </h1>
  <?php include(PATH_ROOT . 'views/_templates/_message.html.php'); ?>

  <a class="call-action" href="/user/createPizza/<?= $user_id ?>"> Je crée ma pizza</a>

</div>
<?php if (empty($pizzas)) : ?>
  <div class="d-flex justify-content-center">
    <div class="d-flex flex-row flex-wrap my-3 justify-content-center col-lg-10">
      <div class="alert alert-info" role="alert">
        Vous n'avez pas encore crée de pizza
      </div>
    </div>
  </div>
<?php else : ?>


  <div class="d-flex justify-content-center">
    <div class="d-flex flex-row flex-wrap my-3 justify-content-center col-lg-10">
      <?php foreach ($pizzas as $pizza) : ?>
        <div class="card m-2" style="width: 18rem;">
          <a href="/pizza/<?= $pizza->id ?>">
            <img src="/assets/images/pizza/<?= $pizza->image_path ?>" alt="<?= $pizza->name ?> " class="card-img-top imgfluid img-pizza">
          </a>
          <div class="card-body">
            <h3 class="card-title sub-title text-center"> <?= $pizza->name ?> </h3>
            <div class="d-flex justify-content-center">
              <a href="/user/pizza/delete/<?= $pizza->id ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette pizza ?')" class="btn btn-danger"><i class="bi bi-trash"></i></a>
            </div>
          </div>
        </div>
      <?php endforeach ?>
    </div>

  </div>
<?php endif; ?>