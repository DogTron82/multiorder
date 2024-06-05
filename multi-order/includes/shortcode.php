<?php
function mos_order_scheme_shortcode() {
    $products = wc_get_products(array('limit' => -1));

    ob_start();
    ?>

    <form id="order-scheme-form" method="POST">
        <div id="order-scheme-container">
            <div class="order-scheme-row order-scheme-container clearfix">
                <h3>Bestilling #1</h3>
                <p class="half-width">
                    <label for="recipient_name_1">Fornavn</label>
                    <input type="text" id="recipient_name_1" name="recipient_name[]">
                </p>
                <p class="half-width">
                    <label for="recipient_last_name_1">Etternavn</label>
                    <input type="text" id="recipient_last_name_1" name="recipient_last_name[]">
                </p>
                <p class="full-width">
                    <label for="recipient_address_1">Adresse</label>
                    <input type="text" id="recipient_address_1" name="recipient_address[]">
                </p>
                <p class="full-width">
                    <label for="recipient_address_2_1">Leilighet, suite, etc. (valgfritt)</label>
                    <input type="text" id="recipient_address_2_1" name="recipient_address_2[]">
                </p>
                <p class="half-width">
                    <label for="recipient_postcode_1">Postnummer</label>
                    <input type="text" id="recipient_postcode_1" name="recipient_postcode[]">
                </p>
                <p class="half-width">
                    <label for="recipient_city_1">By</label>
                    <input type="text" id="recipient_city_1" name="recipient_city[]">
                </p>
                <p class="full-width">
                    <label for="recipient_country_1">Land</label>
                    <input type="text" id="recipient_country_1" name="recipient_country[]" value="Norway" readonly>
                </p>
                <p class="full-width">
                    <label for="recipient_phone_1">Telefon (valgfritt)</label>
                    <input type="text" id="recipient_phone_1" name="recipient_phone[]">
                </p>
                <p class="full-width">
                    <label for="order_items_1">Bestillingsvarer</label>
                </p>
                <div class="available-products" id="available_products_1">
                    <?php
                    foreach ($products as $product) {
                        echo '<div class="product-item" data-product-id="' . $product->get_id() . '">
                                <img src="' . wp_get_attachment_url($product->get_image_id()) . '" alt="' . $product->get_name() . '" width="50" height="50">
                                <span>' . $product->get_name() . '</span>
                                <button type="button" class="add-product">Legg til</button>
                            </div>';
                    }
                    ?>
                </div>
                <div class="selected-products" id="selected_products_1"></div>
                <p>
                    <button type="button" class="clone-order">Gjenbruk produktvalg</button>
                </p>
            </div>
        </div>
        <p>
            <button type="button" id="add-more-orders">Legg til flere bestillinger</button>
        </p>
        <p>
            <input type="button" id="submit-order-scheme" value="Send inn bestillinger">
        </p>
    </form>

    <script>
        let orderCount = 1;
        const products = <?php echo json_encode(array_map(function($product) {
            return [
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'image' => wp_get_attachment_url($product->get_image_id()),
                'price' => $product->get_price()
            ];
        }, $products)); ?>;

        function updateSelectedProducts(orderIndex, productId, productName, productImage) {
            const selectedProductsContainer = document.getElementById(`selected_products_${orderIndex}`);
            const existingProduct = selectedProductsContainer.querySelector(`.product-row[data-product-id="${productId}"]`);

            if (!existingProduct) {
                const productRow = document.createElement('div');
                productRow.classList.add('product-row');
                productRow.setAttribute('data-product-id', productId);
                productRow.innerHTML = `
                    <img src="${productImage}" alt="${productName}" width="50" height="50">
                    <span>${productName}</span>
                    <input type="number" name="order_quantity[${orderIndex}][]" value="1" min="1" class="quantity-selector" placeholder="Antall">
                `;
                selectedProductsContainer.appendChild(productRow);
            }
        }

        function cloneOrder(orderIndex) {
            orderCount++;
            const container = document.getElementById('order-scheme-container');
            const newOrderRow = document.createElement('div');
            newOrderRow.classList.add('order-scheme-row', 'order-scheme-container', 'clearfix');
            newOrderRow.innerHTML = `
                <h3>Bestilling #${orderCount}</h3>
                <p class="half-width">
                    <label for="recipient_name_${orderCount}">Fornavn</label>
                    <input type="text" id="recipient_name_${orderCount}" name="recipient_name[]">
                </p>
                <p class="half-width">
                    <label for="recipient_last_name_${orderCount}">Etternavn</label>
                    <input type="text" id="recipient_last_name_${orderCount}" name="recipient_last_name[]">
                </p>
                <p class="full-width">
                    <label for="recipient_address_${orderCount}">Adresse</label>
                    <input type="text" id="recipient_address_${orderCount}" name="recipient_address[]">
                </p>
                <p class="full-width">
                    <label for="recipient_address_2_${orderCount}">Leilighet, suite, etc. (valgfritt)</label>
                    <input type="text" id="recipient_address_2_${orderCount}" name="recipient_address_2[]">
                </p>
                <p class="half-width">
                    <label for="recipient_postcode_${orderCount}">Postnummer</label>
                    <input type="text" id="recipient_postcode_${orderCount}" name="recipient_postcode[]">
                </p>
                <p class="half-width">
                    <label for="recipient_city_${orderCount}">By</label>
                    <input type="text" id="recipient_city_${orderCount}" name="recipient_city[]">
                </p>
                <p class="full-width">
                    <label for="recipient_country_${orderCount}">Land</label>
                    <input type="text" id="recipient_country_${orderCount}" name="recipient_country[]" value="Norway" readonly>
                </p>
                <p class="full-width">
                    <label for="recipient_phone_${orderCount}">Telefon (valgfritt)</label>
                    <input type="text" id="recipient_phone_${orderCount}" name="recipient_phone[]">
                </p>
                <p class="full-width">
                    <label for="order_items_${orderCount}">Bestillingsvarer</label>
                </p>
                <div class="available-products" id="available_products_${orderCount}">
                    <?php
                    foreach ($products as $product) {
                        echo '<div class="product-item" data-product-id="' . $product->get_id() . '">
                                <img src="' . wp_get_attachment_url($product->get_image_id()) . '" alt="' . $product->get_name() . '" width="50" height="50">
                                <span>' . $product->get_name() . '</span>
                                <button type="button" class="add-product">Legg til</button>
                            </div>';
                    }
                    ?>
                </div>
                <div class="selected-products" id="selected_products_${orderCount}"></div>
                <p>
                    <button type="button" class="clone-order">Klon bestilling</button>
                </p>
            `;
            container.appendChild(newOrderRow);

            document.querySelectorAll(`#available_products_${orderCount} .add-product`).forEach(button => {
                button.addEventListener('click', function() {
                    const productItem = this.closest('.product-item');
                    const productId = productItem.getAttribute('data-product-id');
                    const productName = productItem.querySelector('span').innerText;
                    const productImage = productItem.querySelector('img').src;

                    updateSelectedProducts(orderCount, productId, productName, productImage);
                });
            });

            document.querySelectorAll(`#selected_products_${orderIndex} .product-row`).forEach(productRow => {
                const productId = productRow.getAttribute('data-product-id');
                const productName = productRow.querySelector('span').innerText;
                const productImage = productRow.querySelector('img').src;

                updateSelectedProducts(orderCount, productId, productName, productImage);
            });

            newOrderRow.querySelector('.clone-order').addEventListener('click', function() {
                cloneOrder(orderCount);
            });
        }

        document.getElementById('add-more-orders').addEventListener('click', function() {
            orderCount++;
            const container = document.getElementById('order-scheme-container');
            const newOrderRow = document.createElement('div');
            newOrderRow.classList.add('order-scheme-row', 'order-scheme-container', 'clearfix');
            newOrderRow.innerHTML = `
                <h3>Bestilling #${orderCount}</h3>
                <p class="half-width">
                    <label for="recipient_name_${orderCount}">Fornavn</label>
                    <input type="text" id="recipient_name_${orderCount}" name="recipient_name[]">
                </p>
                <p class="half-width">
                    <label for="recipient_last_name_${orderCount}">Etternavn</label>
                    <input type="text" id="recipient_last_name_${orderCount}" name="recipient_last_name[]">
                </p>
                <p class="full-width">
                    <label for="recipient_address_${orderCount}">Adresse</label>
                    <input type="text" id="recipient_address_${orderCount}" name="recipient_address[]">
                </p>
                <p class="full-width">
                    <label for="recipient_address_2_${orderCount}">Leilighet, suite, etc. (valgfritt)</label>
                    <input type="text" id="recipient_address_2_${orderCount}" name="recipient_address_2[]">
                </p>
                <p class="half-width">
                    <label for="recipient_postcode_${orderCount}">Postnummer</label>
                    <input type="text" id="recipient_postcode_${orderCount}" name="recipient_postcode[]">
                </p>
                <p class="half-width">
                    <label for="recipient_city_${orderCount}">By</label>
                    <input type="text" id="recipient_city_${orderCount}" name="recipient_city[]">
                </p>
                <p class="full-width">
                    <label for="recipient_country_${orderCount}">Land</label>
                    <input type="text" id="recipient_country_${orderCount}" name="recipient_country[]" value="Norway" readonly>
                </p>
                <p class="full-width">
                    <label for="recipient_phone_${orderCount}">Telefon (valgfritt)</label>
                    <input type="text" id="recipient_phone_${orderCount}" name="recipient_phone[]">
                </p>
                <p class="full-width">
                    <label for="order_items_${orderCount}">Bestillingsvarer</label>
                </p>
                <div class="available-products" id="available_products_${orderCount}">
                    <?php
                    foreach ($products as $product) {
                        echo '<div class="product-item" data-product-id="' . $product->get_id() . '">
                                <img src="' . wp_get_attachment_url($product->get_image_id()) . '" alt="' . $product->get_name() . '" width="50" height="50">
                                <span>' . $product->get_name() . '</span>
                                <button type="button" class="add-product">Legg til</button>
                            </div>';
                    }
                    ?>
                </div>
                <div class="selected-products" id="selected_products_${orderCount}"></div>
                <p>
                    <button type="button" class="clone-order">Klon bestilling</button>
                </p>
            `;
            container.appendChild(newOrderRow);

            document.querySelectorAll(`#available_products_${orderCount} .add-product`).forEach(button => {
                button.addEventListener('click', function() {
                    const productItem = this.closest('.product-item');
                    const productId = productItem.getAttribute('data-product-id');
                    const productName = productItem.querySelector('span').innerText;
                    const productImage = productItem.querySelector('img').src;

                    updateSelectedProducts(orderCount, productId, productName, productImage);
                });
            });

            newOrderRow.querySelector('.clone-order').addEventListener('click', function() {
                cloneOrder(orderCount);
            });
        });

        document.querySelectorAll('.add-product').forEach(button => {
            button.addEventListener('click', function() {
                const productItem = this.closest('.product-item');
                const productId = productItem.getAttribute('data-product-id');
                const productName = productItem.querySelector('span').innerText;
                const productImage = productItem.querySelector('img').src;

                updateSelectedProducts(1, productId, productName, productImage);
            });
        });

        document.querySelectorAll('.clone-order').forEach(button => {
            button.addEventListener('click', function() {
                cloneOrder(1);
            });
        });

        jQuery(document).ready(function($) {
            $('#submit-order-scheme').on('click', function() {
                var formData = $('#order-scheme-form').serialize();
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: formData + '&action=mos_process_order_scheme',
                    success: function(response) {
                        alert('Ordrer er tillagde');
                    },
                    error: function(response) {
                        alert('Det oppsto en feil. Vennligst prøv igjen.');
                    }
                });
            });
        });
    </script>

    <?php
    return ob_get_clean();
}

add_shortcode('multi_order_scheme', 'mos_order_scheme_shortcode');
?>
