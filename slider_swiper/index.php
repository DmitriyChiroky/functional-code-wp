<?php

?>
<div class="wcl-section-2">
    <div class="data-container">
        <div class="data-list-out">
            <div class="data-list swiper">
                <div class="data-list-inner swiper-wrapper">
                    <?php if ($query_obj->have_posts()) : ?>
                        <?php while ($query_obj->have_posts()) : $query_obj->the_post(); ?>
                            <?php get_template_part('template-parts/section-2/item', null, $args); ?>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    <?php else : ?>
                        <div class="data-list-empty">
                            No content
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="data-list-nav">
                <div class="data-list-nav-btn mod-prev">
                    <img src="<?php echo get_stylesheet_directory_uri() . '/img/sc-2-arrow.svg'; ?>" alt="img">
                </div>

                <div class="data-list-nav-btn mod-next">
                    <img src="<?php echo get_stylesheet_directory_uri() . '/img/sc-2-arrow.svg'; ?>" alt="img">
                </div>
            </div>
        </div>
    </div>
</div>