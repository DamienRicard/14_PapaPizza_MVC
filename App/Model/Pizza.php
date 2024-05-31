<?php

namespace App\Model;

use Core\Model\Model;

class Pizza extends Model
{
  public string $name;
  public string $image_path;
  public bool $is_active;
  public int $user_id;  //foreign key

  public User $user;  //pour mettre en relation avec le foreign key

  public array $ingredients=[];
  public array $prices=[];
}