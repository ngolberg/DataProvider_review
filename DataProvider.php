<?php

namespace src\Integration; // src - это скорее директория, не стоит задавать namespace с таким именем

class DataProvider
{
  private $host;
  private $user;
  private $password;

  /**
   * @param $host
   * @param $user
   * @param $password
   */
  public function __construct($host, $user, $password)
  {
    $this->host = $host;
    $this->user = $user;
    $this->password = $password;
  }

  /**
   * @param array $request
   *
   * @return array
   */
  public function get(array $request)
  {
    // returns a response from external service
  }
}
