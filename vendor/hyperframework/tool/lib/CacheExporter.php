<?php
class CacheExporter {
  private $folder;

  public function export($result) {
    if ($result === null) {
      return;
    }
    list($name, $cache) = $result->export();
    file_put_contents(
      $this->getPath($name),
      '<?php'.PHP_EOL.'return '.var_export($cache, true).';'
    );
  }

  private function getPath($name) {
    if ($this->folder === null) {
        $this->folder = $_SERVER['PWD'].DIRECTORY_SEPARATOR.'cache';
        $this->createFolder();
    }
    return $this->folder.DIRECTORY_SEPARATOR.$name.'.cache.php';
  }

  private function createFolder() {
    if (!file_exists($this->folder)) {
      mkdir($this->folder);
      chmod($this->folder, 0777);
    }
  }
}