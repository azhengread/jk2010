<?php
class ClassRecognizer {
  private $cache;

  public function __construct($cache) {
    $this->cache = $cache;
  }

  public function execute($fileName, $relativeFolder, $rootFolder) {
    $class = $this->getClass($fileName);
    if ($class !== null) {
      $this->cache->append($class, $relativeFolder, $rootFolder);
    }
  }

  private function getClass($fileName) {
    $pattern = '/^([A-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*).php$/';
    if (preg_match($pattern, $fileName)) {
      return preg_replace('/.php$/', '', $fileName);
    }
  }
}