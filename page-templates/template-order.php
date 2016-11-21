<?php
/*
Template Name: Order
*/
?>

<script>
$(document).ready(function(){

	$('input:radio').screwDefaultButtons({
		checked: 	"url(<?php echo site_url(); ?>/t3.png)",
		unchecked:	"url(<?php echo site_url(); ?>/t2.png)",
		width:		32,
		height:		37
	});

});

</script>

<?php get_header(); ?>

	<?php if(have_posts()) : while (have_posts()) : the_post(); ?>
		<article class="boxed">

        <style>
		    input[type="radio"] {
    display:none;

}

label {
    display:block;
    padding-left:30px;
    font-family:Arial;
    font-size:16px;
    background: url('<?php echo site_url(); ?>/t2.png') left center no-repeat;
	height:50px;
	cursor:pointer;
}

input[type="radio"]:checked + label {
    background: url('<?php echo site_url(); ?>/t3.png') left center no-repeat; cursor:pointer;
}


		</style>
    		<?php global $more; $more = 0; the_content(); ?>
		</article>
	<?php endwhile; endif; ?>

    <?php wp_link_pages(); ?>
<?php wp_footer(); ?>
<?php get_footer(); ?>