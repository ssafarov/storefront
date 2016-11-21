<?php

/** custom shipping **/
add_action('woocommerce_before_cart_totals', 'display_custom_shipping');
//@ todo if uncomment we willl see this block under the customer shipping address
//add_action('display_custom_shipping', 'display_custom_shipping');
function display_custom_shipping()
{
    // todo check only virtual items
    if (is_cart()) {
        return;
    }
    $user = wp_get_current_user();
    if (!$user || !$user->has_cap('distributor')) {
        return;
    }

    $custom_shipping = WC()->session->get('custom_shipping');
    $custom_shipping_info = WC()->session->get('custom_shipping_info');
    ?>

    <style>
        .inline {
            display: inline!important;
        }

        @media (max-width: 768px) {
            #c_sh {
                text-align: left;
                padding-top: 10px;
                border-bottom: 1px #eee solid;
            }
        }
    </style>

    <div id="c_sh">
        <div>
            <label for="custom_shipping_1"><input type="radio" id="custom_shipping_1" <?= $custom_shipping ? '' : 'checked' ?> name="custom_shipping" class="inline" value="no"/>
                &nbsp;<?= __('Fuel 3D organise shipping', 'storefront'); ?>
            </label>
            <br/>
            <label for="custom_shipping_2"><input type="radio" required="required" id="custom_shipping_2" <?= $custom_shipping ? 'checked' : '' ?> name="custom_shipping" class="inline" value="yes"/>
                &nbsp;<?= __('You organise shipping', 'storefront'); ?>
            </label>
        </div>
        <?php if ($custom_shipping): ?>
            <div>
                <label for="custom_shipping_info"><?= __('Courier details and account number:', 'storefront'); ?></label>
                <p class="validate-required ">
                    <textarea required="required" id="custom_shipping_info" data-validation="required" name="custom_shipping_info"><?= __($custom_shipping_info,
                    'storefront'); ?></textarea>
                </p>
            </div>
        <?php endif; ?>
        <script>
            (function ($) {
                $(function () {
                    //@ todo SS For future use inside the customer shipping block
                    $(document.body).on('click',  'a.showcustomshipping', show_custom_shipping_form);
                    $(document.body).on('click',  'a.hidecustomshipping', hide_custom_shipping_form);

                    function show_custom_shipping_form() {
                        $(document.body).find('a.showcustomshipping').parent().fadeToggle(400, function () {
                            $('input[name=custom_shipping]').val('yes');

                            $('#custom_shipping_info_form').slideToggle(200, function () {
                                $('#custom_shipping_info_form').find('textarea[name=custom_shipping_info]').focus();
                            });
                        });
                        return false;
                    }

                    function hide_custom_shipping_form() {
                        $('input[name=custom_shipping]').val('no');

                        $('#custom_shipping_info_form').slideToggle(200, function () {
                            $(document.body).find('a.showcustomshipping').parent().fadeToggle(400);
                        });
                        return false;
                    }

                    $("#c_sh input").change(function () {
                        $('body').trigger('update_checkout');
                    });

                    $("#c_sh textarea").blur(function () {
                        $('body').trigger('update_checkout');
                    });

                });
            })(jQuery);
        </script>
    </div>

    <?php
}

add_action('woocommerce_checkout_update_order_review', 'update_custom_shipping');
function update_custom_shipping($post_data)
{
    $user = wp_get_current_user();
    if (!$user || !$user->has_cap('distributor')) {
        return;
    }

    parse_str($post_data, $data_array);

    if (isset($data_array['custom_shipping'])) {
        WC()->session->set('custom_shipping', $data_array['custom_shipping'] == 'yes');
    } else {
        WC()->session->set('custom_shipping', false);
    }

    if (isset($data_array['custom_shipping_info'])) {
        WC()->session->set('custom_shipping_info', $data_array['custom_shipping_info']);
    }
}

add_filter('woocommerce_cart_needs_shipping', 'is_custom_shipping', 15, 1);
function is_custom_shipping($needs_shipping)
{
    if (!WC()->session) {
        return $needs_shipping;
    }

    $custom_shipping = WC()->session->get('custom_shipping');

    return $custom_shipping?$custom_shipping:$needs_shipping;

}

add_action('woocommerce_checkout_update_order_meta', 'add_custom_shipping_to_order', 10, 2);
function add_custom_shipping_to_order($order_id, $posted)
{
    $custom_shipping = WC()->session->get('custom_shipping');
    if ($custom_shipping) {
        $custom_shipping_info = WC()->session->get('custom_shipping_info');
        update_post_meta($order_id, '_custom_shipping', $custom_shipping);
        update_post_meta($order_id, '_custom_shipping_info', $custom_shipping_info);
    }
}

add_action('woocommerce_admin_order_data_after_billing_address', 'display_admin_customer_info');
function display_admin_customer_info(WC_Order $order)
{
    $custom_shipping = get_post_meta($order->id, '_custom_shipping');
    if ($custom_shipping) {
        echo '</div><div class="order_data_column">';
        ?>
        <h4><? _e('Courier details and account number', 'storefront'); ?></h4>
        <? echo get_post_meta($order->id, '_custom_shipping_info', true) ?>
        <?php
    }
}