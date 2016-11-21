<?php
/*
Template Name: Help center
*/

	get_header();

?>

<div class="row" style="margin-top:80px;">
	<div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
		<?php
			while ( have_posts() ) : the_post();
				the_content();
			endwhile;
		?>
	</div>
</div>

<?php get_footer(); ?>