<?php

namespace Core\Database;

//on donne des méthodes que en public
interface DatabaseConfigInterface
{
 public function getHost(): string;
 public function getName(): string;
 public function getUser(): string;
 public function getPass(): string;
}