<?php
class UserScreen extends Screen {
  protected function renderHtmlHeadContent() {
    echo '<title>优选集 - 攻略聚集地</title>';
  }

  protected function renderHtmlBodyContent() {
    $user = $GLOBALS['USER'];
    echo '<h2>', $GLOBALS['USER']['name'], '</h2>';
    echo '<p><a href="flag">举报</a></p>';
    echo '<p>声望 [',$user['reputation'],']</p>';
    echo '<p>签名 [',$user['description'],']</p>';
    echo '<p>位置 [',$user['description'],']</p>';
    echo '<p>个人简介 [',$user['description'],']</p>';
    echo '<p>注册时间 [',$user['sign_up_time'],']</p>';
    echo '<p>最后登录时间 [',$user['sign_in_time'],']</p>';
    echo '<p>绑定账户 [',$user['account_binding_list'],']</p>';
    echo '<p>个人主页被浏览 [',$user['page_view'],'] 次</p>';
    echo '<p>攻略分类</p>';
    $categoryList = Db::getAll('SELECT * FROM user_article_category WHERE user_id = ?', $_SESSION['user_id']);
    DbConnection::connect('youxuanji');
    foreach ($categoryList as $categoryMeta) {
      $category = Db::getRow('SELECT * FROM category WHERE id = ?', $categoryMeta['category_id']);
      echo '[<a href="category-', $category['id'], '/">', $category['name'], '</a>] x ', $categoryMeta['article_amount'];
    }
    DbConnection::close();
    echo '<div class="big_number">';
    echo '<hr />';
    echo '<p>参与 { <a href="participation/">', $user['article_participation_amount'], '</a> } 篇攻略</p>';//获得 xx 声望
    echo '<p>喜欢 { <a href="like/">', $user['article_like_amount'], '</a> } 篇攻略</p>';
    echo '<p>关注 { <a href="watch/">', $user['article_watch_amount'], '</a> } 篇攻略</p>';
    echo '<hr />';
    echo '<p>讨论</p>';
    echo '<p>主题：{ <a href="topic/">', $user['topic_amount'], '</a> }</p>';
    echo '<p>回复：{ <a href="post/">', $user['post_amount'], '</a> }</p>';
    echo '<p>关注：{ <a href="topic_watch/">', $user['topic_watch_amount'], '</a> }</p>';
    echo '<hr />';
    echo '</div>';
    $articleList = Db::getAll('SELECT * FROM article WHERE user_id = ?', $_SESSION['user_id']);
    foreach ($articleList as $article) {
      echo '<p>[#] <a href="/article-', $article['id'], '/">', $article['title'], '</a> - ', $article['abstract'], '</p> ';
    }
  }
}