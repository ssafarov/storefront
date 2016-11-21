<script src="<?=get_template_directory_uri()?>/js/orbit.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(window).load(function() {
			// The slider needs a little trickery
			// as there is no orbit event for the first
			// slide...
			$('#featured').watch('innerHTML', function() {
				$(this).find('.slide:not(.fluid-placeholder):first .slide-content').addClass('active');

				$('#featured').unwatch('innerHTML');
			});

			$('#featured').orbit({
				animation: 'fade',                    // fade, horizontal-slide, vertical-slide, horizontal-push
				// animationSpeed: 1500,                 // how fast animtions are
				timer: true,                           // true or false to have the timer
				advanceSpeed: 5000,                    // if timer is enabled, time between transitions
				pauseOnHover: true,                    // if you hover pauses the slider
				startClockOnMouseOut: true,            // if clock should start on MouseOut
				startClockOnMouseOutAfter: 500,        // how long after MouseOut should the timer start again
				directionalNav: true,                 // manual advancing directional navs
				bullets: true,                         // true or false to activate the bullet navigation
				bulletThumbs: false,                   // thumbnails for the bullets
				bulletThumbLocation: '',               // location from this file where thumbs will be
				captions: true,                        // do you want captions
				afterSlideChange: function(previousSlide, currentSlide) {
					previousSlide.find('.slide-content').removeClass('active');
					currentSlide.find('.slide-content').addClass('active');
				}
			});
		});
	});
</script>

<?php if ( ! in_array( $_SERVER['PHP_SELF'], array( '/wp-login.php', '/wp-activate.php', '/wp-signup.php' ) ) ) { ?>
<div id="featured">
	<?php
		$slider = new WP_Query(array('order' => 'DESC', 'post_type' => 'slider', 'posts_per_page' => -1));
		while ($slider->have_posts()) : $slider->the_post();

		$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
	?>
	   <div class="slide" style="background-image: url(<?=$featured_image[0]?>);">
			<div class="boxed container">
				<div class="slide-content-wrapper row">
					<div class="slide-content col-xs-12">
						<h3><?=get_the_title($post->ID)?></h3>
						<br>
						<article>
							<?=excerpt(200)?>
						</article>
					</div>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
</div>
<?php } ?>