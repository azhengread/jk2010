<?php
class OptionArgumentParser {
  private $reader;
  private $isAfterCommand;

  public function __construct($reader, $isAfterCommand) {
    $this->reader = $reader;
    $this->isAfterCommand = $isAfterCommand;
  }

  private function parse($maximumLength) {
    $arguments = array();
    while (($item = $this->reader->read()) !== null) {
      if (strpos($item, '-') === 0 && $item !== '-') {
        $this->reader->move(-1);
        break;
      }
      $arguments[] = $item;
      $this->reader->move();
    }
    $amount = count($arguments);
    if ($amount > $maximumLength && $maximumLength !== null) {
      return $this->cutArguments($arguments, $amount, $maximumLength);
    }
    return $arguments;
  }

  private function cutArguments($arguments, $amount, $maximumLength) {
    if ($amount === $maximumLength + 1 && !$this->isAfterCommand) {
      array_pop($arguments);
      $this->reader->move(-1);
      return $arguments;
    }
    if ($this->reader->read() === null) {
      $arguments = array_slice($arguments, 0, $maximumLength);
      $this->reader->move($maximumLength - $amount);
    }
    return $arguments;
  }
}