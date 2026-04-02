<?php

if ($item['parent_id'] == 0) {
    echo "<div class='col-lg-3 col-md-6 col-sm-1'>
<h5 class='name'>". $item['name']. "</h5>". PHP_EOL;
} else {
    $item['name'] = mb_strtolower($item['name']);
    echo "  <li>
    <a class='children' href=". base_url("/category/{$item['slug']}") .">". hsc($item['name']) ."</a>
  </li>". PHP_EOL;
}
if (isset($item['children'])) {
    echo  "<ul class=\"list-unstyled\">". 
        PHP_EOL .$this->get_menu_html($item['children']) ."</ul>". PHP_EOL;
}
if ($item['parent_id'] == 0) {
    echo 
    "</div>". PHP_EOL; //</div class='col-lg-3 col-md-6 col-sm-1'>
}

