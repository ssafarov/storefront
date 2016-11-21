<?php
/**
 * The template for displaying the homepage.
 *
 * Template name: Homepage Distributor
 *
 * @package storefront
 */

// Get scanner WC_Product Class
$scanner = get_scanner_product();
$currency = get_woocommerce_currency_symbol();

get_header(); ?>

<main id="main" role="main">
    <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
    <section class="hero" style="background-image: url('<?php echo $image[0]; ?>');">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <img width="60%" src="<?php echo get_template_directory_uri(); ?>/images/scanify-logo.png" alt="Scanify">

                    <h2><?php _e('A world of scanning at your fingertips', 'storefront'); ?></h2>

                    <p>
                        <a class="btn btn-primary" href="/distributor-portal/?apply"><?php _e('Apply here to be a sales partner', 'storefront'); ?></a>&nbsp;&nbsp;
                        <a class="btn btn-transparent btn-outline" href="/distributor-portal/"><?php _e('Existing partners click here', 'storefront'); ?></a>&nbsp;&nbsp;
                    </p>
                </div>
            </div>
        </div>

        <video id="home_player" class="hero__video">
            <!-- <source src="<?php echo get_template_directory_uri(); ?>/foo.ogg" type="video/ogg"> -->
            <source src="<?php echo get_template_directory_uri(); ?>/assets/fuel-video.mp4" type="video/mp4">
            <?php _e('Your browser does not support the <code>video</code> element.', 'storefront'); ?>
        </video>

        <a href="#" class="close-video js-video-trigger"><?php _e('Close', 'storefront'); ?></a>
    </section>


    <div class="home-articles">
        <?php $count = 0; ?>
        <?php $count++; ?>
        <section class="benefits <?php echo($count == 1 ? 'box-first' : ''); ?>" style="background-image: url(<?php the_sub_field('section_background'); ?>);">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        while (have_posts()) : the_post();
                            the_content();
                        endwhile;
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
<!--                        --><?//= do_shortcode('[DistributorMap]') ?>
                        <hr>
                        We are proud to work with a network of partners internationally.
                        On the map below you will find our sales partners who will be happy to demonstrate SCANIFY or answer any sales enquiries you might have.
                        <iframe src="https://www.google.com/maps/d/embed?mid=z0jS8QNE8cR0.kWKzrbEU4-EU" width="100%" height="480"></iframe>
                    </div>
                </div>
            </div>
        </section>
    </div>

</main><!-- #main -->
<?php get_footer(); ?>
