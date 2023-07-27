<?php

if ($this->query->have_posts()) {
    while ($this->query->have_posts()) {
        $this->query->the_post();
        the_title();
        post_class();
        echo '<br>';
    }
}
