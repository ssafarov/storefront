<?php
/*
Template Name: Register
*/
?>

<?php get_header(); ?>
<style>
	input[type=text] { clear:both; float:left; display:block; height:20px; }
	select {width:100% !important;}
	label{ display:block; width:200px; height:20px;clear:both; float:left;}
	#wpmem_login {display:none;}
	td {height: 13px !important; padding: 3px !important;}
	.lhie {line-height:8px !important;}
	.tdhr {height:4px !important;}
	
	INPUT.wpcf7-form-control.wpcf7-submit.backgb {background-color:#00B0F0 !important; color: #fff !important;}
	
</style>
<main class="site-main">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article class="boxed">
        
    		<?php global $more; $more = 0; the_content(); ?>	
		</article>
	<?php endwhile; endif; ?>
	
    <?php wp_link_pages(); ?>
</main>
<?php //wp_footer(); ?> 
<?php get_footer(); ?>