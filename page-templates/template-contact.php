<?php
/*
Template Name: Contact
*/
?>

<?php get_header(); ?>

	<?php if(have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="boxed">	
			<div class="two_third">
    			
                <?php echo do_shortcode( '[contact-form-7 id="961" title="Contact form 1"]' ); ?>
                
                
    			<!-- contact form -->
				<form style="display:none;" id="contact_form" name="contact_form" method="post"
				      action="<?php echo get_template_directory_uri(); ?>/inc/contact_form.php" enctype="multipart/form-data">
					<input name="recipient" value="<?php echo get_option('builder_contact_email'); ?>" type="hidden" />		
					<input name="url" value="<?php echo home_url(); ?>" type="hidden" />	
					
					<label for="name"><?php _e('Your name', 'storefront'); ?>&nbsp;<span>*</span></label>
					<input type="text" id="name" name="name" value="" />
					
					<label for="email"><?php _e('Your email', 'storefront'); ?>&nbsp;<span>*</span></label>
					<input type="text" id="email" name="email" value="" />
					
					<label for="message"><?php _e('Your Message', 'storefront'); ?>&nbsp;<span>*</span></label>
					<textarea id="message" name="message"></textarea>
				
					<input class="button flat" type="submit" id="send_message" name="send_message" value="Submit" />
				</form>
			</div>
			
			<div class="one_third last">	
				<?php global $more; $more = 0; the_content(); ?>	
			</div>
			
		</div>

	<?php endwhile; endif; ?>

<?php get_footer(); ?>
