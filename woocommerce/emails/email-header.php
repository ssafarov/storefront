<?php
/**
 * Email Header
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$bg 		= get_option( 'woocommerce_email_background_color' );
$body		= get_option( 'woocommerce_email_body_background_color' );
$base 		= get_option( 'woocommerce_email_base_color' );
$base_text 	= wc_light_or_dark( $base, '#202020', '#ffffff' );
$text 		= get_option( 'woocommerce_email_text_color' );

$bg_darker_10    = wc_hex_darker( $bg, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );

global $upload_dir;
$upload_dir = wp_upload_dir();

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline. !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
$wrapper_wo_bg = "
	width:100%;
	-webkit-text-size-adjust:none !important;
    -webkit-font-smoothing: antialiased;
    font-family: Arial, Times, serif;
	margin:0;
	padding: 0!important;
";
$wrapper = "
	background-color: " . esc_attr( $bg ) . ";
	width:100%;
	-webkit-text-size-adjust:none !important;
    -webkit-font-smoothing: antialiased;
    font-family: Arial, Times, serif;
	margin:0;
	padding: 0!important;
";
$template_container = "
	background-color: " . esc_attr( $body ) . ";
	border: 1px solid $bg_darker_10;
	border:none !important;
";
$template_header = "
	background-color: " . esc_attr( $body ) .";
	color: $base_text;
	border-bottom: 0;
	font-family:Arial;
	font-weight:bold;
	line-height:100%;
	vertical-align:middle;
";
$body_content = "
	background-color: " . esc_attr( $body ) . ";
";
$body_content_inner = "
	color: $text_lighter_20;
	font-family:Arial;
	font-size:14px;
	line-height:150%;
	text-align:left;
";
$header_content_h1 = "
    font-family: 'Lato', Arial, sans-serif;
    font-size:48px;
    line-height: 50px;
    color: #000000;
    font-weight:bold;
";

$table = "
    border-collapse: collapse !important;
    mso-table-lspace: 0pt;
    mso-table-rspace: 0pt;
";
$body_style = "
background-color:#ffffff;
font-family:'Lato', Arial,serif; color: #464646; margin:0; padding:0; min-width:100%; -webkit-text-size-adjust:none; -ms-text-size-adjust:none;
";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css /'>
        <title><?php echo get_bloginfo( 'name' ); ?></title>
    </head>
    <body style="<?php echo $body_style; ?>" marginwidth="0" marginheight="0" leftmargin="0" topmargin="0">

        <!--[if !mso]><!-- -->
        <img style="min-width:640px; display:block; margin:0; padding:0; width:640px; height:1px" class="mobileOff" src="http://s14.postimg.org/7139vfhzx/spacer.gif">
        <!--<![endif]-->

        <!-- Start Background -->
        <table style="<?php echo $wrapper_wo_bg; ?>" width="97%" cellpadding="0" cellspacing="0" border="0" data-spy="header">
            <tr>
                <td width="100%" valign="top" align="center">
                    <!-- Module Header -->
                    <!-- Start Wrapper  -->
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="<?php echo $bg; ?>">
                        <tr>
                            <td width="100%" valign="top" align="center">
                                <table width="640px" cellpadding="0" cellspacing="0" border="0" class="container">
                                    <tr>
                                        <td height="10px" style="font-size:10px; line-height:10px;"></td> <!-- Spacer -->
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <!-- Start Container  -->
                                            <table width="640" cellpadding="0" cellspacing="0" border="0" class="container">
                                                <tr>
                                                    <td width="300" class="mobile" align="left" style="font-size:12px; line-height:18px;">
                                                        <?php
                                                            if ( $img = get_option( 'woocommerce_email_header_image' ) ) {
                                                                echo '<img width="206" src="' . esc_url( $img ) . '" alt="' . get_bloginfo( 'name' ) . '" />';
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                            </table>
                                            <!-- Start Container  -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="10px" style="font-size:10px; line-height:10px;"></td> <!-- Spacer -->
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <!-- End Wrapper  -->

                    <!-- Start Wrapper  -->
                    <table style ="<?php echo $table; ?>" width="640" height="400" cellpadding="0" cellspacing="0" border="0" class="header">
                        <tr>
                            <td align="center">
                                <!-- Start Container  -->
                                <table style ="<?php echo $table; echo $template_container; ?>" border="0" cellpadding="0" cellspacing="0" width="600" id="template_container">
                        	        <tr>
                            	        <td align="center" valign="top">
                                            <!-- Header -->
                                            <table style ="<?php echo $table; ?>" border="0" cellpadding="0" cellspacing="0" width="640" id="template_header" style="<?php echo $template_header; ?>" >
                                                <tr>
                                                    <td>
                                                      <?php if($email_heading != 'Before renewal heading'):?>  <h1 style="<?php echo $header_content_h1; ?>"><?php echo $email_heading; ?></h1>
                                                        <?php endif;?>
