<?php
/**
 * Template functions used for the site header.
 *
 * @package storefront
 */

if ( ! function_exists( 'storefront_header_widget_region' ) ) {
    /**
     * Display header widget region
     * @since  1.0.0
     */
    function storefront_header_widget_region() {
    }
}

if ( ! function_exists( 'storefront_site_branding' ) ) {
    /**
     * Display Site Branding
     * @since  1.0.0
     * @return void
     */
    function storefront_site_branding() {
    ?>
        <div class="pull-left">
            <div class="site-branding">
                <h1 class="site-title">
                    <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                </h1>
            </div>
        </div>
    <?php
    }
}

if ( ! function_exists( 'storefront_mobile_navigation' ) ) {
    /**
     * Display Mobile Navigation
     * @since  2.0.0
     * @return void
     */
    function storefront_mobile_navigation() {
        global $current_user;
        ?>

        <div class="text-right pull-left">
            <div class="mobile-menu-switch" style="margin-right: 5px!important;"><i class="fa fa-bars"></i></div>
        </div>
        <nav class="mobile-menu">
            <div class="user-connect">
                <i class="icon-user"></i>
                <?php if (is_user_logged_in()) { ?>
                    <?php $r = explode(' ', wp_get_current_user()->display_name); echo reset($r); ?>
                    <ul>
                        <li><a href="/edit-profile/"><?php _e( 'Edit profile', 'storefront' ); ?></a></li>
                        <?php if ( in_array('distributor', $current_user->roles) ) : ?>
                            <li><a href="/distributor-portal/"><?php _e( 'Distributor portal', 'storefront' ); ?></a></li>
                        <?php else : ?>
                            <li><a href="/portal/"><?php _e( 'User Portal' , 'storefront'); ?></a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'Log Out', 'storefront' ) ?></a></li>
                    </ul>
                <?php } else { ?>
                    <a class="sign-in" href="/portal/"><?php _e( 'Login' , 'storefront'); ?></a>
                <?php } ?>
            </div>
            <div class="menu-wrap">

                <?php if ( ! is_user_logged_in()) : ?>
                    <ul class="menu visible-xs visible-sm">
                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-712">
                            <a href="/portal/?register"><?php _e( 'Register' , 'storefront'); ?></a>
                        </li>
                    </ul>
                <?php endif; ?>

                <?php wp_nav_menu(); ?>

                <ul class="menu visible-xs visible-sm">
                    <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-shop">
                        <a href="/shop-redirect/"><?php _e( 'Shop' , 'storefront'); ?></a>
                    </li>
                </ul>

            </div>
        </nav>
        <!-- #site-navigation -->
    <?php
    }
}

if ( ! function_exists( 'storefront_desktop_navigation' ) ) {
    /**
     * Display Desktop Navigation
     * @since  2.0.0
     * @return void
     */
    function storefront_desktop_navigation() {
        ?>
        <div class="pull-left">
            <div class="row-inner">
                <nav id="site-navigation" class="main-navigation" role="navigation">
                    <button class="menu-toggle">Primary Menu</button>
                    <div class="desktop-menu">
                        <?php wp_nav_menu(); ?>
                    </div>
                </nav>
            </div>
        </div>
        <div class="nav-hello end pull-left">
            <a class="btn btn-block sign-in link-sign-in" href="/shop-redirect/"><?php _e( 'Shop' , 'storefront'); ?></a>
        </div>
    <?php
    }
}

if ( ! function_exists( 'storefront_signin_signout_umenu' ) ) {
    /**
     * Display Secondary Navigation
     * @since  1.0.0
     * @return void
     */
    function storefront_signin_signout_umenu() {
        global $current_user;
        if ( is_user_logged_in() ) : ?>
            <div class="nav-hello pull-right" style="margin-right:0!important;">
                <div class="secondary-navigation">
                    <ul class="nav-menu">
                        <li style="text-align: right;">
                            <span><?= __('Hello', 'storefront') ?>&nbsp;&nbsp;<?php echo $current_user->first_name; ?></span>
                            <ul class="sub-menu">
                                <li><a href="/edit-profile/"><?php _e( 'Edit profile', 'storefront' ); ?></a></li>
                                <?php if ( in_array('distributor', $current_user->roles) ) : ?>
                                    <li><a href="/distributor-portal/"><?php _e( 'Distributor portal', 'storefront' ); ?></a></li>
                                <?php else : ?>
                                    <li><a href="/portal/"><?php _e( 'User Portal', 'storefront' ); ?></a></li>
                                <?php endif; ?>
                                <li><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'Log Out', 'storefront' ) ?></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        <?php  else : ?>
            <div class="nav-hello pull-right btn-sign-in-wrapper">
                <a id="register" class="btn btn-primary btn-block sign-in btn-sign-in" href="/portal/?register"><?php _e( 'Register', 'storefront' ) ?></a>
            </div>
            <div class="nav-hello pull-right btn-sign-in-wrapper">
                <a id="login" class="btn btn-secondary btn-block sign-in btn-sign-in" href="/portal/"><?php _e( 'Login', 'storefront' ) ?></a>
            </div>
        <?php endif; ?>
    <?php
    }
}

if ( ! function_exists( 'storefront_basket_checkout' ) ) {
    /**
     * Display Secondary Navigation
     * @since  2.0.0
     * @return void
     */
    function storefront_basket_checkout() {
        global $woocommerce;
        $quantity = $woocommerce->cart->cart_contents_count;
        if (!is_cart() && !is_checkout()){
        ?>
            <span class="cart">
                <?php
                if ($quantity > 0) {
                // $woocommerce->cart->get_cart_url();
                    ?>
                    <a href="#" class="nav-buy-btn cart" >
                        <i class="nav-control-link-icon fa fa-shopping-cart"></i>
                        <span class="buy-counter" data-cart_total_count="<?= $quantity ?>"><?= $quantity ?></span> <span class="nav-control-link-label"><?= _n('item', 'items', $quantity, 'storefront') ?></span>
                    </a>
                <?php
                }
                ?>
            </span>
        <?php
        }
    }
}

if ( ! function_exists( 'storefront_site_currency' ) ) {
    /**
     * Display Site Branding
     * @since  2.0.0
     * @return void
     */
    function storefront_site_currency() {
        echo do_shortcode( '[aelia_currency_selector_widget title="" widget_type="buttons"]' );

    }
}

if (!function_exists('storefront_language_selector')) {
    /**
     * Display language selector
     * @since  2.0.0
     * @return void
     */
    function storefront_language_selector()
    {
        if (!class_exists('QTX_Translator')) {
            return;
        }

        global $q_config;
        $language = $q_config['language'];
        $language_name = $q_config['language_name'][$language];
        ?>

        <div class="nav-control-item dropdown">
            <div class="dropdown dropdown-language pull-right hidden" id="langSelector">
                <a href="" class="nav-control-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="nav-control-link-icon fa fa-globe"></i>
                    <span class="nav-control-link-label hidden-xs hidden-sm"><?php echo $language_name; ?></span>
                </a>
                <?php
                    dynamic_sidebar('langselector-top');
                ?>
            </div>
        </div>
        <script>
            jQuery( document ).ready(function() {
                var
                    selector = jQuery('#langSelector'),
                    sleSubMenu;

                if ((selector).length > 0)
                {
                    sleSubMenu = selector.find('.qtranxs_language_chooser');
                    selector.removeClass('hidden');
                }

                if ((sleSubMenu).length > 0)
                {
                    sleSubMenu.addClass('dropdown-menu');
                    sleSubMenu[0].setAttribute("aria-labelledby", "dLabel");
                }
            });
        </script>
    <?php
    }
};

if (!function_exists('storefront_currency_selector')) {
    /**
     * Display currency selector
     * @since  2.0.0
     * @return void
     */
    function storefront_currency_selector()
    {
    collect_currency_info();
//    global $woocommerce;
	global $selected_currency;
//	global $selected_currency_sign;
	global $selected_currency_sign_icon;

    ?>
        <script>
            jQuery(function ($) {
    			$(document).on('click', '.nav-currency-link', function (e) {
    			    e.stopPropagation();
    			    e.preventDefault();
    			    var $target = $(e.currentTarget);
    			    var currency = $target.attr('data-currency');

    			    var $form = $(document).find('[name="cs_selector_form"]');
    			    var $input = $(document).find('[name="aelia_cs_currency"]');

    			    $input.val(currency);

    			    $form.submit();
    			    return false;
    			});
            });
        </script>

        <div class="nav-control-item dropdown">
            <div class="dropdown dropdown-language pull-right" id="currencySelector">
                <a href="" class="nav-control-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="nav-control-link-icon fa <?php echo $selected_currency_sign_icon; ?>"></i>
                    <span class="nav-control-link-label hidden-xs hidden-sm"><?php  _e($selected_currency, 'storefront'); ?></span>
                </a>
                <form method="post" name="cs_selector_form" class=""><input type="hidden" name="aelia_cs_currency" value=""/></form>
                <ul class="dropdown-menu" aria-labelledby="dLabel" id="currencySelector">
                    <li><a href="" data-currency="EUR" class="nav-currency-link"><i class="nav-control-link-icon fa fa-euro"></i> <span class="nav-control-link-label"><?php _e('EUR', 'storefront'); ?></span></a></li>
                    <li><a href="" data-currency="USD" class="nav-currency-link"><i class="nav-control-link-icon fa fa-usd"></i> <span class="nav-control-link-label"><?php _e('USD', 'storefront'); ?></span></a></li>
                    <li><a href="" data-currency="GBP" class="nav-currency-link"><i class="nav-control-link-icon fa fa-gbp"></i> <span class="nav-control-link-label"><?php _e('GBP', 'storefront'); ?></span></a></li>
                </ul>
            </div>
        </div>
<?php
    }
};

if (!function_exists('storefront_inner_row_start')) {
    /**
     * @since  1.0.0
     * @return void
     */
    function storefront_inner_row_start()
    {
    ?>
        <div class='row-inner'>
      <?php
    }
};

if (!function_exists('storefront_inner_row_end')) {
      /**
       * @since  1.0.0
       * @return void
       */
      function storefront_inner_row_end()
      {
      ?>
            </div>
    <?php
    }
};


if (!function_exists('storefront_div_row_start')) {
    /**
     * @since  1.0.0
     * @return void
     */
    function storefront_div_row_start()
    {
        ?>
        <div class='row'>
        <?php
    }
};

if (!function_exists('storefront_div_row_ml10r0_start')) {
    /**
     * @since  Navigation bar redesign
     * @return void
     */
    function storefront_div_row_ml10r0_start()
    {
    ?>
        <div class='row' style="margin-left:10px!important; margin-right:0!important;">
    <?php
    }
};

if (!function_exists('storefront_div_end')) {
    /**
     * @since  1.0.0
     * @return void
     */
    function storefront_div_end()
    {
    ?>
        </div>
    <?php
    }
};

if (!function_exists('storefront_right_nav_control_start')) {
    /**
     * @since  Navigation bar redesign
     * @return void
     */
    function storefront_right_nav_control_start()
    {
    ?>
        <div class="pull-right">
            <div class="nav-control">
    <?php
    }
};

if (!function_exists('storefront_right_phone')) {
    /**
     * @since  Navigation bar redesign
     * @return void
     */
    function storefront_right_phone()
    {
    ?>
        <div class="nav-control-item hidden-xs hidden-sm">
            <a href="/about-us" class="nav-control-link"><i class="nav-control-link-icon fa fa-phone"></i><span class="nav-control-link-label">USA: 18667840972</span></a>
        </div>
        <div class="nav-control-item hidden-xs hidden-sm">
            <a href="/about-us" class="nav-control-link"><i class="nav-control-link-icon fa fa-phone"></i><span class="nav-control-link-label">Outside-USA: +44(0)3300249502</span></a>
        </div>
    <?php
    }
};
