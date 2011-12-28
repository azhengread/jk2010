<?php
class RelatedQueryScreen {
  public static function render() {
    $result = QuerySearch::searchByQuery($GLOBALS['URI']['QUERY']);
    if ($result['total_found'] === 0) {
      return;
    }
    $buffer = '';
    foreach ($result['matches'] as $id => $item) {
      $query = DbQuery::get($id);
      if ($GLOBALS['URI']['QUERY'] === $query['name']) {
       continue;
      }
      $buffer .= '<li><a href="/'.$query['name'].'/">'
        .$query['name'].'</a> '.$item['attrs']['amount'].'</li>';
    }
    if ($buffer === '') {
      return;
    }
    echo '<h2>相关搜索:</h2><ul>', $buffer, '</ul>';
  }
}