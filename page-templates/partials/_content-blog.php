
<div class="col-xs-12 col-sm-6">
    <div class="post">
        <?php if (has_post_thumbnail()): ?>
            <div class="post-image"><a href="<?php echo get_the_guid()  ?>"><img class="img img-zoom" src="<?php echo wp_get_attachment_image_src(get_post_thumbnail_id($ID), 'single-post-thumbnail')[0]; ?>"/></a></div>
        <?php endif; ?>
        <div class="names-categories"><?php echo $categories; ?></div>
        <div class="title"><a href="<?php echo get_the_guid()  ?>"><?php the_title(); ?></a></div>
        <div class="date-author"><?php echo _e('by', 'storefront') ?> <?php the_author() ?> <?php echo the_time('M d, Y'); ?></div>
    </div>
</div>
