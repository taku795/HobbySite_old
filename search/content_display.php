<?php
if ($sql!=NULL) {
  foreach ($sql->fetchAll() as $row) {
    echo
    "
    <article>
      <form name='form$row[id]' target='_brank' action='../content/content_page.php?content_id=$row[id]' method='post'>
      <a href='javascript:form$row[id].submit()'>
      <div class='content'>
      <p>タイトル：$row[Title]</p>
      <div class='content-body'>
      <p>$row[Content]</p>
      </div>
      </div>
      </a>
      </form>
      </article>
    ";
  }
}

?>