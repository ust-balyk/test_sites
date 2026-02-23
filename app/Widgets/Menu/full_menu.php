<?php

if ($item['parent_id'] == 0) {
    echo "<div class=\"col-lg-4 col-md-6 col-sm-1\">
            <h5 class=\"title\">". $item['category']. "</h5>";
} else {
    $item['title'] = mb_strtolower($item['title']);
    echo "<li>
            <a style='font-size:16px' href=". base_url("/category/{$item['slug']}") .">". $item['title'] ."</a>
        </li>";

}

if (isset($item['children'])) {
    echo "<ul class=\"list-unstyled\">". $this->getMenuHtml($item['children']) ."</ul>";

}

if ($item['parent_id'] == 0) {
    echo "</div>";

}

