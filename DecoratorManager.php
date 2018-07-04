<?php

namespace src\Decorator; // src - это скорее директория, не стоит задавать namespace с таким именем

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

// phpDoc
class DecoratorManager extends DataProvider
{
  public $cache; // должно быть private
  public $logger; // должно быть private

  /**
   * @param string $host
   * @param string $user
   * @param string $password
   * @param CacheItemPoolInterface $cache
   */
  public function __construct($host, $user, $password, CacheItemPoolInterface $cache)
  {
    parent::__construct($host, $user, $password);
    $this->cache = $cache;
  }

  // phpDoc
  public function setLogger(LoggerInterface $logger)
  {
    $this->logger = $logger;
  }

  /**
   * phpDoc
   * название должно совпадать с родительским
   * {@inheritdoc}
   */
  public function getResponse(array $input)
  {
    try {
      $cacheKey = $this->getCacheKey($input);
      $cacheItem = $this->cache->getItem($cacheKey);
      if ($cacheItem->isHit()) {
        return $cacheItem->get();
      }

      $result = parent::get($input);

      $cacheItem
        ->set($result)
        ->expiresAt(
          (new DateTime())->modify('+1 day')
        );

      return $result;
    } catch (Exception $e) {
      $this->logger->critical('Error'); //а если $this->logger не задан? добавить проверку
    }

    return [];
  }

  /**
   * phpDoc
   * видимость private
   * обработать исключение, в случае если массив не конвертабельный
   */
  public function getCacheKey(array $input)
  {
    return json_encode($input);
  }
}
