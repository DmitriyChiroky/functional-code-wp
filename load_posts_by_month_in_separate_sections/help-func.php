<?php 


/**
 * posts_landing_page_load_post
 */
function posts_landing_page_load_post() {
    $category  = $_POST['category'];
    $post_date = $_POST['post_date'];
    $has_post  = '';

    ob_start();
    if (empty($post_date)) {
        $post_date   = date("Y-M");
        $count_month = 3;

        while ($count_month > 0) {
            $posts = posts_landing_page_render_month($post_date, $category);
            $post_date = $posts['post_date'];
            $post_date = date("Y-M", strtotime('-1 month', strtotime($post_date)));

            if (!empty($posts)) {
                $count_month--;
                echo $posts['posts'];
            }

            $has_post = posts_landing_page_if_has_post($post_date, $category);
            if (empty($has_post)) {
                break;
            }
        }
    } else {
        $posts = posts_landing_page_render_month($post_date, $category);
        $post_date = $posts['post_date'];
        if (!empty($posts)) {
            echo $posts['posts'];
        }
        $post_date = date("Y-M", strtotime('-1 month', strtotime($post_date)));
        $has_post = posts_landing_page_if_has_post($post_date, $category);
    }
?>
    <?php
    $output['posts'] = ob_get_clean();
    ob_start();
    ?>
    <?php if (!empty($has_post)) : ?>
        <button class="d2-btn" data-post-date="<?php echo $post_date; ?>">
            View More
        </button>
    <?php else : ?>
        <button class="d2-btn" data-post-date="<?php echo $post_date; ?>" disabled="true">
            All Viewed
        </button>
    <?php endif; ?>
<?php
    $output['button'] = ob_get_clean();
    echo json_encode($output);
    wp_die();
}
add_action('wp_ajax_posts_landing_page_load_post', 'posts_landing_page_load_post');
add_action('wp_ajax_nopriv_posts_landing_page_load_post', 'posts_landing_page_load_post');





/**
 * posts_landing_page_get_posts_by_month
 */
function posts_landing_page_get_posts_by_month($last_date, $category = '', $post_type = 'post') {
    $paged      = 1;
    $page_items = -1;
    $offset     = ($paged - 1) * $page_items;

    $args = array(
        'post_type'           => $post_type,
        'posts_per_page'      => $page_items,
        'offset'              => $offset,
        'paged'               => $paged,
        'ignore_sticky_posts' => 1,
        'post_status'         => ['publish'],
    );

    if (!empty($category) && $category != 'all') {
        $args['tax_query'] = [
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        ];
    };

    $date_from = date("Y-M", strtotime('-0 month', strtotime($last_date)));
    $date_to = date("Y-M", strtotime('+1 month', strtotime($last_date)));

    $arr = array(
        array(
            'after' => $date_from,
        ),
    );
    $args['date_query'][] = $arr;

    $arr = array(
        array(
            'before' => $date_to,
        ),
    );
    $args['date_query'][] = $arr;

    $query_obj     = new WP_Query($args);
    $total_count   = $query_obj->found_posts;
    $pages_el      = ceil(($total_count - $offset) / $page_items);
    $data_posts    = [];

    foreach ($query_obj->posts as $item) {
        $date = date("Y-M", (strtotime($item->post_date)));
        $month = date("F", (strtotime($item->post_date)));
        $data_posts[$month][] = $item;
    }

    if (empty($data_posts)) {
        $args = array(
            'post_type'           => $post_type,
            'posts_per_page'      => 1,
            'ignore_sticky_posts' => 1,
            'post_status'         => ['publish'],
        );

        if (!empty($category) && $category != 'all') {
            $args['tax_query'] = [
                array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $category,
                ),
            ];
        };

        $arr = array(
            array(
                'before' => $last_date,
            ),
        );
        $args['date_query'][] = $arr;
        $query_obj   = new WP_Query($args);
        $total_count = $query_obj->found_posts;
        if (!empty($total_count)) {
            $last_date = date("Y-M", strtotime('-1 month', strtotime($last_date)));
            return posts_landing_page_get_posts_by_month($last_date, $category, $post_type);
        }
    } else {
        return ['post_date' => $last_date, 'posts' => $data_posts];
    }
}








/**
 * posts_landing_page_if_has_post
 */
function posts_landing_page_if_has_post($data_to, $category = '', $post_type = 'post') {
    $args = array(
        'post_type'           => $post_type,
        'posts_per_page'      => 1,
        'ignore_sticky_posts' => 1,
        'post_status'         => ['publish'],
    );

    if (!empty($category) && $category != 'all') {
        $args['tax_query'] = [
            array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        ];
    };

    $data_to = date("Y-M", strtotime('+1 month', strtotime($data_to)));

    $arr = array(
        array(
            'before' => $data_to,
        ),
    );
    $args['date_query'][] = $arr;
    $query_obj   = new WP_Query($args);
    $total_count = $query_obj->found_posts;
    return $total_count;
}




/* 
posts_landing_page_render_month
 */
function posts_landing_page_render_month($post_date, $category = '', $post_type = 'post') {
    $data       = posts_landing_page_get_posts_by_month($post_date, $category, $post_type);
    $data_posts = $data['posts'];
    $post_date  = $data['post_date'];
    ob_start();
?>
    <?php if (!empty($data_posts)) : ?>
        <?php foreach ($data_posts as $key => $posts) : ?>
            <?php
            $count_posts = count($posts);
            $dots        = $count_posts / 3;
            $dots        = ceil($dots);
            if ($count_posts > 9) {
                $dots = 3;
            }
            $less_three = '';
            $one_item = '';
            if ($count_posts <= 3) {
                $less_three = 'less-three';
            }
            if ($count_posts == 1) {
                $one_item = 'one-item';
            }
            ?>
            <div class="data-a-item" data-id="<?php echo $post_date; ?>">
                <div class="data-a-month">
                    <?php echo $key; ?>
                </div>

                <div class="data-list swiper <?php echo $less_three . ' ' . $one_item; ?>">
                    <div class="data-list-inner swiper-wrapper">
                        <?php foreach ($posts as $key => $post_item) : ?>
                            <?php
                            $args =  array(
                                'item' => $post_item,
                            );
                            if ($post_type == 'cd-outfit') {
                                get_template_part('template-parts/outfits/item', null, $args);
                            } else {
                                get_template_part('template-parts/posts-landing/item', null, $args);
                            }
                            ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($count_posts > 3) : ?>
                    <div class="data-list-nav">
                        <div class="data-list-nav-btn mod-prev">
                            Previous
                        </div>

                        <?php if ($dots > 0) : ?>
                            <div class="data-list-dots">
                                <?php
                                for ($i = 0; $i < $dots; $i++) {
                                    $active = '';
                                    if ($i == 0) {
                                        $active = 'active';
                                    }

                                    $num   = $count_posts / 3;
                                    $num   = floor($num);
                                    $num_2 = $num * $i;
                                ?>
                                    <div class="data-list-dots-item <?php echo $active ?>" data-index="<?php echo $num_2; ?>"></div>
                                <?php
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <div class="data-list-nav-btn mod-next">
                            Next
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php
    $output['post_date'] = $post_date;
    $output['posts'] = ob_get_clean();
    return $output;
}