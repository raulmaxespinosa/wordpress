<?php
/*
 * This script is designed to display custom fields on the WooCommerce order page.
 */

// Display custom fields in the order details page
add_action('woocommerce_admin_order_data_after_order_details', 'display_order_data_in_order');

function display_order_data_in_order($order) {

    $weeks_number = $order->get_meta( '_weeks_number' );
    $due_date = $order->get_meta( '_due_date' );
    $additional_info = $order->get_meta( '_additional_info' );
    $cancelation_fee_checkbox = $order->get_meta( '_cancelation_fee_checkbox' );
    

    echo '<br class="clear" /><h3>Pregnancy Information</h3>';

    // Display weeks number
    echo '<p><strong>How many weeks pregnant today:</strong> ';
    echo $weeks_number;
    echo '</p>';

    // Display due date
    echo '<p><strong>Pregnancy Due Date:</strong> ';
    echo $due_date;
    echo '</p>';

     // Display cancellation fee check
     echo '<p><strong>Acept cancellation fee:</strong> ';
     echo $cancelation_fee_checkbox ? 'Yes' : 'No';
     echo '</p>';

    // Display additional info
    echo '<p><strong>Additional Information:</strong> ';
    echo $additional_info;
    echo '</p>';
}