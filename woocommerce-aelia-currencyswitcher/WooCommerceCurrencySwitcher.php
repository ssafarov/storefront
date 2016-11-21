<?php
/**
 * User: Mihai Lodoaba
 * Date: 3/4/15
 * Time: 7:11 PM
 *
 * Overrides method for the currency selector so it takes into account selected default currency option (if any)
 */

class WooCommerceCurrencySwitcher extends WC_Aelia_CurrencySwitcher_Widget
{
    public static function render_currency_selector($widget_args)
    {
        ob_start();

        $class = get_called_class();

        $widget_instance = new $class();

        // all we have to do now is to add the currency preference if existent (and none other requested through POST)
        // we'll store the preferrence for 10mins.
        $selected = Aelia_SessionManager::get_value(AELIA_CS_USER_CURRENCY);
        if(!$selected){
        $selected = esc_attr(get_user_meta(get_current_user_id(), 'default_currency', true));
        }
        if ($selected && ! isset($_POST['aelia_cs_currency']) && ! isset($_COOKIE['aelia_op_currency']))
        {
            $widget_args['selected_currency'] = $selected;
            // we need to update Currency Switcher's Session Currency in order to get all values in the desired default currency
            // and set a personal cookie, just to be sure nothing breaks
            Aelia_SessionManager::set_value(AELIA_CS_USER_CURRENCY, $selected);
        }
        else
        {
            // if we have a cookie set and no post request, let's use it
            if ( ! isset($_POST['aelia_cs_currency']) && isset($_COOKIE['aelia_op_currency']))
            {
                $widget_args['selected_currency'] = $_COOKIE['aelia_op_currency'];
                Aelia_SessionManager::set_value(AELIA_CS_USER_CURRENCY, $_COOKIE['aelia_op_currency']);
            }
            else
            {
                // If a currency request is recorded, let's remember it
                if (isset($_POST['aelia_cs_currency']) && ! headers_sent())
                {
                    setcookie('aelia_op_currency', $_POST['aelia_cs_currency'], time()+60*10, COOKIEPATH, COOKIE_DOMAIN, false);
                }
            }
        }

        $widget_instance->widget($widget_args);

        $output = ob_get_contents();

        @ob_end_clean();

        return $output;
    }
}