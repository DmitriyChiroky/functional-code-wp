<?php

$group_1 = get_field('group_1');
$gallery = $group_1['gallery'];

$posts_obj = get_posts(array(
    'post_type'      => 'wcl-service',
    'posts_per_page' => 3,
    'post__not_in'   => [get_the_ID()],
    'post_status'    => 'publish',
));
?>
<div class="wcl-acf-block-2">
    <div class="data-container wcl-container">
        <div class="data-row">
            <div class="data-col">
                <?php if (!empty($gallery)) : ?>
                    <?php
                    $counter = 0;
                    $images = [];

                    foreach ((array)$gallery as $img_id) {
                        $images[] = wp_get_attachment_image_url($img_id, 'image-size-10');
                    }

                    $gallery_class = '';
                    if (count((array)$gallery) > 1) {
                        $gallery_class = 'mod-more-one';
                    } else {
                        $gallery_class = 'mod-one-item';
                    }
                    ?>
                    <div class="data-gallery-out <?php echo $gallery_class; ?>">
                        <div class="data-gallery" data-images="<?php echo esc_attr(json_encode($images)); ?>" data-index="1">
                            <?php foreach ((array)$gallery as $img_id) : ?>
                                <?php
                                $counter++;
                                $active = '';
                                $image = wp_get_attachment_image_url($img_id, 'image-size-10');
                                ?>
                                <?php if (!empty($image)) : ?>
                                    <?php if ($counter == 1) : ?>
                                        <img src="<?php echo $image; ?>" class="active wcl-img-clear-pin" alt="img">
                                    <?php elseif ($counter == 2) : ?>
                                        <img src="<?php echo $image; ?>" class="next wcl-img-clear-pin" alt="img">
                                    <?php else : ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>