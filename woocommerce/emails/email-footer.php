<?php
/**
 * Email Footer
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates/Emails
 * @version       2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

// Load colours
$base = get_option('woocommerce_email_base_color');

$base_lighter_40 = wc_hex_lighter($base, 40);

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline.
$template_footer = "
	border-top:0;
	-webkit-border-radius:6px;
";

$credit = "
	border:0;
	color: $base_lighter_40;
	font-family: Arial;
	font-size:12px;
	line-height:125%;
	text-align:center;
";

?>
<!-- Module Footer -->
<!-- Start Wrapper  -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#464646">
    <tr>
        <td width="100%" valign="top" align="center">
            <table width="640" cellpadding="0" cellspacing="0" border="0" class="container">
                <tr>
                    <td height="30" style="font-size:30px; line-height:30px;"></td> <!-- Spacer -->
                </tr>
                <tr>
                    <td align="center">

                        <!-- Start Container  -->
                        <table width="600" cellpadding="0" cellspacing="0" border="0" class="container">
                            <tr>
                                <td width="300" class="mobile" align="left" style="font-size:12px; line-height:18px;">
                                    <table cellspacing="0" cellspacing="0">
                                        <tr>
                                            <td width="50%" align="center" class="mobile">
                                                <table cellspacing="0" cellspacing="0">
                                                    <tr>
                                                        <td width="60px">
                                                            <a href="#"><img width="40" src="<?php echo site_url('/img/email-templates/icon-fb.jpg','https'); ?>" alt="<?php _e('Facebook', 'storefront'); ?>"></a>
                                                        </td>
                                                        <td width="60px">
                                                            <a href="#"><img width="40" src="<?php echo site_url('/img/email-templates/icon-inst.jpg','https'); ?>" alt="<?php _e('Instagram', 'storefront'); ?>"></a>
                                                        </td>
                                                        <td width="60px">
                                                            <a href="#"><img width="40" src="<?php echo site_url('/img/email-templates/icon-tw.jpg','https'); ?>" alt="<?php _e('Twitter', 'storefront'); ?>"></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="mobileOn" colspan="3" height="20" style="font-size:20px; line-height:20px;"></td> <!-- Spacer -->
                                                    </tr>
                                                </table>
                                            </td>
                                            <td width="50%" class="mobile" align="center">
                                                <table cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td>
                                                            <a style="color: #ffffff; text-decoration: underline; font-size: 18px; font-weight: bold; font-family: 'Lato', Arial, sans-serif;" href="http://www.fuel-3d.com">www.fuel-3d.com</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" height="20" style="font-size:20px; line-height:20px;"></td> <!-- Spacer -->
                                        </tr>
                                        <tr>
                                            <td colspan="2" valign="middle" id="credit" style="<?php echo $credit; ?>">
                                                <?php echo wpautop(wp_kses_post(wptexturize(apply_filters('woocommerce_email_footer_text',
                                                    get_option('woocommerce_email_footer_text'))))); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" height="20" style="font-size:20px; line-height:20px;"></td> <!-- Spacer -->
                                        </tr>
                                        <tr>
                                            <td style="color: #ffffff; font-size: 12px; font-family: 'Lato', Arial, sans-serif;" colspan="4">
                                                <?php
                                                _e( '&copy;&nbsp;Copyright', 'storefront');
                                                echo(' ' . date('Y') . ' ');
                                                _e(
                                                    'Fuel 3D Technologies Limited. All rights reserved. Fuel3D®, SCANIFY® and PELLEGO® are registered trademarks in the name of Fuel 3D Technologies Limited. All other trademarks are the property of their respective owners. All Fuel 3D Technologies Limited products and services are subject to continuous development. We reserve the right to alter technical specifications without prior notice.',
                                                    'storefront'
                                                ); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" height="20" style="font-size:20px; line-height:20px;"></td> <!-- Spacer -->
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- Start Container  -->

                    </td>
                </tr>
                <tr>
                    <td height="10" style="font-size:10px; line-height:10px;"></td> <!-- Spacer -->
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- End Wrapper  -->


</td>
</tr>
</table>
<!-- End Background -->

</body>
</html>
