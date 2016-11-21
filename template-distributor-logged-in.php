<?php get_header(); ?>

<?php
	/**
	 * woocommerce_before_main_content hook
	 *
	 */
	do_action( 'woocommerce_before_main_content' );

	$scanner_price_heading = get_post_custom_values('product_price_heading', $scanner->id)[0];
	$target_price_heading = get_post_custom_values('product_price_heading', $target->id)[0];
	$scanner_subheading = get_post_custom_values('product_subheading', $scanner->id)[0];

    $current_user = wp_get_current_user();

	$total_discount = 0;
	$discounts = $info['discount'];
	foreach ( $discounts as $discount ) {
		$total_discount = $total_discount + $discount;
	}
?>

	<div class="well distributor-home clearfix">
		<?php wc_print_notices(); ?>
		<div id="distributorTabs" role="tabpanel" class="distributor-content clearfix">
			<ul class="distributor-tabs clearfix" role="">
				<?php if (!$current_user->has_cap('sub-distributor')||($current_user->has_cap('sub-distributor')&&$current_user->has_cap('can_view_my_distributor_tab'))) : ?>
				<li role="presentation" class="active">
					<a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">
						<i class="icon-info"></i>
						<span class="text"><?php _e('My distributor account', 'storefront'); ?></span>
					</a>
				</li>
				<?php
					endif;
					if (!$current_user->has_cap('sub-distributor')||($current_user->has_cap('sub-distributor')&&$current_user->has_cap('can_view_place_order_tab'))) :
				?>
				<li role="presentation">
					<a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">
						<i class="icon-cart"></i>
						<span class="text"><?php _e('Place an order', 'storefront'); ?></span>
					</a>
				</li>
				<?php
					endif;
					if (!$current_user->has_cap('sub-distributor')||($current_user->has_cap('sub-distributor')&&$current_user->has_cap('can_view_help_support_tab'))) :
				?>
				<li role="presentation">
					<a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">
						<i class="icon-bubble"></i>
						<span class="text"><?php _e('Help &amp; support', 'storefront'); ?></span>
					</a>
				</li>
				<?php
					endif;
					if (!$current_user->has_cap('sub-distributor')||($current_user->has_cap('sub-distributor')&&$current_user->has_cap('can_view_share_info_tab'))) :
				?>
				<li role="presentation">
					<a href="#tab4" aria-controls="tab4" role="tab" data-toggle="tab">
						<i class="icon-file"></i>
						<span class="text"><?php _e('Share information', 'storefront'); ?></span>
					</a>
				</li>
				<?php
					endif;
					if (!$current_user->has_cap('sub-distributor')||($current_user->has_cap('sub-distributor')&&$current_user->has_cap('can_view_media_assets_tab'))) :
				?>
				<li role="presentation">
					<a href="#tab5" aria-controls="tab5" role="tab" data-toggle="tab">
						<i class="icon-file-media"></i>
						<span class="text"><?php _e('Media &amp; assets', 'storefront'); ?></span>
					</a>
				</li>
				<?php
					endif;
                    if ($current_user->has_cap('distributor') && !$current_user->has_cap('sub-distributor')) :
                ?>
                    <li role="presentation">
                        <a href="#tab6" aria-controls="tab6" role="tab" data-toggle="tab">
                            <i class="icon-users-manage"></i>
                            <span class="text"><?php _e('Manage users', 'storefront'); ?></span>
                        </a>
                    </li>
                <?php
                    endif;
                ?>
			</ul>
			<div class="tab-content clearfix">
				<?php
					if (!$current_user->has_cap('sub-distributor')||($current_user->has_cap('sub-distributor')&&$current_user->has_cap('can_view_my_distributor_tab'))) :
				?>
				<div class="row tab-pane active" role="tabpanel" id="tab3">
					<div class="col-sm-12">
						<?php
							/**
							 * My distributor
							 */
						?>
						<?php
							// TODO: Should have a method that returns this info
							$full_name = $current_user->user_firstname . ' ' .$current_user->user_lastname;
						?>
						<div id="timeline">
							<div class="row distributor-profile">
								<div class="col-sm-12">
									<div class="distributor-info">
										<div class="row">
											<div class="col-sm-12 col-md-12 col-lg-8 clearfix">
												<div class="avatar-wrap">
													<?php echo get_avatar( $current_user->user_email, '64' ); ?>
												</div>
												<div class="name-wrap">
													<h1><?php echo $full_name; ?></h1>
													<div class="subtitle"><?php echo $info['company']; ?></div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12 col-lg-4 text-right">
												<div class="buttons-wrap clearfix">
													<a class="btn btn-secondary" href="<?php echo home_url('edit-profile'); ?>" title="Add distributor"><?php _e('Edit profile', 'storefront'); ?></a>
													<a class="btn btn-secondary" href="<?php echo wp_logout_url(); ?>" title="Log out"><?php _e('Log out', 'storefront'); ?></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="persona-piece col-sm-12 col-md-6 col-lg-4">
									<i class="icon-globe"></i>
									<span class="info"><?php _e('Distributor since', 'storefront'); echo ":&nbsp;".$info['since']; ?></span>
								</div>

								<div class="persona-piece col-sm-12 col-md-6 col-lg-8">
									<i class="icon-home"></i>
									<span class="info"><?php _e('Distributor in', 'storefront'); echo $info['country'] ? ":&nbsp;".$info['country'] : ':&nbsp;<a href="/edit-profile">'. __('(click here to choose your location)', 'storefront'). '</a>'; ?></span>
								</div>

								<div class="persona-piece col-sm-12 col-md-6 col-lg-4">
									<i class="icon-discount"></i>
									<span class="info"><?php _e('Discount rate', 'storefront'); echo ":&nbsp;".$total_discount; ?>%</span>
								</div>

								<div class="persona-piece col-sm-12 col-md-6 col-lg-8">
									<i class="icon-coins"></i>
									<span class="info"><?php _e('Preferred Currency', 'storefront'); echo ":&nbsp;".$info['default_currency']; ?></span>
								</div>
							</div>


							<div class="row spacer">
								<div class="col-sm-12 col-md-4">
									<div class="distributor-details">
										<h3><?php _e('Delivery Address', 'storefront'); ?></h3>
										<?php echo $info['shipping_address']; ?>

										<h3><?php _e('Billing Address', 'storefront'); ?></h3>
										<?php echo $info['billing_address']; ?>

										<h3><?php _e('Email Address', 'storefront'); ?></h3>
										<p><?php echo $current_user->user_email ?></p>
									</div>
								</div>
								<div class="col-sm-12 col-md-8">
									<!-- @start accordion -->
									<div class="panel-group accordion" id="accordion-timeline" role="tablist" aria-multiselectable="true">
										<?=$info['events']->toHtml()?>
									</div><!-- @end .accordion -->
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
					endif;
					if (!$current_user->has_cap('sub-distributor')||($current_user->has_cap('sub-distributor')&&$current_user->has_cap('can_view_place_order_tab'))) :
				?>
				<div class="row tab-pane" role="tabpanel" id="tab1">
					<div class="col-xs-12">
						<?php
							/**
							 * Place an order
							 */
						?>
						<div class="row">
							<div class="col-sm-12 col-md-7">
								<figure class="product-image">
									<img src="<?php echo get_template_directory_uri(); ?>/images/distributor/scanify.jpg" class="img-responsive">
								</figure>
							</div>
							<div class="col-sm-12 col-md-5">
								<div class="product-info">
									<form method="post">
										<h1><?php echo _x($scanner->post->post_title, 'fuel3d'); ?></h1>
										<?php
											if ( $scanner_subheading ) {
												printf( _x('<h6>%s</h6>', 'fuel3d'), $scanner_subheading );
											}
										?>

										<hr>

										<div class="row">
											<div class="col-xs-12 col-sm-7">
												<?php
													if ( $scanner_price_heading ) {
														printf( _x('<h6 class="details-subtitle">%s</h6>', 'fuel3d'), $scanner_price_heading );
													}
												?>

												<div class="price">
													<sup class="currency"><?php echo $info['currency_sign']; ?></sup>
													<span class="value"><?php echo number_format($scanner->get_price() * (100 - $total_discount) / 100, 2); ?></span>
												</div>
												<div class="price-alt">
													<p>
														<?php _e( 'MSRP:', 'storefront' ); ?>
														<sup><?php echo $info['currency_sign']; ?></sup><?php echo number_format($scanner->get_price()); ?>
													</p>
													<p>
														<?php _e( 'SKU:', 'storefront' ); ?>
														<?php echo $scanner->get_sku(); ?>
													</p>
												</div>
											</div>
											<div class="col-xs-12 col-sm-5 text-right">
												<label>
													<input class="form-control input-primary input-quantity" type="number" name="scanner_quantity" id="scanner_quantity" value="1" step="1" min="1">
												</label>
											</div>
										</div>

										<hr>

										<div class="row">
											<div class="col-xs-12 col-sm-7">
												<?php
													if ( $target_price_heading ) {
														printf( _x('<h6 class="details-subtitle">%s</h6>', 'fuel3d'), $target_price_heading );
													}
												?>

												<div class="price">
													<sup class="currency"><?php echo $info['currency_sign']; ?></sup>
													<span class="value"><?php echo number_format($target->get_price() * (100 - $total_discount) / 100, 2); ?></span>

												</div>

												<div class="price-alt">
													<p>
														<?php _e( 'MSRP:', 'storefront' ); ?>
														<sup><?php echo $info['currency_sign']; ?></sup><?php echo number_format($target->get_price()); ?>
													</p>
													<p>
														<?php _e( 'SKU:', 'storefront' ); ?>
														<?php echo $target->get_sku(); ?>
													</p>
												</div>

											</div>
											<div class="col-xs-12 col-sm-5 text-right">
												<label>
													<input class="form-control input-primary input-quantity" type="number" name="target_quantity" id="target_quantity" value="1" step="1" min="0" onkeyup="this.value=this.value.replace(/[^\d]/,'')">
												</label>
											</div>
										</div>

										<hr>

										<div class="total-wrap">
											<h6 class="details-subtitle"><?php _e( 'Total', 'storefront' ); ?></h6>
											<div id="total" class="price">
												<sup class="currency"><?php echo $info['currency_sign']; ?></sup>
												<span class="value">20.00</span>
											</div>
										</div>
										<div class="discount-wrap">
											<?php _e( 'Includes discount of', 'storefront' ); ?>
											<?php echo  $total_discount; ?>% (<span id="discount"><?php echo $info['currency_sign']; ?><span class="value">20.00</span></span>)
										</div>
										<div>
											<a class="btn btn-secondary btn-block" href="#" onclick="jQuery(this).closest('form').submit(); return false;"><?php _e( 'Order', 'storefront' ) ?></a>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
					endif;
					if (!$current_user->has_cap('sub-distributor')||($current_user->has_cap('sub-distributor')&&$current_user->has_cap('can_view_help_support_tab'))) :
				?>
				<div class="row tab-pane" role="tabpanel" id="tab2">
					<div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2" id="help-support">
						<?php
							/**
							 * Help & Support Tab
							 */
						?>

						<header>
							<h2><?php _e( 'Help and support', 'storefront' ); ?></h2>
						</header>
						<p><?php _e( 'Use this section if you require any help and technical support regarding Scanify. If you leave us a message or ask us a question then one of our team will be in contact with you shortly.', 'storefront'); ?></p>


						<?php echo do_shortcode('[contact-form-7 id="6706" title="Distributor Help & Support"]');; ?>
					</div>
				</div>
				<?php
					endif;
					if (!$current_user->has_cap('sub-distributor')||($current_user->has_cap('sub-distributor')&&$current_user->has_cap('can_view_share_info_tab'))) :
				?>
				<div class="row tab-pane" role="tabpanel" id="tab4">
					<div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2" id="share-information">
						<?php
							/**
							 * Share Information
							 */
						?>
						<script src="<?php echo get_template_directory_uri(); ?>/js/ajaxfileupload.js"></script>


							<header>
								<h2><?php _e('Upload your sales information', 'storefront'); ?></h2>
							</header>

							<div class="row">
								<div class="col-sm-12">
									<p><?php _e( 'We are always keen to find out how you are getting on selling scanify. Therefore please feel free to send us any information or figures that will allow us to understand and support. We are happy to see all sorts, figures, forms, or pictures from customers, or download our CSV file with the template that we will most benefit from.', 'storefront' ); ?></p>
								</div>

								<div class="col-sm-12">
									<div class="actions">
										<a href="<?php echo get_template_directory_uri(); ?>/assets/Distributor-Sales.csv" class="btn btn-primary"><?php _e('Download CSV', 'storefront'); ?></a>

										<!-- <a id="upload-now" class="btn btn-primary"><?php _e('Upload Files', 'storefront'); ?></a> -->
									</div>
								</div>
							</div>

							<form class="dropzone" id="fuel-drop" method="post" enctype="multipart/form-data" action="<?php echo plugins_url('fuel3d-distributors/upload-sales-csv.php'); ?>">
								<input type="file" id="csv-upload" class="hidden" name="csv">
								<div class="dz-message" data-dz-message>
									<i class="icon-upload"></i>
									<strong><?php _e( 'Drag &amp; drop files here', 'storefront'); ?></strong>
								</div>
							</form>

							<br><br>
							<header>
								<h2><?php _e('Quick feedback', 'storefront'); ?></h2>
							</header>

							<?php echo do_shortcode('[contact-form-7 id="6707" title="Distributor Share Information"]'); ?>


						<script type="text/javascript" src="https://app.box.com/js/static/select.js"></script>

						<script>
							(function( $ ) {
								var url = "<?php echo plugins_url('fuel3d-distributors/upload-sales-csv.php'); ?>";


								// The camelized version of the ID of the form element
								Dropzone.options.fuelDrop = {

									url: url,
									acceptedFiles: '.csv',
									uploadMultiple: false,
									maxFiles: 1,

									// The setting up of the dropzone
									init: function() {
										var fuelDrop = this;

										this.on('complete', function( files ) {
											var self = this;

											// Clear files q
											self.removeFile(files);

										});

										this.on('success', function( files, response ) {
											if ( response.success ) {
												notification('success', __('Your sales report has been uploaded. Thank you!', 'storefront'));
											} else {
												notification('danger', __('There was an error uploading your sales report. Please try again later', 'storefront'));
											}
										});

										this.on('error', function( files, response ) {
											notification('danger', __('There was an error uploading your sales report. Please try again later', 'storefront'));
										});
									}
								}

								function notification( alert_type, alert_message ) {
									if ( $('.alert alert-' + alert_type).length == 0 ) {
										$('<div class="alert alert-' + alert_type + ' alert-dismissible" role="alert">' +
											'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
											alert_message +
										'</div>').prependTo('#tab4');
									};
								}

							})( jQuery );
						</script>
					</div>
				</div>
				<?php
					endif;
					if (!$current_user->has_cap('sub-distributor')||($current_user->has_cap('sub-distributor')&&$current_user->has_cap('can_view_media_assets_tab'))) :
				?>
				<div class="row tab-pane" role="tabpanel" id="tab5">
					<div class="col-sm-12 col-md-10 col-md-offset-1">
						<?php
						/**
						 * Media & assets
						 */
						?>

						<header><h2><?php _e('Distributor Downloads', 'storefront'); ?></h2></header>
						<p><?php _e('This section is where you should find all the information you will need to sell Scanify and support our users. This site will be regularly updated as new material becomes available and you will be notified when this happens.', 'storefront'); ?></p>

						<center id="loading_assets">
							<img src="<?php echo get_template_directory_uri() ?>/images/ajax-loader.gif"
							     style="display:inline;">
							<?php _e('Please wait, loading content', 'storefront') ?>
						</center>

						<script>
							(function ($) {
								$(document).ready(function () {
									$.get('<?php echo plugins_url('fuel3d-distributors/get-media-downloads.php'); ?>', function (r) {
										var counter = 1;

										for (var i in r) {
											if (!r[i].label || !r[i].items) continue;

											var section =
												'<div class="panel panel-default">' +
												'<div class="panel-heading" role="tab">' +
												'<h3>' +
												'<a data-toggle="collapse" data-parent="#assets" href="#dd-tab' + counter + '" aria-expanded="' + (counter == 1 ? 'true' : 'false') + '" aria-controls="dd-tab' + counter + '"' + (counter == 1 ? '' : ' class="collapsed"') + '>' +
												r[i].label +
												'<span class="arrow">' +
												'<i class="icon-arrow-down"></i>' +
												'<i class="icon-arrow-up"></i>' +
												'</span>' +
												'</a>' +
												'</h3>' +
												'</div>' +
												'<div id="dd-tab' + counter + '" class="' + (counter == 1 ? 'panel-collapse in ' : '') + 'collapse" role="tabpanel">' +
												'<div class="panel-body">' +
												'<ul class="list-unstyled row">';
											for (var j in r[i].items) {
												if (!r[i].items[j].label || !r[i].items[j].url) continue;
												section +=
													'<li class="col-sm-6 col-md-4">' +
													'<a class="distributor-asset" href="' + r[i].items[j].url + '"><i class="icon-file-pdf"></i>' + r[i].items[j].label + '</a>' +
													'</li>';
											}
											section +=
												'</ul>' +
												'</div>' +
												'</div>' +
												'</div>';

											$('#assets').append(section);
											counter++;
										}

										$('#assets').removeClass('hidden');
										$('#loading_assets').remove();
									});
								});
							})(jQuery);
						</script>

						<!-- @start accordion -->
						<div class="panel-group accordion hidden" id="assets" role="tablist"
						     aria-multiselectable="true"></div>
						<!-- @end .accordion -->
					</div>
				</div>
				<?php
					endif;
                    if ($current_user->has_cap('distributor') && !$current_user->has_cap('sub-distributor')) :
                ?>
                    <div class="row tab-pane" role="tabpanel" id="tab6">
                        <div class="col-sm-12">
                            <?php
                                $full_name = $current_user->user_firstname . ' ' .$current_user->user_lastname;
                            ?>
                            <div class="row">
                                <div class="row distributor-profile">
                                    <div class="col-sm-12">
                                        <div class="distributor-info">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12 col-lg-9 clearfix">
                                                    <div class="avatar-wrap">
                                                        <?php echo get_avatar( $current_user->user_email, '64' ); ?>
                                                    </div>
                                                    <div class="name-wrap">
                                                        <h1><?php echo $full_name; ?></h1>
                                                        <div class="subtitle"><?php echo $info['company']; ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-3 text-right">
                                                    <div class="buttons-wrap clearfix">
	                                                    <?php if (!$current_user->has_cap('sub-distributor')){ ?>
                                                            <a class="btn btn-secondary" href="/add-distributor" title="Add user"><?php _e('Add user', 'storefront'); ?></a>
	                                                    <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row spacer">
                                <header>
                                    <h2><?php _e('Users', 'storefront'); ?></h2>
                                </header>
                                <div class="row inner">
                                    <table>
                                        <thead>
                                            <th><?php _e( 'ID', 'storefront' ); ?></th>
                                            <th><?php _e( 'Login', 'storefront' ); ?></th>
                                            <th><?php _e( 'Email', 'storefront' ); ?></th>
                                            <th><?php _e( 'First name', 'storefront' ); ?></th>
                                            <th><?php _e( 'Last name', 'storefront' ); ?></th>
                                            <th><?php _e( 'Capabilities', 'storefront' ); ?></th>
                                            <th><?php _e( 'Actions', 'storefront' ); ?></th>
                                        </thead>
		                                <?php
	                                        if (count ($info['sub_distributors'])>0) {
		                                        foreach ($info['sub_distributors'] as $subDistributor) {
			                                        $sdCaps = '';
			                                        if ($subDistributor->has_cap('can_view_my_distributor_tab'))
				                                        $sdCaps .= __('My distributor', 'storefront').'<br/>';
			                                        if ($subDistributor->has_cap('can_view_place_order_tab'))
				                                        $sdCaps .= __('Place order', 'storefront').'<br/>';
			                                        if ($subDistributor->has_cap('can_view_help_support_tab'))
				                                        $sdCaps .= __('Help & Support', 'storefront').'<br/>';
			                                        if ($subDistributor->has_cap('can_view_share_info_tab'))
				                                        $sdCaps .= __('Share information', 'storefront').'<br/>';
			                                        if ($subDistributor->has_cap('can_view_media_assets_tab'))
				                                        $sdCaps .= __('Media & assets', 'storefront');

			                                        echo "<tr>
			                                                <td>".$subDistributor->ID."</td>
				                                            <td>".$subDistributor->user_login."</td>
				                                            <td>".$subDistributor->user_email."</td>
				                                            <td>".$subDistributor->user_firstname."</td>
				                                            <td>".$subDistributor->user_lastname."</td>
				                                            <td>".$sdCaps."</td>
				                                            <td><a href='#' onclick='editDistributor($subDistributor->ID);'>". __('edit', 'storefront')."</a>/<a href='#' onclick='deleteDistributor($subDistributor->ID);'>". __('delete', 'storefront')."</a></td>
				                                        </tr>";
		                                        }
	                                        }
										?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    // Display notices (if any)
                    wc_print_notices();
                    ?>
                   <div style="display:none">
	                   <div id="confirmDeleteDialog"></div>
                   </div>
                <?php
                    endif;
                ?>
			</div>
		</div>
	</div>
<?php
	/**
	 * woocommerce_after_main_content hook
	 *
	 */
	do_action( 'woocommerce_after_main_content' );
?>
<script>
	(function($) {
		function number_format(number, decimals, dec_point, thousands_sep) {
			// @source: http://phpjs.org/functions/number_format/

			number = (number + '').replace(/[^0-9+\-Ee.]/g, '');

			var n = !isFinite(+number) ? 0 : +number,
				prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
				sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
				dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
				s = '',
				toFixedFix = function(n, prec) {
					var k = Math.pow(10, prec);
					return '' + (Math.round(n * k) / k).toFixed(prec);
				};

			// Fix for IE parseFloat(0.55).toFixed(0) = 0;
			s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');

			if (s[0].length > 3) {
				s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
			}

			if ((s[1] || '').length < prec) {
				s[1] = s[1] || '';
				s[1] += new Array(prec - s[1].length + 1).join('0');
			}

			return s.join(dec);
		}

		function updatePrice() {
			var scanner_price = <?php
									if ($scanner->regular_price)
									{
										echo $scanner->regular_price;
									}
									else
									{
										echo 0;
									} ?>,
				target_price = <?php
									if ($target->regular_price)
									{
										echo $target->regular_price;
									}
									else
									{
										echo 0;
									} ?>,
				scanners = $.isNumeric($('#scanner_quantity').val()) ? $('#scanner_quantity').val() : '1',
				targets = $.isNumeric($('#target_quantity').val()) ? $('#target_quantity').val() : '1',
				discount_percent = <?php echo $total_discount; ?>,
				total = scanners * scanner_price + targets * target_price;
				discounted = parseInt(total * (100 - discount_percent)) / 100,
				discount = parseInt((total - discounted) * 100) / 100;


			if ( scanners != $('#scanner_quantity').val() ) {
				// No hacky-hacky
				$('#scanner_quantity').val(scanners);
			};

			if ( targets != $('#target_quantity').val() ) {
				$('#target_quantity').val(targets);
			};

			$('#total .value').html(number_format(discounted, 2));
			$('#discount .value').html(number_format(discount, 2));
		}

		$('#scanner_quantity, #target_quantity').change(function() {
			updatePrice();
		}).click(function() {
			updatePrice();
		}).keyup(function() {
			updatePrice();
		}).mousewheel(function() {
			setTimeout(function() { updatePrice() }, 10);
		});

		$(document).ready(function() {
			updatePrice();

			// Javascript to enable link to tab
			var url = document.location.toString();
			if (url.match('#')) {
				$('.distributor-tabs a[href=#'+url.split('#')[1]+']').tab('show') ;
			}
		});
	})(jQuery);

	function editDistributor(distribID) {
		var form = document.createElement("form");
		var input1 = document.createElement("input");
		var input2 = document.createElement("input");

		form.action = "/add-distributor-page/";
		form.method = "post";

		input1.name = "action";
		input1.type = "hidden";
		input1.value = "edit-distributor";
		form.appendChild(input1);
		input2.name = "distributor-id";
		input2.type = "hidden";
		input2.value = distribID;
		form.appendChild(input2);
		document.body.appendChild(form);
		form.submit();
		return false;
	}

	function doDeleteDistributor(distribID) {
		var form = document.createElement("form");
		var input1 = document.createElement("input");
		var input2 = document.createElement("input");

		form.action = "/add-distributor-page/";
		form.method = "post";

		input1.name = "action";
		input1.type = "hidden";
		input1.value = "delete-distributor";
		form.appendChild(input1);
		input2.name = "distributor-id";
		input2.type = "hidden";
		input2.value = distribID;
		form.appendChild(input2);
		document.body.appendChild(form);
		form.submit();
		return false;
	}


	function deleteDistributor (distribID) {
		var dialog = jQuery( "#confirmDeleteDialog" );
		var header = "<h3><?php _e("Please confirm user deletion"); ?></h3>";
		var content = "<p><?php _e("Are you sure you want to delete user with id #");?>" + distribID + "?</p>";
		var decision = "<a class='btn btn-secondary' href='#' onclick='doDeleteDistributor(" + distribID + ");' title='<?php _e("Yes");?>'><?php _e("Yes");?></a>&nbsp;<a class='btn btn-primary' href='#' onclick='cancelDistrDelete();' title='<?php _e("No"); ?>'><?php _e("No"); ?></a>";

		dialog.html(header + content + decision);

		jQuery.fancybox({
			href: '#confirmDeleteDialog',
			modal:true
		});

		return false;
	}

	function cancelDistrDelete () {
		parent.jQuery.fancybox.close();
	}


</script>

<?php get_footer(); ?>
