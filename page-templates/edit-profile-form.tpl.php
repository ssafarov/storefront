<?php
  /**
   * woocommerce_before_main_content hook
   *
   */

   do_action( 'woocommerce_before_main_content' );

    // TODO: Should have a method that returns this info
    $current_user = wp_get_current_user();
    $full_name = $current_user->user_firstname . ' ' .$current_user->user_lastname;
?>

<div class="well distributor-home clearfix">
    <div class="row distributor-profile">
        <div class="col-sm-12">
            <div class="distributor-info">
                <div class="row no-gutter">
                    <div class="col-xs-12 col-sm-2 col-md-1">
                        <?php echo get_avatar( $current_user->user_email, '64' ); ?>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-7">
                        <h1><?php echo $full_name; ?></h1>
                        <div class="subtitle"><?php echo $info['company']; ?></div>
                    </div>

                    <div class="col-xs-12 col-sm-4 text-right">
                        <div class="buttons-wrap">
                            <a class="btn btn-secondary" href="/portal/" title="Back to portal"><?php _e('Back to portal', 'storefront'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" class="col-xs-12">
            <div class="entry-content entry">
                <?php the_content(); ?>
                <?php if (!is_user_logged_in()) : ?>
                    <p class="warning">
                        <?php _e('You must be logged in to edit your profile.', 'storefront'); ?>
                    </p><!-- .warning -->
                <?php else : ?>
                    <?php if (count($error) > 0) echo '<p class="error">' . implode("<br />", $error) . '</p>'; ?>
                        <form method="post">
                            <h3><?php _e('Profile details', 'storefront'); ?></h3>

                            <div class="row">

                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="first-name">
                                        <?php _e('First name', 'storefront'); ?>
                                    </label>

                                    <div class="form-control-wrapper">
                                        <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta('first_name', $current_user->ID); ?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="last-name">
                                        <?php _e('Last name', 'storefront'); ?>
                                    </label>

                                    <div class="form-control-wrapper">
                                        <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta('last_name', $current_user->ID); ?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?php $selected = esc_attr(get_user_meta($current_user->ID, 'default_currency', true)); ?>

                                    <label for="default_currency">
                                        <?php _e('Choose your default currency', 'storefront'); ?>&nbsp;*
                                    </label>

                                    <div class="form-control-wrapper">
                                        <select name="default_currency" id="default_currency" data-validation="required">
                                            <option value="GBP" <?php ($selected == 'GBP') ? print "selected='selected'" : ""; ?>>
                                                <?php _e('Pounds Sterling [&pound;]', 'storefront'); ?>
                                            </option>

                                            <option value="EUR" <?php ($selected == 'EUR') ? print "selected='selected'" : ""; ?>>
                                                <?php _e('Euros [&euro;]', 'storefront'); ?>
                                            </option>

                                            <option value="USD" <?php ($selected == 'USD') ? print "selected='selected'" : ""; ?>>
                                                <?php _e('US Dollars [$]', 'storefront'); ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="email">
                                        <?php _e('E-mail', 'storefront'); ?>&nbsp;*
                                    </label>

                                    <div class="form-control-wrapper">
                                        <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta('user_email', $current_user->ID); ?>" data-validation="email">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pass1">
                                        <?php _e('Change password', 'storefront'); ?>
                                    </label>

                                    <div class="form-control-wrapper">
                                        <input class="text-input" name="pass1" type="password" id="pass1" data-validation="length" data-validation-length="min6" data-validation-optional="true">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pass2">
                                        <?php _e('Repeat password', 'storefront'); ?>
                                    </label>

                                    <div class="form-control-wrapper">
                                        <input class="text-input" name="pass2" type="password" id="pass2" data-validation="confirmation" data-validation-confirm="pass1">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (in_array('distributor', $current_user->roles)) { ?>
                            <hr/>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="first-name">
                                            <?php _e('Vat number', 'storefront'); ?>
                                        </label>

                                        <div class="form-control-wrapper">
                                            <input class="vat_number" name="vat_number" type="text" id="vat_number" value="<?= get_user_meta(get_current_user_id(), 'vat_number', true); ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <hr>

                        <div class="row">
                        <?php
                            // Display notices (if any)
                            wc_print_notices();
                            // Render all woocomm fields
                            render_woocomm_fields();
                        ?>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="form-submit">
                                    <?= isset($referer) ? $referer : '' ?>
                                    <input name="updateuser" type="submit" id="updateuser" class="btn btn-secondary pull-right" value="<?php _e('Update', 'storefront'); ?>"/>
                                    <?php wp_nonce_field('update-user') ?>
                                    <input name="action" type="hidden" id="action" value="update-user"/>
                                </p><!-- .form-submit -->
                            </div>
                        </div>
                    </form><!-- #adduser -->
                <?php endif; ?>
            </div>
            <!-- .entry-content -->
        </div><!-- .hentry .post -->
    <?php endwhile; ?>
    <?php else: ?>
        <p class="no-data">
            <?php _e('Sorry, no page matched your criteria.', 'storefront'); ?>
        </p><!-- .no-data -->
    <?php endif; ?>
    </div>
</div>
<?php
  /**
   * woocommerce_after_main_content hook
   */
  do_action( 'woocommerce_after_main_content' );
?>