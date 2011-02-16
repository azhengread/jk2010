<?php
class DocumentScreen {
  public function render() {
    $connection = new PDO('mysql:host=localhost;dbname=jiakr', 'root', 'a841107!',
     array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $statement = $connection->prepare("select * from tech_document where id=?");
    if (!is_numeric($_GET['id'])) {
      throw new NotFoundException;
    }
    $statement->execute(array($_GET['id']));
    $this->cache = $statement->fetch(PDO::FETCH_ASSOC);
    if ($this->cache === false) {
      throw new NotFoundException;
    }
    $title = "{$this->cache['title']}-甲壳";
    $wrapper = new ScreenWrapper($this, $title, new HtmlMeta);
    $wrapper->render();
  }
  
  public function renderContent() {
    echo '<h1>'.$this->cache['title'].'</h1>';
    echo '<div>';
    echo '<div>'.$this->cache['description'].'</div>';
    if (isset($this->cache['image_url_prefix'])) {
      echo ' <div class="image"><img src="'.$this->cache['image_url_prefix'].'-'.$this->cache['url_name'].'.jpg" /></div>';
    }
    if (isset($this->cache['time'])) {
      echo ' <span class="time">'.$this->cache['time'].'</span>';
    }
    if (isset($this->cache['place'])) {
      echo ' <span class="place">'.$this->cache['place'].'</span>';
    }
    if (isset($this->cache['people'])) {
      echo ' <span class="people">'.$this->cache['people'].'</span>';
    }
    echo ' <span class="source_sina">', $_ENV['source'][$this->cache['source_id']], '</span>';
    echo '<div style="color: #0E774A;">'.$this->cache['source_url'].' <a target="_blank" href="http://'.$this->cache['source_url'].'">浏览</a></div>';
    echo '</div>';
    $tmp = substr($this->cache['related_cache'], 1, strlen($this->cache['related_cache']) - 2);
    $items = explode('";"', $tmp);
    foreach ($items as $row) {
      echo '<span class="related" style="display:block">';
      $columns = explode('","', $row);
      echo '<a href="'.$columns[0].'-'.$columns[3].'.html">'.$columns[1].'</a>';
      echo '</span>';
      if (!empty($columns[5])) {
        echo '<img src="'.$columns[5].'-'.$columns[3].'.jpg" title="图片：'.$columns[4].'" alt="图片：'.$columns[4].'" />';
      } else {
        echo $columns[4];
      }
    }
    echo "<div>返回《{$this->cache['title']}》所在的存档</div>";
  }
}