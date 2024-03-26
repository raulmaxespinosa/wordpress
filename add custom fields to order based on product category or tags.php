<?php
// Add custom section before billing details
add_action('woocommerce_before_checkout_billing_form', 'custom_checkout_section');

function custom_checkout_section() {
    // Define the product categories to check
    $categories_to_check = array('Ultrasound Packages', 'Another Category', 'Yet Another Category');
    // Define the product tags to check
    $tags_to_check = array('Tag1', 'Tag2', 'Tag3');

    // Check if any product in the cart belongs to the defined categories or tags
    $category_check = false;
    $tag_check = false;
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        foreach ($categories_to_check as $category) {
            if ( has_term( $category, 'product_cat', $cart_item['product_id'] ) ) {
                $category_check = true;
                break;
            }
        }
        foreach ($tags_to_check as $tag) {
            if ( has_term( $tag, 'product_tag', $cart_item['product_id'] ) ) {
                $tag_check = true;
                break;
            }
        }
        if ($category_check || $tag_check) {
            break;
        }
    }

    // If no product from the defined categories or tags is in the cart, return
    if ( ! $category_check && ! $tag_check ) {
        return;
    }

    echo '<div id="custom-checkout-section">';
    echo '<h3>Pregnancy Information</h3>';
    echo '<p>Please provide the following details:</p>';

    // Security contact person text field
    woocommerce_form_field('weeks_number', array(
        'type' => 'text',
        'class' => array('form-row-first'),
        'label' => 'How many weeks pregnant today?',
        'required' => true,
    ));

    // Due date field
    woocommerce_form_field('due_date', array(
        'type' => 'date',
        'class' => array('form-row-last'),
        'label' => 'Pregnancy Due Date',
        'required' => true,
    ));

    // Additional information text area
    woocommerce_form_field('additional_info', array(
        'type' => 'textarea',
        'class' => array('form-row-wide'),
        'label' => 'Additional Information',
        'required' => false,
        'placeholder' => 'Enter any additional details here...',
    ));

    // Disclaimer checkbox
    woocommerce_form_field('disclaimer_checkbox', array(
        'type' => 'checkbox',
        'class' => array('form-row-wide'),
        'label' => 'I confirm I have a verified pregnancy and am under the care of a doctor or midwife.',
        'required' => true,
    ));

    // Disclaimer checkbox
    woocommerce_form_field('cancelation_fee_checkbox', array(
        'type' => 'checkbox',
        'class' => array('form-row-wide'),
        'label' => 'If I do not cancel or reschedule my appointment with at least 24 hours notice, I agree to pay a $20 non-refundable no show fee.',
        'required' => true,
    ));

    echo '</div>';
}

// Validate checkout form submission
add_action('woocommerce_checkout_process', 'validate_checkout_fields');

function validate_checkout_fields() {
    if (empty($_POST['disclaimer_checkbox'])) {
        wc_add_notice('Please agree to the disclaimer.', 'error');
    }
}

// Save custom fields to order meta data
add_action('woocommerce_checkout_create_order', 'save_custom_fields_to_order');

function save_custom_fields_to_order($order) {
    if (!empty($_POST['weeks_number'])) {
        $order->update_meta_data('_weeks_number', sanitize_text_field($_POST['weeks_number']));
    }

    if (!empty($_POST['due_date'])) {
        $order->update_meta_data('_due_date', sanitize_text_field($_POST['due_date']));
    }

    if (!empty($_POST['additional_info'])) {
        $order->update_meta_data('_additional_info', sanitize_text_field($_POST['additional_info']));
    }

    if (!empty($_POST['cancelation_fee_checkbox'])) {
        $order->update_meta_data('_cancelation_fee_checkbox', sanitize_text_field($_POST['cancelation_fee_checkbox']));
    }
}