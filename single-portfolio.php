<?php
    set_post_views(get_the_ID());
    get_header();
?>

<main id="main" class="container site-main" role="main">
<?php
    if ( have_posts() ) : while ( have_posts() ) : the_post();
        $featured_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
?>
        
        <h1><?php the_title(); ?></h1>
        <!-- <a style="font-weight: bold; color: #90C73E;" href="#top">Download .OBJ .PLY & .STL files</a> -->
		<article <?php post_class('single_portfolio'); ?> id="post-<?php the_ID(); ?>">
      
    		<div class="boxed">
        		<?php the_content(); ?>
    		</div>
		</article>  
    
	<?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>