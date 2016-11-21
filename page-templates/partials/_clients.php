<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<section id="clients">
				<article class="boxed">
					<div class="carousel nolist">
						<?php
							$client = new WP_Query(array('order' => 'DESC', 'post_type' => 'clients', 'posts_per_page' => -1));
							if($client->have_posts()) : while($client->have_posts()) : $client->the_post();
								$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
						?>

						<div clas="item">
							<?php if( $featured_image[0]) { ?>
								<a href="<?php the_title(); ?>" target="_blank"><img src="<?php echo $featured_image[0]; ?>" alt="<?php the_title(); ?>" /></a>
						   <?php } ?>
						</div>

						<?php endwhile; endif; ?>
					</div>
				</article>
			</section>
		</div>
	</div>
</div>