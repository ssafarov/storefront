<?php
/**
 * Customer processing order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
    $downloadableOnly = apply_filters( 'wc_has_order_downloadable_items_only', $order);
?>

<?php do_action('woocommerce_email_header', $email_heading); ?>

<?php if (!$downloadableOnly): ?>
    <h2 style="font-family: 'Lato', Arial, sans-serif; font-size:24px; line-height: 30px; color: #464646; font-weight:normal;"><?php _e( "Your order is processing; then it will be packaged ready for shipping", 'storefront' ); ?></h2>
<?php endif; ?>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

    <h2 style="font-family: 'Lato', Arial, sans-serif; font-size:24px; line-height: 30px; color: #000000; font-weight:bold;"><?php echo __( 'Your Order', 'woocommerce' ) . ' ' . $order->get_order_number(); ?>:</h2>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="mobileOff" style="color: #000000;" bgcolor="#9ac31c">
        <tr style="text-align: left;">
            <th style="border-bottom: solid 1px #464646; padding-bottom:10px;"><?php _e( 'QTY', 'storefront' ); ?></th>
            <th style="border-bottom: solid 1px #464646; padding-bottom:10px;"><?php _e( 'NAME', 'storefront' ); ?></th>
            <th style="border-bottom: solid 1px #464646; padding-bottom:10px;"><?php _e( 'UNIT PRICE', 'storefront' ); ?></th>
            <th style="border-bottom: solid 1px #464646; padding-bottom:10px;"><?php _e( 'VAT', 'storefront' ); ?></th>
            <th style="border-bottom: solid 1px #464646; padding-bottom:10px;"><?php _e( 'Tax', 'storefront' ); ?></th>
            <th style="border-bottom: solid 1px #464646; padding-bottom:10px;"><?php _e( 'TOTAL', 'storefront' ); ?></th>
        </tr>
        <?php echo $order->email_order_items_table( $order->is_download_permitted(), true, ( $order->status=='processing' ) ? true : false ); ?>
    </table>

</td>
</tr>

<tr>
    <td height="20" style="font-size:20px; line-height:40px;">&nbsp;</td><!-- Spacer -->
</tr>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<tr>
    <td height="20" style="font-size:10px; line-height:40px;">&nbsp;</td> <!-- Spacer -->
</tr>

<?php do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text ); ?>

<?php if (!$downloadableOnly): ?>
<tr>
    <td align="center" class="mobile" style="font-family: Century Gothic, Arial, sans-serif; font-size:14px; line-height:20px;">
        <img style="max-width:100%;" src="<?php echo site_url('/img/email-templates/image-product.jpg', 'https'); ?>" alt="<?php _e('Scanify', 'storefront'); ?>">
    </td>
</tr>

<tr>
    <td height="20" style="font-size:10px; line-height:40px;">&nbsp;</td> <!-- Spacer -->
</tr>

<tr>
    <td align="center">
        <img style="max-width:100%;" src="<?php echo site_url('/img/email-templates/steps-processing.jpg', 'https'); ?>" alt="<?php _e('Processing', 'storefront'); ?>">
    </td>
</tr>
<?php endif; ?>

</td>
</tr>
</tbody>
</table>
<!-- Start Container  -->

</td>
</tr>
</tbody>
</table>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="wrapper" bgcolor="#FFFFFF">
    <tbody><tr>
        <td height="20" style="font-size:10px; line-height:10px;">&nbsp;</td><!-- Spacer -->
    </tr>
    <tr>
        <td align="center">

            <!-- Start Container  -->
            <table width="560" cellpadding="30" cellspacing="0" border="0" bgcolor="#d6ebf5" class="container">
                <tbody><tr>
                    <td width="360" class="mobile" align="center" valign="top">

                        <!-- Start Content -->
                        <table width="330" cellpadding="0" cellspacing="0" border="0" class="container" align="left">
                            <tbody><tr>
                                <td width="330" align="left" style="font-family: 'Lato', Arial, sans-serif; font-size:18px; line-height:24px; font-weight: 300;">
                                    <?php _e('Compliment your SCANIFY with some essential accessories.', 'storefront'); ?>
                                </td>
                            </tr>
                            </tbody></table>
                        <!-- Start Content -->
                    </td>
                    <td width="200" class="mobile" align="center" valign="top">

                        <!-- Start Content -->
                        <table width="200" cellpadding="0" cellspacing="0" border="0" class="container" align="right">
                            <tbody><tr>
                                <td height="20" style="font-size:10px; line-height:10px;">&nbsp;</td><!-- Spacer -->
                            </tr>
                            <tr>
                                <td align="center">
                                    <img src="<?php echo site_url('/img/email-templates/image-product-extra-1.jpg', 'https'); ?>" alt="<?php _e('Scanify Bag', 'storefront'); ?>">
                                </td>
                            </tr>
                            <tr class="mobileOn">
                                <td height="20" style="font-size:10px; line-height:10px;">&nbsp;</td><!-- Spacer -->
                            </tr>
                            </tbody></table>
                        <!-- Start Content -->
                    </td>
                </tr>
                </tbody></table>
            <!-- Start Container  -->
        </td>
    </tr>
    </tbody></table>


    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="wrapper" bgcolor="#FFFFFF">
    <tbody><tr>
        <td height="20" style="font-size:10px; line-height:10px;">&nbsp;</td><!-- Spacer -->
    </tr>
    <tr>
        <td align="center">

            <!-- Start Container  -->
            <table width="560" cellpadding="30" cellspacing="0" border="0" bgcolor="#ebf3d2" class="container">
                <tbody><tr>
                    <td width="360" class="mobile" align="center" valign="middle">
                        <!-- Start Content -->
                        <table width="330" cellpadding="0" cellspacing="0" border="0" class="container" align="left">
                            <tbody><tr>
                                <td width="330" align="left" class="mobile" style="font-family: 'Lato', Arial, sans-serif; font-size:18px; font-weight: 300; line-height:24px;">
                                    <?php _e('Do check out our FAQ;', 'storefront'); ?>
                                </td>
                            </tr>
                            <tr class="mobileOn">
                                <td height="20" style="font-size:10px; line-height:10px;">&nbsp;</td><!-- Spacer -->
                            </tr>
                            </tbody></table>
                        <!-- Start Content -->
                    </td>
                    <td width="200" class="mobile" align="center" valign="middle">

                        <!-- Start Content -->
                        <table width="200" cellpadding="0" cellspacing="0" border="0" class="container" align="right">
                            <tbody><tr>
                                <td width="170" height="38" bgcolor="#9ac31c" align="center" valign="middle" style="font-family: 'Lato', Arial, sans-serif; font-size: 18px; color: #ffffff; font-weight: bold; border-radius:3px;">
                                    <a href="https://fuel3d.zendesk.com/hc/en-us" target="_blank" alias="" style="font-family: 'Lato', Arial, sans-serif; text-decoration: none; line-height:34px; color: #ffffff; display: block; width: 170px; height: 38px;">FAQ</a>
                                </td>
                            </tr>
                            <tr class="mobileOn">
                                <td height="20" style="font-size:10px; line-height:10px;">&nbsp;</td><!-- Spacer -->
                            </tr>
                            </tbody></table>
                        <!-- Start Content -->
                    </td>
                </tr>
                </tbody></table>
            <!-- Start Container  -->
        </td>
    </tr>
    </tbody></table>



    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="wrapper" bgcolor="#FFFFFF">
    <tbody><tr>
        <td height="20" style="font-size:10px; line-height:10px;">&nbsp;</td><!-- Spacer -->
    </tr>
    <tr>
        <td align="center">

            <!-- Start Container  -->
            <table width="560" cellpadding="30" cellspacing="0" border="0" bgcolor="#ebf3d2" class="container">
                <tbody><tr>
                    <td width="360" class="mobile" align="center" valign="middle">

                        <!-- Start Content -->
                        <table width="330" cellpadding="0" cellspacing="0" border="0" class="container" align="left">
                            <tbody><tr>
                                <td width="330" align="left" class="mobile" style="font-family: 'Lato', Arial, sans-serif; font-size:18px; font-weight: 300; line-height: 24px;">
                                    <?php _e('For further questions view our helpdesk', 'storefront'); ?>
                                </td>
                            </tr>
                            <tr class="mobileOn">
                                <td height="20" style="font-size:10px; line-height:10px;">&nbsp;</td><!-- Spacer -->
                            </tr>
                            </tbody></table>
                        <!-- Start Content -->
                    </td>
                    <td width="200" class="mobile" align="center" valign="middle">

                        <!-- Start Content -->
                        <table width="200" cellpadding="0" cellspacing="0" border="0" class="container" align="right">
                            <tbody><tr>
                                <td width="170" height="38" bgcolor="#9ac31c" align="center" valign="middle" style="font-family: 'Lato', Arial, sans-serif; font-size: 18px; color: #ffffff; font-weight: bold; border-radius:3px;">
                                    <a href="https://fuel3d.zendesk.com/hc/en-us" target="_blank" alias="" style="font-family: 'Lato', Arial, sans-serif; text-decoration: none; line-height:34px; color: #ffffff; display: block; width: 170px; height: 38px;">Helpdesk</a>
                                </td>
                            </tr>
                            <tr class="mobileOn">
                                <td height="20" style="font-size:10px; line-height:10px;">&nbsp;</td><!-- Spacer -->
                            </tr>
                            </tbody></table>
                        <!-- Start Content -->
                    </td>
                </tr>
                </tbody></table>
            <!-- Start Container  -->
        </td>
    </tr>
    <tr class="mobileOn">
        <td height="20" style="font-size:10px; line-height:10px;">&nbsp;</td><!-- Spacer -->
    </tr>
    </tbody></table>



</td>
</tr>
</tbody>
</table>

<?php do_action( 'woocommerce_email_footer' ); ?>
