<?php

if ($item['parent_id'] == 0 && !isset($item['children'])) {
    echo "<div class=\"col-lg-4 col-md-6 col-sm-1\">
            <h6>
                <a href=". base_url("/category/{$item['slug']}") .">". $item['name'] ."</a>
            </6>
        </div>";
}


