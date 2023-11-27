<?php 

$thumbnail = get_the_post_thumbnail($post_id, 'image-size-2');
?>
<div class="wcl-b2-img-loader data-img-loader">
    <div class="b2-inner">
        <div class="b2-img">
            <?php if (!empty($thumbnail)) : ?>
                <?php echo $thumbnail; ?>
            <?php endif; ?>
        </div>

        <label for="picture">
            <input type="file" id="picture" name="picture" accept="image/*" />

            <img src="<?php echo get_stylesheet_directory_uri() . '/img/picture-icon.svg'; ?>" alt="img">

            <div class="b2-text mod-label">
                Додати фото
            </div>
        </label>
    </div>

    <?php if (!empty($thumbnail)) : ?>
        <?php
        $delete_class = 'active'
        ?>
    <?php endif; ?>

    <div class="b2-delete <?php echo $delete_class; ?>">
        <img src="<?php echo get_stylesheet_directory_uri() . '/img/delete.svg'; ?>" alt="img">

        <span>Видалити фото</span>
    </div>
</div>