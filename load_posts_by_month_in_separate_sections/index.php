<?php

/**
 * Template Name: Posts Landing Page
 */

get_header();

$terms = get_terms([
    'taxonomy'   => 'category',
    'hide_empty' => true,
    'parent'     => 0,
    'exclude'    => 1,
]);

$term = get_queried_object();

$term_all       = new stdClass();
$term_all->slug = 'all';
$term_all->name = 'All';
$term_active    = $term_all;

array_unshift($terms, $term_all);

if (is_category()) {
    $term_active = get_queried_object();
}

$post_date   = date("Y-M");
$has_post    = '';
$count_month = 3;
?>
<div class="wcl-posts-landing">
    <div class="data-a">
        <div class="data-a-container wcl-container">
            <?php
            while ($count_month > 0) {
                $posts     = posts_landing_page_render_month($post_date, $term_active->slug);
                $post_date = $posts['post_date'];
                $post_date = date("Y-M", strtotime('-1 month', strtotime($post_date)));

                if (!empty($posts)) {
                    $count_month--;
                    echo $posts['posts'];
                }

                $has_post = posts_landing_page_if_has_post($post_date, $term_active->slug);
                if (empty($has_post)) {
                    break;
                }
            }
            ?>
        </div>
    </div>

    <div class="wcl-load-more">
        <div class="d2-container">
            <?php if (!empty($has_post)) : ?>
                <button class="d2-btn" data-post-date="<?php echo $post_date; ?>">
                    View More
                </button>
            <?php else : ?>
                <button class="d2-btn" data-post-date="<?php echo $post_date; ?>" disabled="true">
                    All Viewed
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
get_footer();
