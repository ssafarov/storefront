<?php get_header(); ?>

<?php
/**
 * woocommerce_before_main_content hook
 *
 */
do_action('woocommerce_before_main_content');

?>

    <div class="well customer-home clearfix">
        <?php wc_print_notices(); ?>
        <div id="distributorTabs" role="tabpanel" class="customer-content clearfix">
            <ul class="customer-tabs clearfix" role="">
                <li role="presentation" class="active">
                    <a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">
                        <i class="icon-info"></i>
                        <span class="text"><?php _e('My Account', 'storefront'); ?></span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">
                        <i class="icon-cart"></i>
                        <span class="text"><?php _e('Licenses &amp; Software', 'storefront'); ?></span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">
                        <i class="icon-bubble"></i>
                        <span class="text"><?php _e('Help &amp; Support', 'storefront'); ?></span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab4" aria-controls="tab4" role="tab" data-toggle="tab">
                        <i class="icon-coins"></i>
                        <span class="text"><?php _e('Subscriptions', 'storefront'); ?></span>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#tab5" aria-controls="tab5" role="tab" data-toggle="tab">
                        <i class="icon-file"></i>
                        <span class="text"><?php _e('Products &amp; Invoices', 'storefront'); ?></span>
                    </a>
                </li>
            </ul>

            <div class="tab-content clearfix">
                <div class="row tab-pane active" role="tabpanel" id="tab3">
                    <div class="col-sm-12">
                        <?php
                        /**
                         * My account
                         */
                        echo \MVC\Controller::factory('Portal')->render('myAccount');
                        ?>
                    </div>
                </div>
                <div class="row tab-pane" role="tabpanel" id="tab1">
                    <div class="col-sm-12" id="licenses_and_software_tab">
                        <?php
                        /**
                         * Licenses & Software
                         */
                        // todo ajax
                        // echo (new MVC\Controllers\PortalController())->licensesAndSoftwareAction();
                        echo \MVC\Controller::factory('Portal')->render('licensesAndSoftware');
                        ?>
                    </div>
                </div>
                <div class="row tab-pane" role="tabpanel" id="tab2">
                    <div class="col-xs-12" id="help-support">
                        <?php
                        /**
                         * Help & Support Tab
                         */
                        echo \MVC\Controller::factory('Portal')->render('helpAndSupport');
                        ?>
                    </div>
                </div>
                <div class="row tab-pane" role="tabpanel" id="tab4">
                    <div class="col-xs-12" id="share-information">
                        <?php
                        /**
                         * Subscriptions
                         */
                        echo \MVC\Controller::factory('Portal')->render('subscriptions');
                        ?>
                    </div>
                </div>
                <div class="row tab-pane" role="tabpanel" id="tab5">
                    <div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2" id="share-information">
                        <?php
                        /**
                         * Products & Invoices Tab
                         */
                        echo \MVC\Controller::factory('Portal')->render('productsAndInvoices');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
/**
 * woocommerce_after_main_content hook
 *
 */
do_action('woocommerce_after_main_content');
?>

<?php get_footer();; ?>