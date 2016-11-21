<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */
?>
<style>
	#widget-contact {
		position: relative;
	}

	.widget-contact__body .row-inner {
		overflow: hidden;
	}

	#widget-contact .success-email {
		display: none;
		position: absolute;
		left: 50%;
		margin-left: -100px;
		width: 200px;
		top: 45px;
	}

	#widget-contact .success-email img {
		position: relative;
		left: 50%;
		margin-left: -33px;
	}

	#widget-contact .success-email .text-line-1 {
		text-align: center;
		font-weight: bold;
		margin-top: 15px;
	}

	#widget-contact .success-email .text-line-2 {
		text-align: center;
		margin-top: 15px;
	}
</style>

	<?php do_action( 'storefront_before_footer' ); ?>

	<?php
		if ( ! is_page('distributor-portal') ) {
			get_template_part('page-templates/partials/_clients');
		}
	?>

	<?php
		// if ( is_front_page() ) {
		// 	get_template_part('page-templates/partials/_scanify-box');
		// }
	?>

	<footer class="site-footer" role="contentinfo">

		<div class="site-header-nav">
			<div class="container">
				<div class="col-md-offset-2 col-md-8 col-xs-12">
					<?php echo do_shortcode('[woochimp_form]'); ?>
				</div>
			</div>
		</div>

		<header class="container">
			<div class="row">
				<div class="col-xs-12">
					<a class="big clearfix" href="/careers/"><span class="hire-high"><?php _e( 'We are hiring', 'storefront'  ); ?></span><span class="hidden-xs hidden-sm"> - </span><span class="hire-info"><?php _e( 'visit our careers page to have a look', 'storefront' ); ?></span></a>
				</div>
			</div>
		</header>

		<div class="container">
			<?php
			/**
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit - 20
			 */
			do_action( 'storefront_footer' ); ?>

			<div class="footer-text"><?php
				_e( '&copy;&nbsp;Copyright', 'storefront');
				echo (' ' . date('Y') . ' ');
				_e(
					'Fuel 3D Technologies Limited. All rights reserved. Fuel3D®, SCANIFY® and PELLEGO® are registered trademarks in the name of Fuel 3D Technologies Limited. All other trademarks are the property of their respective owners. All Fuel 3D Technologies Limited products and services are subject to continuous development. We reserve the right to alter technical specifications without prior notice.',
					'storefront'
				);
	            if (isUsaShop()) {
		            echo (" ");
		            _e( 'Fuel 3D, Inc. is a wholly-owned subsidiary of Fuel 3D Technologies Limited.
                ', 'storefront');
	            }
	            ?>
            </div>
		</div><!-- .col-full -->
	</footer><!-- #colophon -->


	<!-- Contact Widget -->
<?php
    $chatCode = '2zmZbsaZmr7kpZlvU2hx06tW1unre35z'; // live
?>
	<div class="widget-contact">
		<header class="widget-contact__header">
			<h6><?php _e( 'Contact Us', 'storefront' ); ?></h6>
			<i class="icon-plus right"></i>
		</header>
        <div class="widget-contact__body">
            <div class="row-inner">
                <div class="switch-mode">
                    <div class="switcher mode-chat col-lg-6 col-md-6 col-xs-6"><a href="#"><?php _e( 'Chat with us', 'storefront' ); ?></a></div>
                    <div class="switcher mode-email active col-lg-6 col-md-6 col-xs-6"><a href="#"><?php _e( 'Email us', 'storefront' ); ?></a></div>
                </div>
            </div>
            <div id="widget-chat" class="sslive hidden">
				<?php if (!isset($_SERVER['REMOTE_ADDR']) || $_SERVER['REMOTE_ADDR'] != '127.0.0.1'): ?>
                <iframe id="zopim" src="https://v2.zopim.com/widget/livechat.html?key=<?= $chatCode; ?>" scrolling="no" width="100%" height="507px" frameBorder="0"></iframe>
				<?php endif; ?>
            </div>
            <div id="widget-contact" class="sslive ">
				<div  class="success-email">
					<img src="<?php echo home_url('/')."img/msent.png";?>">
					<div class="text-line-1"><?php _e( 'Thanks for your message', 'storefront' ); ?></div>
					<div class="text-line-2"><?php _e( "We'll be in touch shortly", 'storefront' ); ?></div>
				</div>
				<div class="content">
					<select  id="choiceZohoForm" name="your-recipient" class="wpcf7-form-control wpcf7-select" aria-invalid="false">
						<option selected value="generalQuestion">General question</option>
						<option value="enquiryScOrStudio">I have an enquiry about SCANIFY or Studio </option>
						<option value="discussAbout3dApplication">I have a 3D application to discuss	</option>
						<option value="interestedInDistributingOrReselling">I am interested in distributing or reselling</option>
						<option value="media">Media</option>
					</select>
					<div id='crmWebToEntityForm'>
						<META HTTP-EQUIV='content-type' CONTENT='text/html;charset=UTF-8'>
						<form action='https://crm.zoho.com/crm/WebToLeadForm' name=WebToLeads1923966000000602001
							  method='POST' onSubmit='javascript:document.charset="UTF-8"; return checkMandatory()'
							  accept-charset='UTF-8'><input type='text' style='display:none;' name='xnQsjsdp'
															value='c0403015b07669e3f0e97ebf088d119da0c9d4374049a724a1bb05a8a5ecca11'/><input
								type='hidden' name='zc_gad' id='zc_gad' value=''/><input type='text'
																						 style='display:none;'
																						 name='xmIwtLD'
																						 value='37fb372d1963ae15266f21ee491d59c5b63db7848c2912c344bbf39283de8df0'/><input
								type='text' style='display:none;' name='actionType' value='TGVhZHM='/><input type='text'
																											 style='display:none;'
																											 name='returnURL'
																											 value='https&#x3a;&#x2f;&#x2f;www.fuel-3d.com&#x2f;form-submission-successful&#x2f;'/>
							<div class="row hide">
								<div class="col-xs-12">
									<div class="form-group">
										<label>Description</label><textarea id="zohoDesc" name='Description' maxlength='1000style='
																			width:250;'>General enquiry</textarea>
									</div>
								</div>
							</div>
							<div class="row hide">
								<div class="col-xs-12">
									<div class="form-group">
										<label>Lead Source</label><select style='width:250px;' name='Lead Source'>
											<option selected value='Website'>Website</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row hide">
								<div class="col-xs-12">
									<div class="form-group">
										<label>Lead Source
											Type</label><select style='width:250px;' name='LEADCF48'>
											<option selected value='Website'>Website</option>
										</select>
									</div>
								</div>
							</div>

							<div class="row ">
								<div class="col-xs-12">
									<div class="form-group">
										<label>First name</label>
											<span class="wpcf7-form-control-wrap"><input
													type='text' maxlength='40' name='First Name'/></span>
									</div>
								</div>
							</div>
							<div class="row ">
								<div class="col-xs-12">
									<div class="form-group">
										<label>Last name</label>
											<span class="wpcf7-form-control-wrap"><input
													type='text' maxlength='40'
													name='Last Name'/></span>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-xs-12">
									<div class="form-group">
										<label>Country</label><br>
										<span class="wpcf7-form-control-wrap you-country"><select name='LEADCF5'>
											<option value='-None-'>-None-</option>
											<option value='United&#x20;States'>United States</option>
											<option value='Albania'>Albania</option>
											<option value='Algeria'>Algeria</option>
											<option value='American&#x20;Samoa'>American Samoa</option>
											<option value='Argentina'>Argentina</option>
											<option value='Australia'>Australia</option>
											<option value='Austria'>Austria</option>
											<option value='Azerbaijan'>Azerbaijan</option>
											<option value='Bahrain'>Bahrain</option>
											<option value='Bangladesh'>Bangladesh</option>
											<option value='Belgium'>Belgium</option>
											<option value='Belguim'>Belguim</option>
											<option value='Bermuda'>Bermuda</option>
											<option value='Bolivia'>Bolivia</option>
											<option value='Brazil'>Brazil</option>
											<option value='Bulgaria'>Bulgaria</option>
											<option value='Canada'>Canada</option>
											<option value='Chile'>Chile</option>
											<option value='China'>China</option>
											<option value='Colombia'>Colombia</option>
											<option value='Costa&#x20;Rica'>Costa Rica</option>
											<option value='Cote&#x20;d &#x27;Ivoire'>Cote d &#x27;Ivoire</option>
											<option value='Croatia'>Croatia</option>
											<option value='Cyprus'>Cyprus</option>
											<option value='Czech&#x20;Republic'>Czech Republic</option>
											<option value='Denmark'>Denmark</option>
											<option value='Ecuador'>Ecuador</option>
											<option value='Egypt'>Egypt</option>
											<option value='El&#x20;Salvador'>El Salvador</option>
											<option value='Estonia'>Estonia</option>
											<option value='Europe'>Europe</option>
											<option value='Finland'>Finland</option>
											<option value='France'>France</option>
											<option value='Germany'>Germany</option>
											<option value='Greece'>Greece</option>
											<option value='Guatemala'>Guatemala</option>
											<option value='Haiti'>Haiti</option>
											<option value='Hong&#x20;Kong'>Hong Kong</option>
											<option value='Hungary'>Hungary</option>
											<option value='India'>India</option>
											<option value='Indonesia'>Indonesia</option>
											<option value='Iran'>Iran</option>
											<option value='Ireland'>Ireland</option>
											<option value='Israel'>Israel</option>
											<option value='Italy'>Italy</option>
											<option value='Japan'>Japan</option>
											<option value='Kazakhstan'>Kazakhstan</option>
											<option value='Kenya'>Kenya</option>
											<option value='Korea,&#x20;South'>Korea, South</option>
											<option value='Korea,&#x20;Republic&#x20;of'>Korea, Republic of</option>
											<option value='Latvia'>Latvia</option>
											<option value='Lebanon'>Lebanon</option>
											<option value='Liechtenstein'>Liechtenstein</option>
											<option value='Lithuania'>Lithuania</option>
											<option value='Luxembourg'>Luxembourg</option>
											<option value='Macau'>Macau</option>
											<option value='Malaysia'>Malaysia</option>
											<option value='Malta'>Malta</option>
											<option value='Mauritius'>Mauritius</option>
											<option value='Mexico'>Mexico</option>
											<option value='Micronesia'>Micronesia</option>
											<option value='Morocco'>Morocco</option>
											<option value='Netherlands'>Netherlands</option>
											<option value='New&#x20;Zealand'>New Zealand</option>
											<option value='Nigeria'>Nigeria</option>
											<option value='Norway'>Norway</option>
											<option value='Pakistan'>Pakistan</option>
											<option value='Palestine'>Palestine</option>
											<option value='Panama'>Panama</option>
											<option value='Paraguay'>Paraguay</option>
											<option value='Peru'>Peru</option>
											<option value='Philippines'>Philippines</option>
											<option value='Poland'>Poland</option>
											<option value='Portugal'>Portugal</option>
											<option value='Puerto&#x20;Rico'>Puerto Rico</option>
											<option value='Qatar'>Qatar</option>
											<option value='Romania'>Romania</option>
											<option value='Russia'>Russia</option>
											<option value='Samoa'>Samoa</option>
											<option value='Saudi&#x20;Arabia'>Saudi Arabia</option>
											<option value='Scotland'>Scotland</option>
											<option value='Serbia'>Serbia</option>
											<option value='Singapore'>Singapore</option>
											<option value='Slovakia'>Slovakia</option>
											<option value='Slovenia'>Slovenia</option>
											<option value='South&#x20;Africa'>South Africa</option>
											<option value='Spain'>Spain</option>
											<option value='Sri&#x20;Lanka'>Sri Lanka</option>
											<option value='Sweden'>Sweden</option>
											<option value='Switzerland'>Switzerland</option>
											<option value='Taiwan'>Taiwan</option>
											<option value='Tajikistan'>Tajikistan</option>
											<option value='Tanzania'>Tanzania</option>
											<option value='Thailand'>Thailand</option>
											<option value='Trinidad&#x20;and&#x20;Tobago'>Trinidad and Tobago</option>
											<option value='Tunisia'>Tunisia</option>
											<option value='Turkey'>Turkey</option>
											<option value='Turkmenistan'>Turkmenistan</option>
											<option value='Ukraine'>Ukraine</option>
											<option value='United&#x20;Arab&#x20;Emirates'>United Arab Emirates</option>
											<option value='United&#x20;Kingdom'>United Kingdom</option>
											<option value='Unknown'>Unknown</option>
											<option value='Uruguay'>Uruguay</option>
											<option value='Venezuela'>Venezuela</option>
											<option value='Vietnam'>Vietnam</option>
										</select></span>
									</div>
								</div>
							</div>

							<div class="row ">
								<div class="col-xs-12">
									<div class="form-group">
										<label>Email</label>
											<span class="wpcf7-form-control-wrap"><input type='text' maxlength='100'
																						 name='Email'/></span>
									</div>
								</div>
							</div>
							<div class="row ">
								<div class="col-xs-12">
									<div class="form-group">
										<label>Message</label>
										<span class="wpcf7-form-control-wrap"><textarea name='LEADCF121'
																						maxlength='2000'>&nbsp;</textarea></span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
										<label>Enter the
											symbols from the security image</label><span
											class="wpcf7-form-control-wrap captcha-784"><input
												class="wpcf7-form-control wpcf7-captchar" type='text' maxlength='80'
												name='enterdigest'/></span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
										<label class="label-captcha-wi pull-right">Security image</label><img id='imgid'
																											  class="wpcf7-form-control wpcf7-captchac wpcf7-captcha-captcha-784 captcha-wi pull-right"
																											  width="72"
																											  height="24"
																											  alt="captcha"
																											  src='https://crm.zoho.com/crm/CaptchaServlet?formId=37fb372d1963ae15266f21ee491d59c5b63db7848c2912c344bbf39283de8df0&grpid=c0403015b07669e3f0e97ebf088d119da0c9d4374049a724a1bb05a8a5ecca11'>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-xs-9">
									<input class="btn btn-primary showhb" style='font-size:16px;' type='submit'
										   value='Submit'/>
								</div>
							</div>

							<script>
								jQuery(function($){
									var choiceZohoForm = '#choiceZohoForm',
										zohoDesc = '#zohoDesc',
										zohoBlock = '#crmWebToEntityForm';

									    $(choiceZohoForm).change(function () {
										$(zohoBlock+' '+zohoDesc).text($( choiceZohoForm + " option:selected" ).text())
									});

								});
								var mndFileds = new Array('First Name', 'Last Name', 'Email', 'LEADCF5');
								var fldLangVal = new Array('First name', 'Last name', 'Email', 'Country');
								var name = '';
								var email = '';

								/* Do not remove this code. */
								function reloadImg() {
									if (document.getElementById('imgid').src.indexOf('&d') !== -1) {
										document.getElementById('imgid').src = document.getElementById('imgid').src.substring(0, document.getElementById('imgid').src.indexOf('&d')) + '&d' + new Date().getTime();
									} else {
										document.getElementById('imgid').src = document.getElementById('imgid').src + '&d' + new Date().getTime();
									}
								}
								function checkMandatory() {
									for (i = 0; i < mndFileds.length; i++) {
										var fieldObj = document.forms['WebToLeads1923966000000602001'][mndFileds[i]];
										if (fieldObj) {
											if (((fieldObj.value).replace(/^\s+|\s+$/g, '')).length == 0) {
												if (fieldObj.type == 'file') {
													alert('Please select a file to upload');
													fieldObj.focus();
													return false;
												}
												alert(fldLangVal[i] + ' cannot be empty');
												fieldObj.focus();
												return false;
											} else if (fieldObj.nodeName == 'SELECT') {
												if (fieldObj.options[fieldObj.selectedIndex].value == '-None-') {
													alert(fldLangVal[i] + ' cannot be none');
													fieldObj.focus();
													return false;
												}
											} else if (fieldObj.type == 'checkbox') {
												if (fieldObj.checked == false) {
													alert('Please accept  ' + fldLangVal[i]);
													fieldObj.focus();
													return false;
												}
											}
											try {
												if (fieldObj.name == 'Last Name') {
													name = fieldObj.value;
												}
											} catch (e) {
											}
										}
									}
								}

							</script>
						</form>
						<!-- Do not remove this code. -->
						<iframe name='captchaFrame' style='display:none;'></iframe>
					</div>
				</div>
            </div>
        </div>
	</div>

	<?php do_action( 'storefront_after_footer' ); ?>

	<?php wp_footer(); ?>

<script>
		var $nav_header     = jQuery('#masthead'),
			$header_height  = $nav_header.height(),
			$main_height    = jQuery('#main').height(),

            $chatButton     = jQuery('.mode-chat'),
            $contactButton  = jQuery('.mode-email'),
            $chatTab        = jQuery('#widget-chat'),
            $contactTab     = jQuery('#widget-contact');

		function navSlide() {
			var scroll_top = jQuery(window).scrollTop(),
				offset_val = $main_height - $header_height;

			if (scroll_top == 0) {
				$nav_header.css({'position': 'relative'});
			} else {
				$nav_header.css({'position': 'fixed'});
			}
		}

		jQuery(window).scroll(function(){
			navSlide();
		});

		jQuery(document).on('click', '.switch-mode .switcher a', function(event){
			event.stopPropagation;
			event.preventDefault;
			var $target = jQuery(this).parent();
			var clickedToMode = $target.hasClass('mode-chat')?'mode-chat':'mode-email';

			if ($chatButton.hasClass(clickedToMode)){
                $contactButton.removeClass('active');
                $contactTab.addClass('hidden');

				$chatButton.addClass('active');
                $chatTab.removeClass ('hidden');
			} else {
                $chatButton.removeClass('active');
                $chatTab.addClass('hidden');

                $contactButton.addClass('active');
                $contactTab.removeClass ('hidden');
			}
			return false;
		});

</script>


<?php
	echo show_hotjar_marketing_pixel();
?>

</body>
</html>
