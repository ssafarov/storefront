<?php
/**
 * Customer invoice email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text ); ?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="mobileOff" style="color: #000000;">
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
        <td>
            <h2 style="font-family: 'Lato', Arial, sans-serif; font-size:24px; line-height: 30px; color: #464646; font-weight:normal;">

                <?php
                    ob_start();
                    $output = ob_get_clean();
                    $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
                    if (preg_match_all("/$regexp/siU", $output, $matches, PREG_SET_ORDER)){
                        $tracking_link =  $matches[0][2];
                    } else {
                        $tracking_link =  '#';
                    }
                    $tracking_number   = get_post_meta( $order->id, '_tracking_number', true );

                    echo __( 'Order:', 'woocommerce' ) . ' ' . $order->get_order_number();
                ?>

                (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( wc_date_format(), strtotime( $order->order_date ) ) ); ?>)

                <br/>
                <?php _e('Tracking number:', 'storefront'); ?> <strong><?php echo $tracking_number;?></strong>
            </h2>

            <?php if ( $order->status === 'pending' ) : ?>
                <p><?php printf( __( 'An order has been created for you on %s. To pay for this order please use the following link: %s', 'woocommerce' ), get_bloginfo( 'name' ), '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . __( 'pay', 'woocommerce' ) . '</a>' ); ?></p>
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <td>
            <table width="170" cellpadding="0" cellspacing="0" align="left" border="0">
                <tbody>
                    <tr>
                        <td width="170" height="54" bgcolor="#9ac31c" align="center" valign="middle"
                            style="font-family: 'Lato', Arial, sans-serif; font-size: 18px; color: #ffffff; font-weight: bold; border-radius:3px;">
                            <a href="<?php echo $tracking_link; ?>" target="_blank" alias="Track your order" style="font-family: 'Lato', Arial, sans-serif; text-decoration: none; line-height:50px; color: #ffffff; display: block; width: 170px; height: 54px;">Track your order</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr>
        <td height="20" style="font-size:10px; line-height:40px;">&nbsp;</td> <!-- Spacer -->
    </tr>

    <tr>
        <td height="20" style="font-size:10px; line-height:40px;">

            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="mobileOff" style="color: #000000;">
                <tr style="text-align: left;">
                    <th style="border-bottom: solid 1px #464646; padding-bottom:10px;"><?php _e( 'QTY', 'storefront' ); ?></th>
                    <th style="border-bottom: solid 1px #464646; padding-bottom:10px;"><?php _e( 'NAME', 'storefront' ); ?></th>
                    <th style="border-bottom: solid 1px #464646; padding-bottom:10px;"><?php _e( 'UNIT PRICE', 'storefront' ); ?></th>
                    <th style="border-bottom: solid 1px #464646; padding-bottom:10px;"><?php _e( 'VAT', 'storefront' ); ?></th>
                    <th style="border-bottom: solid 1px #464646; padding-bottom:10px;"><?php _e( 'Tax', 'storefront' ); ?></th>
                    <th style="border-bottom: solid 1px #464646; padding-bottom:10px;"><?php _e( 'TOTAL', 'storefront' ); ?></th>
                </tr>
                <tbody>
		        <?php
                    switch ( $order->status ) {
                        case "completed" :
                            echo $order->email_order_items_table( $order->is_download_permitted(), false, true );
                            break;
                        case "processing" :
                            echo $order->email_order_items_table( $order->is_download_permitted(), true, true );
                            break;
                        default :
                            echo $order->email_order_items_table( $order->is_download_permitted(), true, false );
                            break;
                    }
                ?>
                </tbody>
                <tfoot>
                    <?php
                    if ( $totals = $order->get_order_item_totals() ) {
                        $i = 0;
                        foreach ( $totals as $total ) {$i++;?>
                            <tr>
                                <td scope="row" colspan="5" style="text-align:right; padding-right: 5px; <?php if ( $i == 1 ) echo 'border-top: solid 1px #464646;'; ?>"><?php echo $total['label']; ?></td>
                                <td style="text-align:left; padding-left: 5px; <?php if ( $i == 1 ) echo 'border-top: solid 1px #464646;'; ?>"><?php echo $total['value']; ?></td>
                            </tr><?php
                        }
                    }
                    ?>
                </tfoot>

            </table>
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