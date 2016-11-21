<?php
/*
Template Name: View Scan Login
*/
?>

<?php get_header(); ?>
<?php wp_head(); ?>
<style> .input {width:100% !important;} .login-remember {display:none;} </style>
	

	<?php if(have_posts()) : while (have_posts()) : the_post(); ?>
		<article class="boxed">
         <!-- FK EDIT -->
        
        <div style="width:100%;margin:0 auto;">
        
        <h4 style="text-align: center;"><?php _e( 'Please login to view your scan', 'storefront' ); ?></h4>
<div class="splitter"></div><br /><br />

<div id="wpmem_login">
<?php $args = array(
        'echo'           => true,
        'redirect'       => site_url( 'view_scan_page'),
        'form_id'        => 'loginform',
        'label_username' => __( 'Unique User ID:', 'storefront' ),
        'label_password' => __( 'Password:', 'storefront' ),
        'label_remember' => __( 'Remember Me', 'storefront' ),
        'label_log_in'   => __( 'Log In', 'storefront' ),
        'id_username'    => 'user_login',
        'id_password'    => 'user_pass',
        'id_remember'    => 'rememberme',
        'id_submit'      => 'wp-submit',
        'remember'       => true,
        'value_username' => NULL,
        'value_remember' => false
); ?>
<?php wp_login_form( $args ); ?>
</div>
</div>


    		<?php global $more; $more = 0; the_content(); ?>	
		</article>
        
   
        
	<?php endwhile; endif; ?>
	
    <?php wp_link_pages(); ?>


<?php wp_footer(); ?>
<?php get_footer(); ?>