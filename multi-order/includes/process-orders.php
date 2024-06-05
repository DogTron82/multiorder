<?php
function mos_process_order_scheme() {
    if (isset($_POST['recipient_name'])) {
        $recipient_names = $_POST['recipient_name'];
        $recipient_addresses = $_POST['recipient_address'];
        $recipient_addresses_2 = $_POST['recipient_address_2'];
        $recipient_cities = $_POST['recipient_city'];
        $recipient_postcodes = $_POST['recipient_postcode'];
        $recipient_countries = $_POST['recipient_country'];
        $recipient_phones = $_POST['recipient_phone'];
        $order_items_array = $_POST['order_items'];
        $order_quantities_array = $_POST['order_quantity'];

        foreach ($recipient_names as $index => $recipient_name) {
            $recipient_address = $recipient_addresses[$index];
            $recipient_address_2 = $recipient_addresses_2[$index];
            $recipient_city = $recipient_cities[$index];
            $recipient_postcode = $recipient_postcodes[$index];
            $recipient_country = $recipient_countries[$index];
            $recipient_phone = $recipient_phones[$index];
            $order_items = $order_items_array[$index + 1];
            $order_quantities = $order_quantities_array[$index + 1];

            // Create a new order
            $order = wc_create_order();
            
            // Add products to the order
            foreach ($order_items as $item_index => $item_id) {
                $product = wc_get_product(trim($item_id));
                $quantity = isset($order_quantities[$item_index]) ? intval($order_quantities[$item_index]) : 1;
                if ($product) {
                    $order->add_product($product, $quantity);
                }
            }

            // Set address
            $address = array(
                'first_name' => $recipient_name,
                'address_1' => $recipient_address,
                'address_2' => $recipient_address_2,
                'city'       => $recipient_city,
                'postcode'   => $recipient_postcode,
                'country'    => $recipient_country,
                'phone'      => $recipient_phone,
            );

            $order->set_address($address, 'shipping');
            $order->set_address($address, 'billing');

            // Calculate totals and save the order
            $order->calculate_totals();
            $order->save();

            // Set the order status to processing
            $order->update_status('processing');
        }

        wp_send_json_success('Ordrer er tillagde');
    } else {
        wp_send_json_error('Ingen data mottatt');
    }
}

add_action('wp_ajax_mos_process_order_scheme', 'mos_process_order_scheme');
add_action('wp_ajax_nopriv_mos_process_order_scheme', 'mos_process_order_scheme');
?>
