<?php
/*
Template Name: Blog
*/

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package storefront
 */

$blog         = new \MVC\Models\Blog();
$featuredPost = $blog->getFeaturedPost();
$blogPosts    = $blog->getPosts(6);
$translator   = QTX_Translator::get_translator();
$categories = $blog->getBlogCategories();

?>

<?php get_header(); ?>

<div class="row">
    <div class="container blog-wrapper">

        <?php do_action( 'storefront_page_before' ); ?>

    <!-- right col. Small screens - on top -->
    <div class="col-xs-12 col-sm-12 col-md-3 col-md-push-9">
        <?php get_template_part_with_data( 'page-templates/partials/_newsletter_sign_up');?>
        <?php /*get_template_part_with_data( 'page-templates/partials/_sc-buyer-guid');*/?>

        <div class="row">
            <div id="blog-categories" class="col-xs-12 hidden-xs">
                <div class="row">
                    <div class="col-xs-12 header-text"><?php echo __('Categories', 'storefront') ?>:</div>
                </div>
                <?php if (!$categories->have_posts()):?>
                    <div class="row">
                        <div class="col-xs-12 empty-categories"><?php echo __('No Categories', 'storefront') ?></div>
                    </div>
                <?php endif;?>
                <?php
                while ($categories->have_posts()) {
                    $categories->the_post();
                    ?>
                <div class="row">
                    <div class="col-xs-12 categories-name"><?php echo get_custom_field('blog_categories'); ?></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 categories-title">
                        <a href="<?php echo(!empty(get_custom_field('blog_url')) ? get_custom_field('blog_url') : '#') ?>"><?php the_title(); ?></a>
                    </div>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>

        <!-- main large col. Small screens - below right col -->
        <div class="col-xs-12 col-sm-12 col-md-9 col-md-pull-3">

            <?php if (have_posts()): ?>
                <?php if ($featuredPost) : ?>
                <div class="featured-post">
                    <?php if(!empty($featuredPost->image)):?>
                        <div class="featured-image"><a href="<?php echo $featuredPost->guid ?>"><img class="img img-zoom" src="<?php echo $featuredPost->image?>"/></a></div>
                    <?php endif;?>
                    <div class="names-categories"><?php echo $blog->getFormattedPostCategories($featuredPost->ID); ?></div>
                    <div class="title">
                        <a href="<?php echo $featuredPost->guid ?>"><?php echo $translator->translate_text($featuredPost->post_title); ?></a>
                    </div>
                    <div class="date-author"><?php echo __( 'by', 'storefront' ).' '.$blog->getUserName($featuredPost->post_author).' '.get_the_time('M d, Y', $featuredPost->ID);?></div>
                </div>
                <?php get_template_part_with_data( 'page-templates/partials/_newsletter_sign_up_mobile');?>
                <?php endif;?>

                <div class="posts-container">
                    <?php
                    global $post;
                    $column = 1;
                    while ( have_posts() ) : the_post(); ?>

                        <?php if (($column % 2) == 1): ?>
                            <div class="row">
                        <?php endif; ?>

                        <?php get_template_part_with_data( 'page-templates/partials/_content-blog', array( 'categories' => $blog->getFormattedPostCategories(), 'ID'=> $post->ID ) );?>

                        <?php if (($column % 2) == 0): ?>
                            </div>
                        <?php endif; ?>

                        <?php $column++; endwhile; // end of the loop. ?>

                    <div class="row pagination">
                        <a href="#" id="get_older_posts" class="show_more_posts-button"><?php _e ('Older', 'storefront');?>&nbsp;></a>
                    </div>
                </div>

            <?php endif; ?>
        </div>
        <?php do_action( 'storefront_page_after' ); ?>
    </div>
</div>
<?php get_footer();?>
<?php echo '<script>var ajaxurl = \'' . admin_url('admin-ajax.php') . '\';</script>'?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/subscribe-form.js"></script>
<script>
    jQuery(function ($) {
        $('.newsletter-subscribe-right').on('click', function () {
            newsletterSubscribe(this, $);
            return false;
        });

        $('.newsletter-subscribe-right-mobile').on('click', function () {
            newsletterSubscribe(this, $);
            return false;
        })
    });
</script>
