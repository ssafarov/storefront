<?php
/**
 * Customer delivered order and registration reminder email
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
                <?php _e('Please don’t forget to register your SCANIFY. Now that you have it, what will you create?', 'storefront'); ?>
            </h2>
        </td>
    </tr>

    <tr>
        <td height="20" style="font-size:10px; line-height:40px;">&nbsp;</td> <!-- Spacer -->
    </tr>

    <tr>
        <td align="left" class="mobile" style="font-family: 'Lato', Arial, sans-serif; font-size:18px; line-height:24px; font-weight: 300;">
            <?php _e('Registering your SCANIFY activates your Fuel3D product warranty and gives you full access to the support pages on our site.', 'storefront'); ?>
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

    <tr>
        <td align="center" class="mobile" style="font-family: Century Gothic, Arial, sans-serif; font-size:14px; line-height:20px;">
            <img style="max-width:100%;" src="<?php echo home_url(); ?>/img/email-templates/image-product.jpg" alt="Scanify">
        </td>
    </tr>

    <tr>
        <td height="20" style="font-size:10px; line-height:40px;">&nbsp;</td> <!-- Spacer -->
    </tr>

    <tr>
        <td align="center">
            <img style="max-width:100%;" src="<?php echo home_url(); ?>/img/email-templates/steps-delivered.jpg" alt="Shipped">
        </td>
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
                                    <img src="<?php echo home_url(); ?>/img/email-templates/image-product-extra-1.jpg" alt="<?php _e('Scanify Bag', 'storefront'); ?>">
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

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_footer' ); ?>