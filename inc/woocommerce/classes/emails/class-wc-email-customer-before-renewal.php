<?php

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('WC_Email_Customer_Before_Renewal')) :

    /**
     * Customer Before Renewal
     *
     * @class       WC_Email_Customer_Before_Renewal
     * @version     1.0.0
     * @package     WooCommerce/Classes/Emails
     * @author      WooThemes
     * @extends     WC_Email
     */
    class WC_Email_Customer_Before_Renewal extends WC_Email
    {

        /**
         * Constructor
         */
        function __construct()
        {
            $this->id = 'customer_before_renewal';
            $this->title = __('Before renewal', 'storefront');
            $this->description = __('Before renewal description', 'storefront');

            $this->heading = __('Before renewal heading', 'storefront');
            $this->subject = __('Before renewal subject', 'storefront');

            $this->template_html = 'emails/before-renewal.php';
            $this->template_plain = 'emails/plain/before-renewal.php';

            // Triggers for this email
            add_action('woocommerce_before_renewal', array($this, 'trigger'));

//		// Other settings
//		$this->heading_downloadable = $this->get_option( 'heading_downloadable', __( 'We are sorry to see you have canceled your order.', 'woocommerce' ) );
//		$this->subject_downloadable = $this->get_option( 'subject_downloadable', __( 'Your {site_title} order from {order_date} has been canceled', 'woocommerce' ) );

            // Call parent constuctor
            parent::__construct();
        }

        /**
         * @param array $data
         */
        function trigger($data)
        {
            if (is_numeric($data)) {
                $order = new WC_Order($data);
            } else {
                $order = $data['order'];
            }

            if ($order) {
                $user = get_user_by( 'id', $order->user_id );
                $this->user = $user;
                $this->object = $order;
                $this->recipient = $this->object->billing_email;
            }

            if (!$this->is_enabled() || !$this->get_recipient()) {
                return;
            }

            $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
        }

        /**
         * get_subject function.
         *
         * @access public
         * @return string
         */
        function get_subject()
        {
            return $this->subject;
        }

        /**
         * get_heading function.
         *
         * @access public
         * @return string
         */
        function get_heading()
        {
            return $this->heading;
        }

        /**
         * get_content_html function.
         *
         * @access public
         * @return string
         */
        function get_content_html()
        {
            ob_start();
            wc_get_template($this->template_html, array(
                'order' => $this->object,
                'user' => $this->user,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text' => false
            ));

            return ob_get_clean();
        }

        /**
         * get_content_plain function.
         *
         * @access public
         * @return string
         */
        function get_content_plain()
        {
            ob_start();
            wc_get_template($this->template_plain, array(
                'order' => $this->object,
                'user' => $this->user,
                'email_heading' => $this->get_heading(),
                'sent_to_admin' => false,
                'plain_text' => true
            ));

            return ob_get_clean();
        }


    }

endif;

return new WC_Email_Customer_Before_Renewal();