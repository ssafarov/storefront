<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Email_Customer_Order_Dispatched_Registration_Reminder' ) ) :

/**
 * Customer Cancel Order Email
 *
 * Order cancel emails are sent to the customer when the order is marked canceled.
 *
 * @class 		WC_Email_Customer_Cancelled_Order
 * @version		1.0.0
 * @package		WooCommerce/Classes/Emails
 * @author 		WooThemes
 * @extends 	WC_Email
 */
class WC_Email_Customer_Order_Dispatched_Registration_Reminder extends WC_Email {

	/**
	 * Constructor
	 */
	function __construct() {

		$this->id 				= 'customer_order_dispatched_register_reminder';
		$this->title 			= __( 'Order dispatched. Registration reminder', 'storefront' );
		$this->description		= __( 'Send the registration reminder emails to the customer when the order is marked dispatched.', 'storefront' );

		$this->heading 			= __( 'Don’t forget to register your SCANIFY', 'storefront' );
		$this->subject      	= __( 'Don’t forget to register your SCANIFY', 'storefront' );

		$this->template_html 	= 'emails/customer-order-dispatched-registration-reminder.php';
		$this->template_plain 	= 'emails/plain/customer-order-dispatched-registration-reminder.php';

		// Triggers for this email
		add_action( 'woocommerce_order_delivered_register_reminder', array( $this, 'trigger' ) );

		// Other settings
		$this->heading_downloadable = $this->get_option( 'heading_downloadable', __( 'Don’t forget to register your SCANIFY', 'woocommerce' ) );
		$this->subject_downloadable = $this->get_option( 'subject_downloadable', __( 'Don’t forget to register your SCANIFY', 'woocommerce' ) );

		// Call parent constuctor
		parent::__construct();
	}

	/**
	 * trigger function.
	 *
	 * @access public
	 * @return void
	 */
	function trigger( $order_id ) {

		if ( $order_id ) {
			$this->object 		= new WC_Order( $order_id );
			$this->recipient	= $this->object->billing_email;

			$this->find[] = '{order_date}';
			$this->replace[] = date_i18n( wc_date_format(), strtotime( $this->object->order_date ) );

			$this->find[] = '{order_number}';
			$this->replace[] = $this->object->get_order_number();
		}

		if ( ! $this->is_enabled() || ! $this->get_recipient() )
			return;

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * get_subject function.
	 *
	 * @access public
	 * @return string
	 */
	function get_subject() {
		if ( ! empty( $this->object ) && $this->object->has_downloadable_item() )
			return apply_filters( 'woocommerce_email_subject_customer_order_dispatched_register_reminder', $this->format_string( $this->subject_downloadable ), $this->object );
		else
			return apply_filters( 'woocommerce_email_subject_customer_order_dispatched_register_reminder', $this->format_string( $this->subject ), $this->object );
	}

	/**
	 * get_heading function.
	 *
	 * @access public
	 * @return string
	 */
	function get_heading() {
		if ( ! empty( $this->object ) && $this->object->has_downloadable_item() )
			return apply_filters( 'woocommerce_email_heading_customer_order_dispatched_register_reminder', $this->format_string( $this->heading_downloadable ), $this->object );
		else
			return apply_filters( 'woocommerce_email_heading_customer_order_dispatched_register_reminder', $this->format_string( $this->heading ), $this->object );
	}

	/**
	 * get_content_html function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_html() {
		ob_start();
		wc_get_template( $this->template_html, array(
			'order' 		=> $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => false
		) );
		return ob_get_clean();
	}

	/**
	 * get_content_plain function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_plain() {
		ob_start();
		wc_get_template( $this->template_plain, array(
			'order' 		=> $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => true
		) );
		return ob_get_clean();
	}

    /**
     * Initialise Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {
    	$this->form_fields = array(
			'enabled' => array(
				'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
				'type' 			=> 'checkbox',
				'label' 		=> __( 'Enable this email notification', 'woocommerce' ),
				'default' 		=> 'yes'
			),
			'subject' => array(
				'title' 		=> __( 'Subject', 'woocommerce' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Defaults to <code>%s</code>', 'woocommerce' ), $this->subject ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'heading' => array(
				'title' 		=> __( 'Email Heading', 'woocommerce' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Defaults to <code>%s</code>', 'woocommerce' ), $this->heading ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'subject_downloadable' => array(
				'title' 		=> __( 'Subject (downloadable)', 'woocommerce' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Defaults to <code>%s</code>', 'woocommerce' ), $this->subject_downloadable ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'heading_downloadable' => array(
				'title' 		=> __( 'Email Heading (downloadable)', 'woocommerce' ),
				'type' 			=> 'text',
				'description' 	=> sprintf( __( 'Defaults to <code>%s</code>', 'woocommerce' ), $this->heading_downloadable ),
				'placeholder' 	=> '',
				'default' 		=> ''
			),
			'email_type' => array(
				'title' 		=> __( 'Email type', 'woocommerce' ),
				'type' 			=> 'select',
				'description' 	=> __( 'Choose which format of email to send.', 'woocommerce' ),
				'default' 		=> 'html',
				'class'			=> 'email_type',
				'options'		=> array(
					'plain'	 	=> __( 'Plain text', 'woocommerce' ),
					'html' 			=> __( 'HTML', 'woocommerce' ),
					'multipart' 	=> __( 'Multipart', 'woocommerce' ),
				)
			)
		);
    }
}

endif;

return new WC_Email_Customer_Order_Dispatched_Registration_Reminder();