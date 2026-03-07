<?php

if ($item['parent_id'] == 0) {
    echo "<div class='col-lg-3 col-md-6 col-sm-1' style='text-align:center'>
            <h5>" .
                "<a href=". base_url("/category/{$item['slug']}") .">". 
                $item['name'] ."</a>
            </h5>
        </div>";
}

