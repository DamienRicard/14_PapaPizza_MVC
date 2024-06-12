<?php use App\Controller\OrderController; ?>

<div id="top-bar">
            <div class="line1">
                <div class="box-phone">
                    <i class="bi bi-telephone"></i>
                    <span>04 68 89 65 22</span>
                </div>
                <div class="box-social-icons">
                    <a href="#"> <i class="bi bi-facebook"></i> </a>
                    <a href="#"> <i class="bi bi-instagram"></i> </a>
                    <a href="#"> <i class="bi bi-twitter-x"></i> </a>
                </div>

            </div>
            <div class="line2">
                <div class="nav-logo">
                    <a href="/">
                        <img class="logo-papapizza" src="/assets/images/homepage/papapizza.svg" alt="logo Papapizza">
                    </a>
                </div>
                <div class="nav-list">
                    <nav class="custom-nav">
                        <ul class="custom-ul">
                            <li class="custom-link"><a href="#">Accueil</a></li>
                            <li class="custom-link"><a href="/pizzas">Carte</a></li>
                            <li class="custom-link"><a href="#">Actualités</a></li>
                            <li class="custom-link"><a href="#">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <!-- 3eme bloc : menu du profil -->
                <div class="nav-profil">
                    <nav class="custom-nav-profil">
                        <ul class="custom-ul-profil">
                            <li class="custom-link">
                                <!-- si je suis en session j'affiche mon compte -->
                                <?php if ($auth::isAuth()) : ?>
                                    <div class="dropdown custom-link">
                                        <a class="dropdown-toggle" href="" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                            Mon compte <i class="bi bi-person custom-svg"> </i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <li><a class="dropdown-item custom-link" href="">Profil</a></li>
                                            <li><a class="dropdown-item custom-link" href="/user/createPizza/<?= $user_id ?> ">Créer une pizza</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item custom-link" href="/user/list-custom-pizza/<?= $user_id ?>">Mes pizzas</a></li>
                                            <li><a class="dropdown-item custom-link" href="">Mes commandes</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item custom-link" href="/logout">Déconnexion</a></li>
                                        </ul>
                                    </div>
                                <?php else : ?>
                                    <a href="/connexion">Se connecter
                                        <i class="bi bi-person custom-svg"></i>
                                    </a>
                                <?php endif ?>
                            </li>
                            <li class="custom-link">
                                <?php if ($auth::isAuth()) : ?>
                                    <a href="/order/<?= $user_id ?>" class="position-relative">
                                        <div>
                                            <i class="bi bi-cart custom-svg"></i>
                                            <!-- on vérifie si on a des lignes dans le panier -->
                                            <?php if (OrderController::hasOrderInCart()) : ?>
                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                                            <?php endif ?>
                                        </div>
                                    </a>
                                <?php endif ?>
                            </li>





                            <!-- <li class="custom-link end-link"><a href="#"><i class="bi bi-cart"></i></a></li> -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>