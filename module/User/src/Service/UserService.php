<?php

namespace User\Service;

class UserService
{
  public function add(User $user);

  public function getAuthenticationService();
  /**
   * @return bool
   */
  public function login($email, $password);
}
