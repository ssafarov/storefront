<?php
  /**
   * woocommerce_before_main_content hook
   *
   */

   do_action( 'woocommerce_before_main_content' );

    // TODO: Should have a method that returns this info
    //$current_user = wp_get_current_user();
    $full_name = $current_user->user_firstname . ' ' .$current_user->user_lastname;
?>

<div class="well distributor-home clearfix">

<?php if (is_user_logged_in() && $current_user->has_cap('distributor')) : ?>

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
                            <a class="btn btn-secondary" href="/distributor-portal/" title="<?php _e('Back to portal', 'storefront'); ?>"><?php _e('Back to portal', 'storefront'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

    <div class="row">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" class="col-xs-12">
            <div class="entry-content entry">
                <?php the_content(); ?>
                <?php if (!is_user_logged_in() || !$current_user->has_cap('distributor')) : ?>
                    <p class="warning">
                        <?php _e('You must be logged in and have appropriate privileges to add distributors.', 'storefront'); ?>
                    </p><!-- .warning -->
                <?php else : ?>
                    <div class="inner-row">
                        <h2><?php if ($selectedAction != 'do-add-distributor') {
                                _e('Edit the user details below.', 'storefront');
                            } else {
                                _e('Enter new user details below.', 'storefront');
                            } ?></h2>
                    </div>
                    <form method="post">
                        <h3><?php _e("Profile details", 'storefront'); ?></h3>

                        <div class="row">

                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="user-login">
                                        <?php _e('User name (login) *', 'storefront'); ?>
                                    </label>
                                    <div class="form-control-wrapper">
                                        <input class="text-input" name="user-login<?php if ($selectedAction != 'do-add-distributor') : ?>-show<?php endif; ?>" type="text" id="user-login" required
                                               placeholder="<?php _e('Enter the user nickname (login)', 'storefront'); ?>"
                                               <?php if ($selectedAction != 'do-add-distributor') : ?> disabled <?php endif; ?>
                                               value="<?php if (isset($selectedUser)) { echo ($selectedUser->user_login); }?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="first-name">
                                        <?php _e('First name *', 'storefront'); ?>
                                    </label>
                                    <div class="form-control-wrapper">
                                        <input class="text-input" name="first-name" type="text" id="first-name" required
                                               placeholder="<?php _e('Enter the user first name', 'storefront'); ?>"
                                               value="<?php if (isset($selectedUser)) { echo ($selectedUser->user_firstname); }?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="last-name">
                                        <?php _e('Last name *', 'storefront'); ?>
                                    </label>
                                    <div class="form-control-wrapper">
                                        <input class="text-input" name="last-name" type="text" id="last-name" required
                                               placeholder="<?php _e('Enter the user second name', 'storefront'); ?>"
                                               value="<?php if (isset($selectedUser)) { echo ($selectedUser->user_lastname); }?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="email1">
                                        <?php _e('E-mail *', 'storefront'); ?>
                                    </label>
                                    <div class="form-control-wrapper">
                                        <input class="text-input" name="email1" type="text" id="email1" data-validation="email"
                                               placeholder="<?php _e('Enter the user e-mail', 'storefront'); ?>"
                                               value="<?php if (isset($selectedUser)) { echo ($selectedUser->user_email); } ?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email2">
                                        <?php _e('E-mail confirm *', 'storefront'); ?>
                                    </label>
                                    <div class="form-control-wrapper">
                                        <input class="text-input" name="email2" type="text" id="email2" data-validation="confirmation"  data-validation-confirm="email1"
                                               placeholder="<?php _e('Repeat the user e-mail', 'storefront'); ?>"
                                               value="<?php if (isset($selectedUser)) { echo ($selectedUser->user_email); }?>"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pass1">
                                        <?php _e('Password *', 'storefront'); ?>
                                    </label>
                                    <div class="form-control-wrapper">
                                        <input class="text-input" name="pass1" type="password" id="pass1" <?php if ($selectedAction == 'do-add-distributor') : ?> data-validation="length" data-validation-length="min6" data-validation-optional="false" <?php endif; ?>
                                               placeholder="<?php if ($selectedAction == 'do-add-distributor') { _e('Enter the user password', 'storefront'); } else { _e('Enter the NEW user password', 'storefront');} ?>" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pass2">
                                        <?php _e('Password confirm *', 'storefront'); ?>
                                    </label>
                                    <div class="form-control-wrapper">
                                        <input class="text-input" name="pass2" type="password" id="pass2"  <?php if ($selectedAction == 'do-add-distributor') : ?> data-validation="confirmation" data-validation-confirm="pass1" <?php endif; ?>
                                               placeholder="<?php _e('Confirm the user password', 'storefront'); ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h3><?php _e("Extra capabilities"); ?></h3>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <div class="form-control-wrapper">
                                        <label for="myDistrCap">
                                            <input class="text-input" name="myDistrCap" type="checkbox" id="myDistrCap" value="1" <?php if (isset($selectedUser)) { if ($selectedUser->has_cap('can_view_my_distributor_tab')) : ?> checked <?php endif; }?>  />
                                            <?php _e('Enable "My Distributor account" tab', 'storefront'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrapper">
                                        <label for="plcOrderCap">
                                            <input class="text-input" name="plcOrderCap" type="checkbox" id="plcOrderCap" value="1" <?php if (isset($selectedUser)) { if ($selectedUser->has_cap('can_view_place_order_tab')) : ?> checked <?php endif; } ?>  />
                                            <?php _e('Enable "Place Order" tab', 'storefront'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrapper">
                                        <label for="hlpSupportCap">
                                            <input class="text-input" name="hlpSupportCap" type="checkbox" id="hlpSupportCap" value="1" <?php if (isset($selectedUser)) { if ($selectedUser->has_cap('can_view_help_support_tab')) : ?> checked <?php endif; } ?> />
                                            <?php _e('Enable "Help&Support" tab', 'storefront'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrapper">
                                        <label for="shrInfoCap">
                                            <input class="text-input" name="shrInfoCap" type="checkbox" id="shrInfoCap" value="1" <?php if (isset($selectedUser)) { if ($selectedUser->has_cap('can_view_share_info_tab')) : ?> checked <?php endif; } ?> />
                                            <?php _e('Enable "Share info" tab', 'storefront'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control-wrapper">
                                        <label for="mdAssetsCap">
                                            <input class="text-input" name="mdAssetsCap" type="checkbox" id="mdAssetsCap" value="1" <?php if (isset($selectedUser)) { if ($selectedUser->has_cap('can_view_media_assets_tab')) : ?> checked <?php endif; } ?> />
                                            <?php _e('Enable "Media&Assets" tab', 'storefront'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <?php
                                // Display notices (if any)
                                wc_print_notices();
                            ?>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="form-submit">
                                    <?php
                                        echo $referer;
                                        if (isset($selectedUser)) {
                                            $selUID = $selectedUser->ID;
                                            $selLogin = $selectedUser->user_login;
                                        } else {
                                            $selUID = '';
                                        }
                                    ?>

                                    <?php
                                        if ($selectedAction == 'do-add-distributor'){
                                            $actString = '<input name="action" type="hidden" id="action" value="add-distributor"/>';
                                            $actString .= '<input name="doaction" type="submit" id="doaction" class="btn btn-secondary pull-right" value="'. __('Add user', 'profile') .'"/>';
                                        } else {
                                            $actString = '<input name="action" type="hidden" id="action" value="save-distributor"/>';
                                            $actString .= '<input name="user-login" type="hidden" value="'.$selLogin.'"/>';
                                            $actString .= '<input name="distributor-id" type="hidden" id="distributor" value="'.$selUID.'"/>';
                                            $actString .= '<input name="doaction" type="submit" id="doaction" class="btn btn-secondary pull-right" value="'. __('Save user', 'profile') .'"/>';
                                        }
                                    ?>

                                    <?php
                                        wp_nonce_field('update-user');
                                        echo $actString;
                                    ?>

                                </p><!-- .form-submit -->
                            </div>
                        </div>
                    </form><!-- #adduser -->
                    <?php
                        if ($selectedAction != 'do-add-distributor'): ?>
                            <script>
                                jQuery(document).ready(function() {
                                    jQuery('#pass1').on('change', function (e) {
                                        jQuery('#pass1').attr('data-validation', 'length');
                                        jQuery('#pass1').attr('data-validation-length', 'min6');
                                        jQuery('#pass1').attr('data-validation-optional', 'false');
                                        jQuery('#pass2').attr('data-validation', 'confirmation');
                                        jQuery('#pass2').attr('data-validation-confirm', 'pass1');
                                    });
                                });
                            </script>
                        <?php endif; ?>
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