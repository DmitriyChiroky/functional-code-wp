<?php


$paged      = 1;
$page_items = 2;
$offset     = ($paged - 1) * $page_items;

$args = array(
	'post_type'      => 'post',
	'posts_per_page' => $page_items,
);

$query_obj   = new WP_Query($args);
$total_count = $query_obj->found_posts;
$pages_el    = ceil(($total_count - $offset) / $page_items);
?>
<?php if ($query_obj->have_posts()) : ?>
	<div class="wcl-section-1">
		<div class="data-list">
			<?php while ($query_obj->have_posts()) : $query_obj->the_post(); ?>
				<?php
				echo get_the_ID();
				echo '<br>';
				?>
			<?php endwhile;
			wp_reset_postdata(); ?>
		</div>

		<div class="data-b2-load-more">
			<div class="b2-container">
				<?php if ($pages_el > 1) : ?>
					<button class="b2-btn" data-page="<?php echo $paged; ?>">
						View More
					</button>
				<?php else : ?>
					<button class="b2-btn" data-page="<?php echo $paged; ?>" disabled="true">
						All Viewed
					</button>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
