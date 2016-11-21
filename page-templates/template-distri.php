<?php
/*
Template Name: Distributors
*/
?>

<?php get_header(); ?>
<style> .input {width:100% !important;} .login-remember {display:none;} .mediabo{display:none !important;} </style>
	

	<?php if(have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="boxed">
        <?php get_sidebar(); ?>
        
    	<article class="post-2358 post type-post status-publish format-standard hentry category-uncategorized box-alt" id="post-2358" style="padding-top: 25px;">
    		<?php global $more; $more = 0; the_content(); ?>	
		</article>
    	
    	
    </div>
        
   
        
	<?php endwhile; endif; ?>
	
    <?php wp_link_pages(); ?>


<?php get_footer(); ?>