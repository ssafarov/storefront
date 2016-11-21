<?php
/**
 * Customer delivered order and registration reminder email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<!-- new email header start -->
<?php do_action( 'fuel3d_email_header', $email_heading ); ?>
<!-- new email header end -->

<!-- main content begin -->
<div id="main">
    <table class="main-body row" style="border-spacing:0;border-collapse:collapse;vertical-align:top;text-align:left;background-color:#9ac41d;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100%;position:relative;display:table;">
        <tbody>
        <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;vertical-align:top;text-align:left;">
            <th class="small-12 large-12 columns first last" style="color:#464646;font-family:Helvetica,Arial,sans-serif;font-weight:normal;text-align:left;font-size:16px;line-height:19px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;Margin:0 auto;padding-top:26px !important;padding-bottom:26px !important;width:564px;padding-left:16px !important;padding-right:16px !important;">
                <table style="border-spacing:0;border-collapse:collapse;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;vertical-align:top;text-align:left;width:100%;">
                    <tr style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;vertical-align:top;text-align:left;">
                        <th style="color:#464646;font-family:Helvetica,Arial,sans-serif;font-weight:normal;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;Margin:0;text-align:left;font-size:16px;line-height:19px;">
                            <h2 class="lead text-center" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;margin-top:0;margin-right:0;margin-left:0;Margin:0;line-height:1;word-wrap:normal;font-family:Helvetica,Arial,sans-serif;margin-bottom:10px;Margin-bottom:10px;font-size:24px;font-weight:bold;text-align:center;color:white;">Please don't forget to register your SCANIFY.</h2>
                            <p class="lead text-center" style="font-family:Helvetica,Arial,sans-serif;font-weight:normal;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;margin-top:0;margin-right:0;margin-left:0;Margin:0;Margin-bottom:10px;text-align:center;font-size:24px;line-height:1.2;color:white;margin-bottom:0;">Now that you have it, what will you create?</p>
                        </th>
                    </tr>
                </table>
            </th>
        </tr>
        </tbody>
    </table>
</div>
<!-- main content end -->

<!-- new email footer start -->
<?php do_action( 'fuel3d_email_footer', $email_heading ); ?>
<!-- new email footer end -->
