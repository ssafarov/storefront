<?php

/**
 * Customer completed order email
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

    <tr>
        <td>
            <h2 style="font-family: 'Lato', Arial, sans-serif; font-size:24px; line-height: 30px; color: #464646; font-weight:normal;">
                <?php _e('It is a shame that you have decided to cancel! No problem, but we would really appreciate it if you could let us know why?', 'storefront'); ?>
            </h2>
        </td>
    </tr>

    <tr>
        <td height="20" style="font-size:10px; line-height:40px;">&nbsp;</td> <!-- Spacer -->
    </tr>

    <tr>
        <td align="left" class="mobile" style="font-family: 'Lato', Arial, sans-serif; font-size:18px; line-height:24px; font-weight: 300;">
            <?php _e('Thanks for your interest in Fuel3D and SCANIFY.', 'storefront'); ?><br><br>
            <?php _e('Best wishes,', 'storefront'); ?><br><br>
            <?php _e('The Fuel3D Team', 'storefront'); ?>
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