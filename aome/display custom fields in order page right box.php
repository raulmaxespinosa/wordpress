<?php
add_action('add_meta_boxes', 'add_custom_info_metabox');
add_action('woocommerce_process_shop_order_meta', 'save_order_fields', 10, 2);

function add_custom_info_metabox() {
    add_meta_box(
        'woocommerce-order-my-custom',
        'Pregnancy Information',
        'order_fields',
        'shop_order',
        'side',
        'default'
    );
}

function order_fields() {
    global $post;

    $order = wc_get_order($post->ID);
    $weeks_number = get_post_meta($order->get_id(), '_weeks_number', true);
    $due_date = get_post_meta($order->get_id(), '_due_date', true);
    $additional_info = get_post_meta($order->get_id(), '_additional_info', true);

    echo '<table class="form-table"><tbody>';

    echo '<tr><th>How many weeks pregnant today:</th><td>';
    echo $weeks_number;
    echo '</td></tr>';

    echo '<tr><th>Pregnancy Due Date:</th><td>';
    echo $due_date;
    echo '</td></tr>';

    echo '<tr><th>Additional Information:</th><td>';
    echo $additional_info;
    echo '</td></tr>';

    echo '</tbody></table>';
}

function save_order_fields($post_id, $post) {
    update_post_meta($post_id, '_weeks_number', wc_clean($_POST['_weeks_number']));
    update_post_meta($post_id, '_due_date', wc_clean($_POST['_due_date']));
    update_post_meta($post_id, '_additional_info', wc_clean($_POST['_additional_info']));
}
