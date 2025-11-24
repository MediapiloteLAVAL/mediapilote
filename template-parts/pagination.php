<?php 

    // Pagination
    $pagination = paginate_links(array(
        'total' => $query->max_num_pages,
        'current' => $paged,
        'prev_text' => '<span class="icon icon-prev icon-40"></span>',
        'next_text' => '<span class="icon icon-next icon-40"></span>',
    ));
    if ($pagination) {
        echo '<div class="container d-flex x-y-center flex-column mtb-70"><div class="pagination">' . $pagination . '</div></div>';
    }