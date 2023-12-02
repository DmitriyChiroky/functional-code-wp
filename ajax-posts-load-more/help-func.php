<?php 

/**
 * load_post
 */
function load_post() {
    $page_items = 2;
    $paged      = $_POST['paged'] ? $_POST['paged'] : 1;
    $offset     = ($paged - 1) * $page_items;

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $page_items,
        'paged'          => $paged,
        'offset'         => $offset,
    );

    $query_obj   = new WP_Query($args);
    $total_count = $query_obj->found_posts;
	$pages_el    = ceil(($total_count - $offset) / $page_items);
    ob_start();
?>
    <?php if ($query_obj->have_posts()) : ?>
        <?php while ($query_obj->have_posts()) : $query_obj->the_post(); ?>
            <?php
            echo get_the_ID();
            echo '<br>';
            ?>
        <?php endwhile;
        wp_reset_postdata(); ?>
    <?php else : ?>
        <div class="data-list-empty">
            No found
        </div>
    <?php endif; ?>
    <?php
    $output['posts'] = ob_get_clean();
    ob_start();
    ?>
    <?php if ($pages_el > 1) : ?>
        <button class="b2-btn" data-page="<?php echo $paged; ?>">
            View More
        </button>
    <?php else : ?>
        <button class="b2-btn" data-page="<?php echo $paged; ?>" disabled="true">
            All Viewed
        </button>
    <?php endif; ?>
<?php
    $output['button'] = ob_get_clean();
    echo json_encode($output);
    wp_die();
}
add_action('wp_ajax_load_post', 'load_post');
add_action('wp_ajax_nopriv_load_post', 'load_post');
