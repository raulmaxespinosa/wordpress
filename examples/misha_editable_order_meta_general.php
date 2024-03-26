<?php
/**
 * Add Custom Fields to Edit Order Page
 *
 * @author Misha Rudrastyh
 * @link https://rudrastyh.com/woocommerce/customize-order-details.html
 */
add_action( 'woocommerce_admin_order_data_after_order_details', 'misha_editable_order_meta_general' );

function misha_editable_order_meta_general( $order ){

	?>
		<br class="clear" />
		<h3>Gift Order <a href="#" class="edit_address">Edit</a></h3>
		<?php
			/*
			 * get all the meta data values we need
			 */
			$is_gift = $order->get_meta( 'is_gift' );
			$gift_wrap = $order->get_meta( 'gift_wrap' );
			$gift_name = $order->get_meta( '_weeks_number' );
			$gift_message = $order->get_meta( 'gift_message' );
		?>
		<div class="address">
			<p><strong>Is this a gift order?</strong><?php echo $is_gift ? 'Yes' : 'No' ?></p>
			<?php
				// we show the rest fields in this column only if this order is marked as a gift
				if( $is_gift ) :
				?>
					<p><strong>Gift Wrap:</strong> <?php echo esc_html( $gift_wrap ) ?></p>
					<p><strong>Recipient name:</strong> <?php echo esc_html( $gift_name ) ?></p>
					<p><strong>Gift message:</strong> <?php echo wpautop( esc_html( $gift_message ) ) ?></p>
				<?php
				endif;
			?>
		</div>
		<div class="edit_address">
			<?php

				woocommerce_wp_radio( array(
					'id' => 'is_gift',
					'label' => 'Is this a gift order?',
					'value' => $is_gift,
					'options' => array(
						'' => 'No',
						'1' => 'Yes'
					),
					'style' => 'width:16px', // required for checkboxes and radio buttons
					'wrapper_class' => 'form-field-wide' // always add this class
				) );

				woocommerce_wp_select( array(
					'id' => 'gift_wrap',
					'label' => 'Gift Wrap:',
					'value' => $gift_wrap,
					'options' => array(
						'' => 'No Wrap',
						'Basic Wrap' => 'Basic Wrap',
						'Magic Wrap' => 'Magic Wrap'
					),
					'wrapper_class' => 'form-field-wide'
				) );

				woocommerce_wp_text_input( array(
					'id' => 'gift_name',
					'label' => 'Recipient name:',
					'value' => $gift_name,
					'wrapper_class' => 'form-field-wide'
				) );

				woocommerce_wp_textarea_input( array(
					'id' => 'gift_message',
					'label' => 'Gift message:',
					'value' => $gift_message,
					'wrapper_class' => 'form-field-wide'
				) );

			?>
		</div>
	<?php 
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'misha_editable_order_meta_billing' );

function misha_editable_order_meta_billing( $order ){
	
	$contactmethod = $order->get_meta( 'contactmethod' );
	?>
		<div class="address">
			<p<?php if( ! $contactmethod ) { echo ' class="none_set"'; } ?>>
				<strong>Preferred Contact Method:</strong>
				<?php echo $contactmethod ? esc_html( $contactmethod ) : 'No contact method selected.' ?>
			</p>
		</div>
		<div class="edit_address">
			<?php
				woocommerce_wp_select( array(
					'id' => 'contactmethod',
					'label' => 'Preferred Contact Method',
					'wrapper_class' => 'form-field-wide',
					'value' => $contactmethod,
					'description' => 'Please, contact the customer only with the method selected here.',
					'options' => array(
						'By Phone' => 'By Phone', // option value == option name
						'By Email' => 'By Email'
					)
				) );
			?>
		</div>
	<?php
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'misha_editable_order_meta_shipping' );

function misha_editable_order_meta_shipping( $order ){

	$shippingdate = $order->get_meta( 'shippingdate' );

	?>
		<div class="address">
			<p<?php if( empty( $shippingdate ) ) { echo ' class="none_set"'; } ?>>
	 			<strong>Shipping date:</strong>
				<?php echo ! empty( $shippingdate ) ? $shippingdate : 'Anytime.' ?>
			</p>
		</div>
		<div class="edit_address">
			<?php
				woocommerce_wp_text_input( array(
					'id' => 'shippingdate',
					'label' => 'Shipping date',
					'wrapper_class' => 'form-field-wide',
					'class' => 'date-picker',
					'style' => 'width:100%',
					'value' => $shippingdate,
					'description' => 'This is the day, when the customer would like to receive his order.'
				) );
			?>
		</div>
	<?php
}

add_action( 'woocommerce_process_shop_order_meta', 'misha_save_general_details' );

function misha_save_general_details( $order_id ){
	
	$order = wc_get_order( $order_id );
	$order->update_meta_data( 'is_gift', wc_clean( $_POST[ 'is_gift' ] ) );
	$order->update_meta_data( 'gift_wrap', wc_clean( $_POST[ 'gift_wrap' ] ) );
	$order->update_meta_data( 'gift_name', wc_clean( $_POST[ 'gift_name' ] ) );
	$order->update_meta_data( 'gift_message', wc_sanitize_textarea( $_POST[ 'gift_message' ] ) );
	$order->update_meta_data( 'contactmethod', wc_clean( $_POST[ 'contactmethod' ] ) );
	$order->update_meta_data( 'shippingdate', wc_clean( $_POST[ 'shippingdate' ] ) );
	// wc_clean() and wc_sanitize_textarea() are WooCommerce sanitization functions
	$order->save();
	
}