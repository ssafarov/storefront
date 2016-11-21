<?php
if (isset($_GET['test'])) {

    /**
     * @test is_ga_active
     */
    if ($_GET['test'] == 'is_ga_active') {
        $is_woo_ga_active = is_plugin_active('woocommerce-google-analytics-integration/woocommerce-google-analytics-integration.php');
        if ($is_woo_ga_active) {
            die('OK');
        }
        die('FAIL');
    }


}