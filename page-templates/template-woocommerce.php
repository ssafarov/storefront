<?php
/*
Template Name: WooCommerce
*/
?>



<?php get_header(); ?>
<?php wp_head(); ?>

	

	<?php if(have_posts()) : while (have_posts()) : the_post(); ?>
		<article class="boxed">
    		<?php global $more; $more = 0; the_content(); ?>	
		</article>
	<?php endwhile; endif; ?>
	
    <?php wp_link_pages(); ?>


<?php wp_footer(); ?>
<?php get_footer(); ?>