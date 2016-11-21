<?php
/*
Template Name: Home
*/

	get_header('fullwidth');

	include(dirname(__FILE__) . '/partials/_slider.php');

?>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php
                while ( have_posts() ) : the_post();
                    the_content();
                endwhile;

                include(dirname(__FILE__) . '/partials/_portfolio.php');
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>