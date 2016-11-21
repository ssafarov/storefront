<?php
/**
 * Created by PhpStorm.
 * User: s.safarov
 * Date: 13.05.2016
 * Time: 12:50
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !$needToClarifyRegion ) {
	$_SESSION['region_clarification'] = 0;
	return;
}
?>

<style>
	.clarify-region-overlay {
		position: fixed;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		z-index: 9999;
		overflow: hidden;
		background-color: #000;
		opacity: 0.7;
	}
	.clarify-region-popup {
		margin-left: -408px !important;
		padding: 15px;
		background-color: #fff;
		border-radius: 10px;
		width: 816px !important;
		left: 50% !important;

		height: 400px;
		position: fixed;
		z-index: 10000;
		display: block;
	}
	.rounded {
		border-radius: 4px;
		padding: 4px;
	}
	.us {
		position: absolute;
		top: 0;
		left: 29px;
		width: 194px;
		height: 182px;
	}
	.eu {
		position: absolute;
		top: 27px;
		left: 200px;
		width: 106px;
		height: 128px;
	}
	.sa {
		position: absolute;
		top: 171px;
		left: 127px;
		width: 62px;
		height: 100px;
	}
	.af {
		position: absolute;
		top: 131px;
		left: 198px;
		width: 95px;
		height: 100px;
	}
	.as {
		position: absolute;
		top: 14px;
		left: 280px;
		width: 168px;
		height: 250px;
	}
	.close-btn {
		top: 0;
		right: 0;
		display: block;
		width: 32px;
		height: 32px;
		cursor: pointer;
		float: right;
	}
	.region-selector {
		padding: 5px 30px;
		margin-top: 10px;
	}
	.hover-region, .hover-region-row {
		cursor: pointer;
	}
	.thin{
		font-weight: 200;
	}
	.snotice{
		margin-left: 20px;
	}

	@media (max-width: 767px) {
		.clarify-region-popup {
			margin-left: -220px !important;
			padding: 15px;
			background-color: #fff;
			border-radius: 10px;
			width: 440px !important;
			left: 50% !important;
			height: 375px;
			position: fixed;
			z-index: 10000;
			display: block;
		}
		.centered{
			text-align: center;
			padding: 0 40px;
		}
		.centered img{
			margin: 0 auto;
		}
	}
</style>

<div class="clarify-region-popup">
	<div class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="close-btn">
					<img src="<?php echo get_template_directory_uri(); ?>/images/close.png" alt="<?php _e('Close', 'storefront'); ?>">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="hidden-xs col-sm-7">
				<div class="row">
					<div class="col-xs-12">
						<img class="world" src="<?php echo get_template_directory_uri(); ?>/images/worldn.png" alt="<?php _e('Select your shipping region', 'storefront'); ?>" />
						<div class="hover-region us">
							<img style="display: none;" src="<?php echo get_template_directory_uri(); ?>/images/usa.png" alt="<?php _e('USA is your shipping region', 'storefront'); ?>" />
						</div>
						<div class="hover-region eu">
							<img style="display: none;" src="<?php echo get_template_directory_uri(); ?>/images/eua.png" alt="<?php _e('EU is your shipping region', 'storefront'); ?>" />
						</div>
						<div class="hover-region-row sa">
							<img style="display: none;" src="<?php echo get_template_directory_uri(); ?>/images/saa.png" alt="<?php _e('Rest of World is your shipping region', 'storefront'); ?>" />
						</div>
						<div class="hover-region-row af">
							<img style="display: none;" src="<?php echo get_template_directory_uri(); ?>/images/afa.png" alt="<?php _e('Rest of World is your shipping region', 'storefront'); ?>" />
						</div>
						<div class="hover-region-row as">
							<img style="display: none;" src="<?php echo get_template_directory_uri(); ?>/images/asa.png" alt="<?php _e('Rest of World is your shipping region', 'storefront'); ?>" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<?php if ($_SESSION['region_clarification'] === 1 ) : ?>
							<p class='snotice'><?php _e( 'Your chosen region doesn\'t match your current location. To continue, please close this window', 'storefront' ) ; ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="col-sm-4 centered">
				<img class="scanify-logo" src="<?php echo get_template_directory_uri(); ?>/images/logow.png" alt="<?php _e('Fuel3D', 'storefront'); ?>" />
				<h4><?php _e('Select your region', 'storefront'); ?></h4>
				<p class="visible-xs col-xs-12 thin"><?php _e('Please select your shipping region below to get started', 'storefront'); ?></p>
				<p class="visible-sm visible-md visible-lg thin"><?php _e('Please select your shipping region from the map or below to get started', 'storefront'); ?></p>
				<form>
					<select name="region" class="rounded">
						<option value=""><?php _e('Select your region', 'storefront'); ?></option>
						<option value="us"><?php _e('North America', 'storefront'); ?></option>
						<option value="eu"><?php _e('Europe', 'storefront'); ?></option>
						<option value="rw"><?php _e('Rest of World', 'storefront'); ?></option>
					</select>
					<a href="#" class="btn btn-primary region-selector"><?php _e('Go', 'storefront'); ?></a>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="clarify-region-overlay"></div>

<?php
$_SESSION['region_clarification'] = 1;
?>
