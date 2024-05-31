<?php

namespace App;

use App\Repository\UserRepository;
use Core\Repository\RepositoryManagerTrait;

class AppRepoManager
{
  //on récupère le trait RepositoryManagerTrait
  use RepositoryManagerTrait;

  //on déclare une propriété privée qui va contenir une instance du repository
  private UserRepository $userRepository;

  //on crée le  getter
  public function getUserRepository(): UserRepository
  {
    return $this->userRepository;
  }

  //on déclare un construct qui va instancier les repositories
  protected function __construct()
  {
    $config = App::getApp();
    //on instancie le repository
    $this->userRepository = new UserRepository($config);
  }
}