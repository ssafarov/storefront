<?php
/*
Template Name: System Registration Register
*/
?>

<?php get_header(); ?>
<?php wp_head(); ?>
<style> .input {width:100% !important;} .login-remember {display:none;} </style>
	

	<?php if(have_posts()) : while (have_posts()) : the_post(); ?>
		<article class="boxed">
        
        
        
        <!-- FK EDIT -->
        <div style="width:100%;margin:0 auto;">

   <h4 style="text-align: center;"><?php _e( 'Register Device / Download your software', 'storefront' ); ?></h4>
<div class="splitter"></div><br /><br />









</div>


    		<?php global $more; $more = 0; the_content(); ?>	
		</article>
        
        
        
            
            
            
            
            
        
        
        
        
        
	<?php endwhile; endif; ?>
	
    <?php wp_link_pages(); ?>


<?php wp_footer(); ?>
<?php get_footer(); ?>



 
 
 
 


