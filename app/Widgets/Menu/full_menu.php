<?php

if ($item['parent_id'] == 0) {
    echo "<div class='col-lg-4 col-md-6 col-sm-1'>
            <h5 class='name'>". $item['name']. "</h5>";
} else {
    $item['name'] = mb_strtolower($item['name']);
    echo "<li>
            <a style='font-size:16px' href=". base_url("/category/{$item['slug']}") .">". $item['name'] ."</a>
        </li>";

}

if (isset($item['children'])) {
    echo "<ul class=\"list-unstyled\">". $this->getMenuHtml($item['children']) ."</ul>";

}

if ($item['parent_id'] == 0) {
    echo "</div>";

}

