<?php


$mailchimp = get_field('mailchimp', 'option');
$title     = $mailchimp['title'];
$subtitle  = $mailchimp['subtitle'];
?>
<div class="wcl-newsletter">
	<div class="data-imgs">
		<img src="<?php echo get_stylesheet_directory_uri() . '/img/acf_4_img_1.png'; ?>" alt="img">
		<img src="<?php echo get_stylesheet_directory_uri() . '/img/newsletter_cloud_1.png'; ?>" alt="img">
	</div>

	<div class="data-container wcl-container">
		<div class="data-inner">
			<div class="data-row">
				<div class="data-col">
					<?php if (!empty($title)) : ?>
						<h2 class="data-title">
							<?php echo $title; ?>
						</h2>
					<?php endif; ?>

					<?php if (!empty($subtitle)) : ?>
						<div class="data-subtitle">
							<?php echo $subtitle; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="data-col">
					<form class="data-form">
						<div class="data-form-fields">
							<input type="email" name="email" placeholder="Enter Your Email" required>
							<button type="submit" class="wcl-btn">Subscribe</button>
						</div>
					</form>
				</div>
			</div>

			<div class="data-img">
				<img src="<?php echo get_stylesheet_directory_uri() . '/img/newsletter_img_1.png'; ?>" alt="img">
				<img src="<?php echo get_stylesheet_directory_uri() . '/img/newsletter_img_2.png'; ?>" alt="img">
			</div>
		</div>
	</div>
</div>