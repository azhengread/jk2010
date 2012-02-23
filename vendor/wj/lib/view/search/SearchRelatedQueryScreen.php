<?php
class SearchRelatedQueryScreen {
  public static function render() {
    if (!isset($GLOBALS['QUERY']['related_list'])) {
      return;
    }
    $hasLongText = false;
    $relatedList = explode(',', $GLOBALS['QUERY']['related_list']);
    foreach ($relatedList as $query) {
      if (mb_strlen($query, 'UTF-8') > 30) {//TODO:定伐值 30?
        $hasLongText = true;
        break;
      }
    }
    $maxColumn = 4;
    $maxRow = 4;
    if ($hasLongText) {
      $maxColumn = 2;
      $maxRow = 8;
    }
    $amount = count($relatedList);
    $index = 0;
    echo '<div id="related"><h2>相关搜索:</h2><table>';
    for ($row = 0; $row < $maxRow; ++$row) { //TODO:use foreach
      echo '<tr>';
      for ($column = 0; $column < $maxRow; ++$column, ++$index) {
        if ($index < $amount) {
          $query = $relatedList[$index];
          echo '<td><a href="/', urlencode($query), '/">', $query, '</a></td>';
          continue;
        }
        if ($row !== 0) {
          $colspan = 5 - $column;
          $colspanAttribute = $colspan === 1 ? '' : ' colspan="'.$colspan.'"';
          echo '<td', $colspanAttribute, '></td>';
        }
        break;
      }
      echo '</tr>';
      if ($index >= $amount) {
        break;
      }
    }
    echo '</table></div>';
  }
}