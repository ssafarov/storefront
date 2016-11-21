<?php

/**
 * Customer before renewal
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="mobileOff" style="color: #000000;">

    <tr>
        <td height="20" style="font-size:10px; line-height:40px;">&nbsp;</td> <!-- Spacer -->
    </tr>

<?php /*
    <tr>
        <td>
            <h2 style="font-family: 'Lato', Arial, sans-serif; font-size:24px; line-height: 30px; color: #464646; font-weight:normal;">
                <?php _e('Before Renewal', 'storefront'); ?>
            </h2>
        </td>
    </tr>
*/ ?>
    <tr>
        <td height="20" style="font-size:10px; line-height:40px;">&nbsp;</td> <!-- Spacer -->
    </tr>

    <tr>
        <td align="left" class="mobile" style="font-family: Tahoma, 'Lato', Arial, sans-serif; font-size:18px; line-height:24px; font-weight: 300;">
            <?php _e('Hi', 'storefront'); echo !isset($user->display_name) ?: " $user->display_name";  ?>,<br><br>
            <?php _e('Your current Fuel3D Studio Software subscription will expire shortly.', 'storefront'); ?><br><br>
            <?php _e('To make it easy, we have set up your account to automatically renew and you can check the details on the customer portal https://www.fuel-3d.com/portal/.',
                'storefront'); ?><br><br>
            <?php _e('To update your credit card details or to unsubscribe from auto-renew please visit the user portal.', 'storefront'); ?><br><br>
            <?php _e('Thanks for your continued custom.', 'storefront'); ?><br><br>
            <?php _e('Best wishes', 'storefront'); ?>,<br><br>
            <?php _e('The Fuel3D team', 'storefront'); ?>

        </td>
    </tr>

    <tr>
        <td height="20" style="font-size:10px; line-height:40px;">&nbsp;</td> <!-- Spacer -->
    </tr>

</table>

</td>
</tr>
</tbody>
</table>
<!-- Start Container  -->

</td>
</tr>
</tbody>
</table>

</td>
</tr>
</tbody>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_footer' ); ?>