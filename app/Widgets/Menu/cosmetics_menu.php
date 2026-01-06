<?php

if ($item['parent_id'] == 0) {
    echo "<div class=\"col-lg-4 col-md-6 col-sm-1\">
            <h6 class=\"title\">". $item['name']. "</h6>";

} else {
    echo "<li>
            <a href=". base_url("/category/{$item['slug']}") .">". $item['name'] ."</a>
        </li>";

}
if (isset($item['children'])) {
    echo "<ul class=\"list-unstyled\">". $this->getMenuHtml($item['children']) ."</ul>";

}
if ($item['parent_id'] == 0) {
    echo "</div>"; //<!-- col-lg-4 col-md-6 col-sm-1 -->

}

