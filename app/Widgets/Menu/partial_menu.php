<?php

if ($item['parent_id'] == 0) {
  echo
  "<div class='col-lg-3 col-md-6 partial_menu'>
  <a href=". base_url('/'. TABLE_NAME .'/'. $item['slug']) ."><h5>". $item['category'] ."</h5></a>
</div>". PHP_EOL;
}

