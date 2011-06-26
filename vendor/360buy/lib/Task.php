<?php
class Task extends Db {
  private $current;

  public function get() {
    return $this->current;
  }

  public function moveToNext() {
    $sql = "select * from task order by id desc limit 1";
    $result = $this->getRow($sql);
    if ($result === false) {
      $this->current = null;
      return false;
    }
    $result['arguments'] = eval('return '.$result['arguments'].';');
    $this->current = $result;
    return true;
  }

  public function add($type, $arguments = array()) {
    $sql = "insert into task(type, arguments)"
      ." values('$type', ?)";
    $this->executeNonQuery($sql, array(var_export($arguments, true)));
  }

  public function remove($id) {
    $sql = "delete from task where id=$id";
    $this->executeNonQuery($sql);
  }

  public function isEmpty() {
    $sql = "select count(*) from task";
    $result = $this->getRow($sql);
    return $result['count(*)'] === '0';
  }
}