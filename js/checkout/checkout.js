/* storefront new checkout */
/* global wc_checkout_params */

jQuery( function( $ ) {

	// wc_checkout_params is required to continue, ensure the object exists
	if (typeof wc_checkout_params === 'undefined') {
		return false;
	}

	$.blockUI.defaults.overlayCSS.cursor = 'default';

	var default_password_length = 10,
	    currentAddressesTheSame = undefined,
		previousAddressesTheSame = currentAddressesTheSame,
		shipping_methods = [],
		payment_method,
		first_name, last_name,
		company, email, country, country_title, state, county, postcode, city, address, address_2,
		s_first_name, s_last_name,
		s_company, s_email, s_country, s_country_title, s_state, s_county, s_postcode, s_city, s_address, s_address_2,
		order_comments, account_password,
		shippingBlock, billingBlock,
		shippingCustomer, shippingAddress,
		billingCustomer, billingAddress,
		customerAddressLine, customerHtml, addressHtml,
		focused;

	/**
	 * Function generates a random string for use in unique IDs, etc
	 *
	 * @param <int> n - The length of the string
	 */
	function randomString(n) {
		if (!n) {
			n = 5;
		}

		var text = '';
		var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_';

		for (var i = 0; i < n; i++) {
			text += possible.charAt(Math.floor(Math.random() * possible.length));
		}

		return text;
	}

	var wc_checkout_form = {
		updateTimer: false,
		dirtyInput: true,
		$order_review: $('#order_review'),
		$checkout_form: $('form.checkout'),
		isUserLogged: false,
		init: function () {
			$(document.body).bind('update_checkout', this.reset_update_checkout_timer);
			$(document.body).bind('update_checkout', this.update_checkout);
			$(document.body).bind('updated_checkout', this.updated_checkout);
			$(document.body).bind('init_checkout', this.init_checkout);

			// Payment methods
			this.$order_review.on('click', 'input[name=payment_method]', this.payment_method_selected);

			// Form submission
			this.$checkout_form.on('submit', this.submit);

			// Inline validation
			this.$checkout_form.on( 'blur input change', '.input-text', this.validate_field );

			// Initial validation after billing show
			this.$checkout_form.on('show', '.input-text, select', this.validate_field);

			// Inputs/selects which update totals
			this.$checkout_form.on('input change', 'select.shipping_method, input[name^=shipping_method], .update_totals_on_change select, .update_totals_on_change input[type=radio]', this.trigger_update_checkout);
			this.$checkout_form.on('change', '.customer-field input.input-text, .address-field input.input-text, .address-field input.input-text, .update_totals_on_change input.input-text', this.maybe_input_changed);
			this.$checkout_form.on('input change', '.address-field select', this.input_changed);
			this.$checkout_form.on('input change', '.update_totals_on_change input.input-text', this.queue_update_checkout);

			// Address fields
			this.$checkout_form.on('change', '#ship-to-different-address input', this.ship_to_different_address);
			this.$checkout_form.on('change', '#billing-address-the-same-as-shipping input', this.billing_the_same_as_shipping);

			// Trigger events
			this.$order_review.find('input[name=payment_method]:checked').trigger('click');
			this.$checkout_form.find('#ship-to-different-address input').trigger('change');
			this.$checkout_form.find('#billing-address-the-same-as-shipping input').trigger('change');

			if (wc_checkout_params.option_guest_checkout === 'yes') {
				$('input#createaccount').change(this.toggle_create_account).change();
			}

			if ($("#billing-address-the-same-as-shipping input").length > 0) {
				currentAddressesTheSame = $("#billing-address-the-same-as-shipping input").is(':checked');
				previousAddressesTheSame = currentAddressesTheSame;
			}

			var data = {action: 'is_user_logged_in'};
			jQuery.post(globalFuel.ajaxUrl, data, function (response) {
				this.isUserLogged = response == 'yes';

				// Update after page ready and set up
				if (wc_checkout_params.is_checkout === '1') {
					$(document.body).trigger('init_checkout');
				}
			});

		},
		toggle_create_account: function () {
			$('div.create-account').hide();

			if ($(this).is(':checked')) {
				$('div.create-account').slideDown();
			}
		},
		init_checkout: function () {
			$(document.body).find('#checkout_coupon_form').hide();
			$('#billing_country, #shipping_country, .country_to_state').change();
			$(document.body).trigger('update_checkout', [false]);
		},
		maybe_input_changed: function (e) {
			if (wc_checkout_form.dirtyInput) {
				wc_checkout_form.input_changed(e);
			}
		},
		input_changed: function (e) {
			wc_checkout_form.dirtyInput = e.target;
			wc_checkout_form.maybe_update_checkout();
		},
		queue_update_checkout: function (e) {
			var code = e.keyCode || e.which || 0;

			if (code === 9) {
				return true;
			}

			wc_checkout_form.dirtyInput = this;
			wc_checkout_form.reset_update_checkout_timer();
			wc_checkout_form.updateTimer = setTimeout(wc_checkout_form.maybe_update_checkout, 1000);
		},
		trigger_update_checkout: function (needAjax) {
			needAjax = typeof needAjax !== 'undefined' ? needAjax : true;
			wc_checkout_form.reset_update_checkout_timer();
			wc_checkout_form.dirtyInput = false;

			$(document.body).trigger('update_checkout', needAjax);
		},
		maybe_update_checkout: function () {
			var update_totals = true;

			if ($(wc_checkout_form.dirtyInput).size()) {
				var $required_inputs = $(wc_checkout_form.dirtyInput).closest('div').find('.address-field.validate-required, .customer-field.validate-required, .email-field.validate-required');

				if ($required_inputs.size()) {
					$required_inputs.each(function () {
						if ($(this).find('input.input-text').val() === '') {
							update_totals = false;
						}
					});
				}
			}
			if (update_totals) {
				wc_checkout_form.trigger_update_checkout();
			}
		},
		billing_the_same_as_shipping: function () {

			previousAddressesTheSame = currentAddressesTheSame;
			currentAddressesTheSame = $('#billing-the-same-as-shipping-address-checkbox').is(':checked');

			$('div.billing_address').find('input').each(function () {
				$(this).hide()
			});
			$('div.billing_address').hide();

			if (!currentAddressesTheSame) {
				$('div.billing_address').find('input').each(function () {
					$(this).show()
				});
				$('div.billing_address').slideDown();
			}

			wc_checkout_form.trigger_update_checkout(false);
		},
		ship_to_different_address: function () {
			previousAddressesTheSame = currentAddressesTheSame;
			currentAddressesTheSame = !$(this).is(':checked');
			$('div.shipping_address').find('input').each(function () {
				$(this).hide()
			});
			$('div.shipping_address').hide();
			if (!currentAddressesTheSame) {
				$('div.shipping_address').find('input').each(function () {
					$(this).show()
				});
				$('div.shipping_address').slideDown();
			}

			wc_checkout_form.trigger_update_checkout(false);
		},
		payment_method_selected: function () {
			if ($('.payment_methods input.input-radio').length > 1) {
				var target_payment_box = $('div.payment_box.' + $(this).attr('ID'));

				if ($(this).is(':checked') && !target_payment_box.is(':visible')) {
					$('div.payment_box').filter(':visible').slideUp(250);

					if ($(this).is(':checked')) {
						$('div.payment_box.' + $(this).attr('ID')).slideDown(250);
					}
				}
			} else {
				$('div.payment_box').show();
			}

			if ($(this).data('order_button_text')) {
				$('#place_order').val($(this).data('order_button_text'));
			} else {
				$('#place_order').val($('#place_order').data('value'));
			}
		},
		reset_update_checkout_timer: function () {
			clearTimeout(wc_checkout_form.updateTimer);
		},
		validate_field: function () {
			var $this = $(this),
				$parent = $this.closest('.form-row'),
				validated = true,
				fieldValue = $this.val();

			if ($parent.is('.validate-required')) {
				if (fieldValue === '') {
					$parent.removeClass('woocommerce-validated').addClass('woocommerce-invalid woocommerce-invalid-required-field');
					validated = false;
				}
			}

			if ($parent.is('.validate-email')) {
				if (fieldValue) {
					/* http://stackoverflow.com/questions/2855865/jquery-validate-e-mail-address-regex */
					var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

					if (( !pattern.test(fieldValue)) || ($this.hasClass('need-login')) || ($this.hasClass('error')) || ($this.hasClass('invalid-state'))) {
						$parent.removeClass('woocommerce-validated').addClass('woocommerce-invalid woocommerce-invalid-email');
						validated = false;
					}

				}
			}

			if ($parent.is('.validate-postcode')) {
				if (fieldValue === '') {
					$parent.removeClass('woocommerce-validated').addClass('woocommerce-invalid woocommerce-invalid-postcode');
					validated = false;
				}
			}

			if (validated) {
				$parent.removeClass('woocommerce-invalid woocommerce-invalid-required-field woocommerce-invalid-email woocommerce-invalid-postcode').addClass('woocommerce-validated');
			}

			focused = $this.attr('id');
		},
		update_checkout: function (event, needAjax) {
			// Small timeout to prevent multiple requests when several fields update at the same time
			wc_checkout_form.reset_update_checkout_timer();
			wc_checkout_form.updateTimer = setTimeout(wc_checkout_form.update_checkout_action (needAjax), 8);
		},
		update_checkout_action: function (needAjax) {
			needAjax = typeof needAjax != 'undefined' ? needAjax : true;

            if ($('form.checkout').size() === 0) {
				return;
			}

			if (globalFuel.xhrRequest) {
				globalFuel.xhrRequest.abort();
			}

			$('select.shipping_method, input[name^=shipping_method][type=radio]:checked, input[name^=shipping_method][type=hidden]').each(function () {
				shipping_methods[$(this).data('index')] = $(this).val();
			});

			payment_method = $('#order_review input[name=payment_method]:checked').val();
			first_name = $('#billing_first_name').val();
			last_name = $('#billing_last_name').val();
			company = $('#billing_company').val();
			email = $('#billing_email').val();
			country = $('#billing_country').val();
			country_title = $('#billing_country option:selected').text();
			state = $('#billing_state').val();
			county = $('#billing_county').val();
			postcode = $('#billing_postcode').val();
			city = $('#billing_city').val();
			address = $('#billing_address_1').val();
			address_2 = $('#billing_address_2').val();
			s_first_name = $('#shipping_first_name').val();
			s_last_name = $('#shipping_last_name').val();
			s_company = $('#shipping_company').val();
			s_email = $('#shipping_email').val();
			s_country = $('#shipping_country').val();
			s_country_title = $('#shipping_country option:selected').text();
			s_state = $('#shipping_state').val();
			s_county = $('#shipping_county').val();
			s_postcode = $('#shipping_postcode').val();
			s_city = $('#shipping_city').val();
			s_address = $('#shipping_address_1').val();
			s_address_2 = $('#shipping_address_2').val();

			order_comments = $('#order_comments').val();
			account_password = $('input[name=account_password]').length > 0 ? $('input[name=account_password]').val() : undefined;

			shippingBlock = $('.addresses_block').find('.shipping_info');
			shippingCustomer = shippingBlock.find('.customer');
			shippingAddress = shippingBlock.find('.address');

			billingBlock = $('.addresses_block').find('.billing_info');
			billingCustomer = billingBlock.find('.customer');
			billingAddress = billingBlock.find('.address');

			if (currentAddressesTheSame !== undefined) {

				if (currentAddressesTheSame) {
					first_name = s_first_name;
					last_name = s_last_name;
					company = s_company;
					email = s_email;
					country = s_country;
					country_title = s_country_title;
					state = s_state;
					county = s_county;
					postcode = s_postcode;
					city = s_city;
					address = s_address;
					address_2 = s_address_2;
				}

				if (previousAddressesTheSame && !currentAddressesTheSame) {
					first_name = s_first_name;
					last_name = s_last_name;
					company = s_company;
					email = s_email;
					country = s_country;
					country_title = s_country_title;
					state = s_state;
					county = s_county;
					postcode = s_postcode;
					city = s_city;
					address = s_address;
					address_2 = s_address_2;

					$('#billing_first_name').val(s_first_name);
					$('#billing_last_name').val(s_last_name);
					$('#billing_company').val(s_company);
					$('#billing_email').val(s_email);
					$('#billing_country').val(s_country);
					$('#billing_state').val(s_state);
					$('#billing_county').val(s_county);
					$('#billing_postcode').val(s_postcode);
					$('#billing_city').val(s_city);
					$('#billing_address_1').val(s_address);
					$('#billing_address_2').val(s_address_2);
				}
			}

			if (account_password && $('input[name=account_password]').length > 0) {
				$('input[name=account_password]').val(account_password);
			}

			// Update info on the order summary tab
			if ((shippingBlock.length > 0) && (shippingCustomer.length > 0) && (shippingAddress.length > 0)) {
				customerHtml = $("<b>{0} {1}</b>".format(s_first_name, s_last_name).trim());
				customerAddressLine = "{0}, {1}, {2}, {3}, {4}, {5}, {6}, {7}".format(s_company, s_address, s_address_2, s_city, s_county, s_state, s_postcode, s_country_title).replace(/^,/, '').replace(/undefined,/g, '').replace(/\s,\s/g, ', ').replace(/,\s,/g, ', ').replace(/,,\s/g, ', ').replace(/\s\s+/g, ' ').trim();
				addressHtml = $("<span>" + customerAddressLine + "</span>");
				shippingCustomer.html(customerHtml);
				shippingAddress.html(addressHtml);
			}

			if ((billingBlock.length > 0) && (billingCustomer.length > 0) && (billingAddress.length > 0)) {
				customerHtml = $("<b>{0} {1}</b>".format(first_name, last_name).trim());
				customerAddressLine = "{0}, {1}, {2}, {3}, {4}, {5}, {6}, {7}".format(company, address, address_2, city, county, state, postcode, country_title).replace(/^,/, '').replace(/undefined,/g, '').replace(/\s,\s/g, ', ').replace(/,\s,/g, ', ').replace(/,,\s/g, ', ').replace(/\s\s+/g, ' ').trim();
				addressHtml = $("<span>" + customerAddressLine + "</span>");
				billingCustomer.html(customerHtml);
				billingAddress.html(addressHtml);
			}

			$('.woocommerce-checkout-payment, .woocommerce-checkout-review-order-table').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			var checkout_data = {
				security: wc_checkout_params.update_order_review_nonce,
				ship_to_different_address: !currentAddressesTheSame,
				billing_the_same_as_shipping: currentAddressesTheSame,

				shipping_method: shipping_methods,
				payment_method: payment_method,

				first_name: first_name,
				last_name: last_name,
				company: company,
				email: email,
				country: country,
				county: county,
				state: state,
				postcode: postcode,
				city: city,
				address: address,
				address_2: address_2,

				billing_first_name: first_name,
				billing_last_name: last_name,
				billing_company: company,
				billing_email: email,
				billing_country: country,
				billing_county: county,
				billing_state: state,
				billing_postcode: postcode,
				billing_city: city,
				billing_address_1: address,
				billing_address_2: address_2,



				s_first_name: s_first_name,
				s_last_name: s_last_name,
				s_company: s_company,
				s_email: s_email,
				s_country: s_country,
				s_county: s_county,
				s_state: s_state,
				s_postcode: s_postcode,
				s_city: s_city,
				s_address: s_address,
				s_address_2: s_address_2,

				shipping_first_name: s_first_name,
				shipping_last_name: s_last_name,
				shipping_company: s_company,
				shipping_email: s_email,
				shipping_country: s_country,
				shipping_county: s_county,
				shipping_state: s_state,
				shipping_postcode: s_postcode,
				shipping_city: s_city,
				shipping_address_1: s_address,
				shipping_address_2: s_address_2,

				order_comments: order_comments,

				post_data: $('form.checkout').serialize()
			};

			if (!wc_checkout_form.isUserLogged && $('input[name=account_password]').length > 0) {
				if ((account_password == undefined) || account_password.length < default_password_length) {
					account_password = randomString(default_password_length)
				}
				checkout_data['account_password'] = account_password;
				$('input[name=account_password]').val(account_password);
			}

			if (needAjax) {
				globalFuel.xhrRequest = $.ajax({
					type: 'POST',
					url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'update_order_review'),
					data: checkout_data,
					success: function (data) {
						// Always update the fragments
						if (data && data.fragments) {
							$.each(data.fragments, function (key, value) {
								$(key).replaceWith(value);
								$(key).unblock();
							});
						}

						// Check for error
						if ('failure' === data.result) {

							var $form = $('form.checkout');

							if ('true' === data.reload) {
								window.location.reload();
								return;
							}

							$('.woocommerce-error, .woocommerce-message').remove();

							// Add new errors
							if (data.messages) {
								$form.prepend(data.messages);
							} else {
								$form.prepend(data);
							}

							// Lose focus for all fields
							$form.find('.input-text, select').blur();

							// Hide whole checkout when we get error
							$('#wizard').hide();

							// Scroll to top
							$('html, body').animate({
								scrollTop: ( $('form.checkout').offset().top - 100 )
							}, 1000);

						}

						// Trigger click e on selected payment method
						if ($('.woocommerce-checkout').find('input[name=payment_method]:checked').size() === 0) {
							$('.woocommerce-checkout').find('input[name=payment_method]:eq(0)').attr('checked', 'checked');
						}

						$('.woocommerce-checkout').find('input[name=payment_method]:checked').eq(0).trigger('click');

						if ((typeof focused == 'string')&&(focused.length > 0)) {
							$('#'+focused).focus();
						}

						$('#billing-the-same-as-shipping-address-checkbox').prop('checked', currentAddressesTheSame);
						wc_checkout_form.billing_the_same_as_shipping();

						// Fire updated_checkout evnt
						$(document.body).trigger('updated_checkout');
					}
				});

			} else {
				// Fire updated_checkout evnt
				$(document.body).trigger('updated_checkout');

			}
		},
		updated_checkout: function () {

			previousAddressesTheSame = currentAddressesTheSame;

			$.validate({
				form: jQuery("form.checkout"),
				language: {
					requiredFields: 'Field cannot be blank',
					notConfirmed: 'Passwords do not match'
					//lengthTooShortStart: 'Password should have at least 10 characters'
				}
			});

			//definetly remove all UI blocks
			$('div.blockUI').hide();

			return true;

		},
		submit: function () {
			$(document.body).trigger('update_checkout');

			var $form = $(this);

			if ($form.is('.processing')) {
				return false;
			}

			var payMethod = $('#payment input[name=payment_method]:checked').val() != undefined ? $('#payment input[name=payment_method]:checked').val() : $('#order_review input[name=payment_method]:checked').val();

			// Trigger a handler to let gateways manipulate the checkout if needed
			if ($form.triggerHandler('checkout_place_order') !== false && $form.triggerHandler('checkout_place_order_' + payMethod) !== false) {

				$form.addClass('processing');

				$('#billing-the-same-as-shipping-address-checkbox-hidden').prop('disabled', currentAddressesTheSame);
				$('#billing-the-same-as-shipping-address-checkbox').prop('disabled', !currentAddressesTheSame);

				$('#shipping_first_name').val(s_first_name);
				$('#shipping_last_name').val(s_last_name);
				$('#shipping_company').val(s_company);
				$('#shipping_email').val(s_email);
				$('#shipping_country').val(s_country);
				$('#shipping_state').val(s_state);
				$('#shipping_county').val(s_county);
				$('#shipping_postcode').val(s_postcode);
				$('#shipping_city').val(s_city);
				$('#shipping_address_1').val(s_address);
				$('#shipping_address_2').val(s_address_2);

				$('#order_comments').val(order_comments);

				$('#billing_first_name').val(first_name);
				$('#billing_last_name').val(last_name);
				$('#billing_company').val(company);
				$('#billing_email').val(email);
				$('#billing_country').val(country);
				$('#billing_state').val(state);
				$('#billing_county').val(county);
				$('#billing_postcode').val(postcode);
				$('#billing_city').val(city);
				$('#billing_address_1').val(address);
				$('#billing_address_2').val(address_2);

				$('input[name=account_password]').val(account_password);

				if (account_password && $('input[name=account_password]').length > 0) {
					$('input[name=account_password]').val(account_password);
				}


				var form_data = $form.data();

				if (1 !== form_data['blockUI.isBlocked']) {
					$form.block({
						message: null,
						overlayCSS: {
							background: '#fff',
							opacity: 0.6
						}
					});
				}

				$.ajax({
					type: 'POST',
					url: wc_checkout_params.checkout_url,
					data: $form.serialize(),
					dataType: 'json',
					success: function (result) {

						try {
							if (result.result === 'success') {
								if (-1 === result.redirect.indexOf('https://') || -1 === result.redirect.indexOf('http://')) {
									window.location = result.redirect;
								} else {
									window.location = decodeURI(result.redirect);
								}
							} else if (result.result === 'failure') {
								throw 'Result failure';
							} else {
								throw 'Invalid response';
							}
						} catch (err) {
							// Reload page
							if (result.reload === 'true') {
								window.location.reload();
								return;
							}

							// Trigger update in case we need a fresh nonce
							if (result.refresh === 'true') {
								$(document.body).trigger('update_checkout');
							}

							// Add new errors
							if (result.messages) {
								wc_checkout_form.submit_error(result.messages);
							} else {
								wc_checkout_form.submit_error('<div class="woocommerce-error">' + wc_checkout_params.i18n_checkout_error + '</div>');
							}
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						wc_checkout_form.submit_error('<div class="woocommerce-error">' + errorThrown + '</div>');
					}
				});
			}

			return false;
		},
		submit_error: function (error_message) {
			$('.woocommerce-error, .woocommerce-message').remove();
			wc_checkout_form.$checkout_form.prepend(error_message);
			wc_checkout_form.$checkout_form.removeClass('processing').unblock();
			wc_checkout_form.$checkout_form.find('.input-text, select').blur();
			$('html, body').animate({
				scrollTop: ( $('form.checkout').offset().top - 100 )
			}, 1000);
			$(document.body).trigger('checkout_error');
		}
	};

	var wc_checkout_coupons = {
		//_form: $('#checkout_coupon_form'), //
		init: function () {
			$(document.body).on('click',  'a.showcoupon', this.show_coupon_form);
			$(document.body).on('click',  'a.hidecoupon', this.hide_coupon_form);
			$(document.body).on('click',  '.woocommerce-remove-coupon', this.remove_coupon);

			// Form submission
			$(document.body).on('click',  'input[name=apply_coupon]', this.submit);
			$('#checkout_coupon_form').hide();
		},
		show_coupon_form: function () {
			$(document.body).find('a.showcoupon').parent().fadeToggle(400, function () {
				$('#checkout_coupon_form').slideToggle(200, function () {
					$('#checkout_coupon_form').find('input[name=coupon_code]').focus();
				});
			});
			return false;
		},
		hide_coupon_form: function () {
			$('#checkout_coupon_form').slideToggle(200, function () {
				$(document.body).find('a.showcoupon').parent().fadeToggle(400);
			});
			return false;
		},
		submit: function () {
			var form_coupon_code = $('#checkout_coupon_form').find('input[name=coupon_code]').val(),
				$helpBlock = $('#checkout_coupon_form').find(errorElement + '.' + helpBlockClass);

			if ($('#checkout_coupon_form').is('.processing')) {
				return false;
			}

			if (globalFuel.xhrRequest) {
				globalFuel.xhrRequest.abort();
			}

			if ($helpBlock.length != 0) {
				$helpBlock.removeClass(errorMessageClass).removeClass(successMessageClass).addClass(workingMessageClass).html('Checking the code...');
			}

			$('#checkout_coupon_form').addClass('processing').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			var data = {
				security: wc_checkout_params.apply_coupon_nonce,
				coupon_code: form_coupon_code
			};

			globalFuel.xhrRequest = $.ajax({
				type: 'POST',
				url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
				data: data,
				success: function (response) {
					$('.woocommerce-error, .woocommerce-message').remove();
					$('#checkout_coupon_form').removeClass('processing').unblock();

					if (response) {
						parsedResponse = $(response);
						messageClass = parsedResponse.hasClass ('alert-danger')?errorMessageClass:successMessageClass;
						errorMessage = parsedResponse.text().replace("&times;", "").replace ("×", "");
						if ($helpBlock.length == 0) {
							$("#coupon_code").parent().append('<'+errorElement+' class="' + messageClass + ' ' + helpBlockClass + '">' +errorMessage + '</'+errorElement+'>');
						} else {
							$helpBlock.removeClass(errorMessageClass).removeClass(successMessageClass).removeClass(workingMessageClass).addClass(messageClass).html(errorMessage);
						}

						if (!parsedResponse.hasClass ('alert-danger')){
							setTimeout((function() {
								$(document.body).trigger('update_checkout');
							}), 2500);
						}
					}
				},
				dataType: 'html'
			});

			return false;
		},
		remove_coupon: function (e) {
			e.preventDefault();

			var container = $(this).parents('.woocommerce-checkout-review-order'),
				coupon = $(this).data('coupon');

			container.addClass('processing').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			var data = {
				security: wc_checkout_params.remove_coupon_nonce,
				coupon: coupon
			};

			$.ajax({
				type: 'POST',
				url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_coupon'),
				data: data,
				success: function (code) {
					$('.woocommerce-error, .woocommerce-message').remove();
					container.removeClass('processing').unblock();

					if (code) {
						$('form.woocommerce-checkout').before(code);

						$(document.body).trigger('update_checkout');

						// remove coupon code from coupon field
						$('.checkout_coupon').find('input[name="coupon_code"]').val('');
					}
				},
				error: function (jqXHR) {
					if (wc_checkout_params.debug_mode) {
						/*jshint devel: true */
						console.log(jqXHR.responseText);
					}
				},
				dataType: 'html'
			});
		}
	};

	var wc_checkout_login_form = {
		init: function () {
			$(document.body).on('click', 'a.showlogin', this.show_login_form);
		},
		show_login_form: function () {
			$('form.login').slideToggle();
			return false;
		}
	};

	// Update on page ready
	$(document).ready(function () {
		wc_checkout_login_form.init();
		wc_checkout_form.init();
		wc_checkout_coupons.init();
	});

});