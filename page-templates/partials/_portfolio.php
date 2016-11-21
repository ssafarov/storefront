<section id="portfolio">
	<h4><?php echo stripslashes(get_option('builder_portfolio_title')); ?></h4>
	<?php if(get_option('builder_portfolio_link')) : ?>
		<a style="margin-top:-60px;margin-right:32px;" class="button alignright" href="<?php echo stripslashes(get_option('builder_portfolio_link')); ?>"><?php echo stripslashes(get_option('builder_portfolio_button')); ?></a>
	<?php endif; ?>

	<div class="row">
		<?php
			 $portfolio = new WP_Query(array('order' => 'DESC', 'post_type' => 'portfolio', 'posts_per_page' => 6));
			 if($portfolio->have_posts()) : while($portfolio->have_posts()) : $portfolio->the_post();
				 $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');

				 $terms = get_the_terms( $post->ID, 'filter' );

					if ( $terms && ! is_wp_error( $terms ) ) :
						$links = array();

						foreach ( $terms as $term )
						{
							$links[] = $term->name;
						}
						$links = str_replace(' ', '-', $links);
						$tax = join( " / ", $links );
					else :
						$tax = '';
					endif;
		 ?>

			<div class="col-xs-12 col-sm-6 col-lg-4 align-center">
				<a href="<?php the_permalink(); ?>">
					<div class="hover"><div class="iconhover"></div></div>
					<img src="<?php echo $featured_image[0]; ?>" alt="" />
					<h5 class="nbm"><?php the_title(); ?></h5>
				</a>
			</div>

		<?php endwhile; endif; ?>
	</div>
</section>