<?php
/**
 * Plugin Name: Sales Booster Live Viewing
 * Description: Displays the fake view counts and total sales to boost sales
 * Plugin URI:
 * Author: Khizar
 * Version: 1.0
 * Author URI: 
 * Text Domain: sales_booster_live_viewing
 */

 function enqueue_my_styles() {
    wp_enqueue_style('my-custom-style', plugins_url('style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'enqueue_my_styles');


function register_sales_boost_page() {
    add_menu_page(
        'Sales Booster Live Viewing',
        'Sales Booster Live Viewing',
        'manage_options',
        'sales_booster_live_viewing',
        'render_sales_booster_live_viewing',
        'dashicons-visibility',
        20
    );
}
add_action('admin_menu', 'register_sales_boost_page');

if (isset($_POST['vc_min_range'])) {
    $min_range = (int) sanitize_text_field($_POST['vc_min_range']);
    $max_range = (int) sanitize_text_field($_POST['vc_max_range']);
    if ($min_range <= $max_range) {
        update_option(
            'fake_view_count_range',
            array(
                'min_range' => $min_range,
                'max_range' => $max_range
            )
        );
        echo '<p class="success">View count range saved successfully!</p>';
    } else {
        echo '<p class="error">Invalid range: Min range must be less than or equal to max range.</p>';
    }
}

if (isset($_POST['sc_min_range'])) {
    $min_range = (int) sanitize_text_field($_POST['sc_min_range']);
    $max_range = (int) sanitize_text_field($_POST['sc_max_range']);
    if ($min_range <= $max_range) {
        update_option(
            'fake_sales_count_range',
            array(
                'min_range' => $min_range,
                'max_range' => $max_range
            )
        );
    } else {
        echo '<p class="error">Invalid range: Min range must be less than or equal to max range.</p>';
    }
}

function render_sales_booster_live_viewing() {
    enqueue_my_styles();
    $vc_saved_range = get_option('fake_view_count_range'); ?>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-...your-sha-hash-here..." crossorigin="anonymous" />

</head>
    <div id="sales-booster-forms">
        <div class="live_views_form">
            <form method="post">
                <h2>Sales Booster Live Viewing</h2>
                <h4>Select View Count Range</h4>
                <label for="vc_min_range">Min Range</label>
                <input type="number" name="vc_min_range" value="<?php echo isset($vc_saved_range['min_range']) ? $vc_saved_range['min_range'] : ''; ?>">
                <label for="vc_max_range">Max Range</label>
                <input type="number" name="vc_max_range" value="<?php echo isset($vc_saved_range['max_range']) ? $vc_saved_range['max_range'] : ''; ?>">
                <p><input type="submit" value="Submit Data" class="btn"></p>
            </form>
        </div>
        <div class="sales_count_form">
            <form method="post">
                <?php $sc_saved_range = get_option('fake_sales_count_range'); ?>
                <h2>Sales Booster Sales Count Range</h2>
                <h4>Select Sales Count Range</h4>
                <label for="sc_min_range">Min Range</label>
                <input type="number" name="sc_min_range" value="<?php echo isset($sc_saved_range['min_range']) ? $sc_saved_range['min_range'] : ''; ?>">
                <label for="sc_max_range">Max Range</label>
                <input type="number" name="sc_max_range" value="<?php echo isset($sc_saved_range['max_range']) ? $sc_saved_range['max_range'] : ''; ?>">
                <p><input type="submit" value="Submit Data" class="btn"></p>
            </form>
        </div>
    </div>
    <?php
}

function vc_random_number() {
    $vc_saved_range = get_option('fake_view_count_range');
    if ($vc_saved_range) {
        $min_value = $vc_saved_range['min_range'];
        $max_value = $vc_saved_range['max_range'];
        return rand($min_value, $max_value);
    } else {
        return rand(10, 30);
    }
}

function sc_random_number() {
    $vc_saved_range = get_option('fake_sales_count_range');
    if ($vc_saved_range) {
        $min_value = $vc_saved_range['min_range'];
        $max_value = $vc_saved_range['max_range'];
        return rand($min_value, $max_value);
    }
}

function ecommercehints_woocommerce_after_price() {
    $number = vc_random_number();
    $number2 = sc_random_number();
    echo '<div id="sales-booster" class="sales-booster-css">
            <div>
                <span class=" icon-css" style="position: relative; top: 2px; left: 2px;">
<svg xmlns="http://www.w3.org/2000/svg" width="20px" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></span>
                <span class="galactic_watch_count"> ' . $number . ' people are watching<br></span>
            </div>
            <div>
                <span class="dashicons icon-css">🔥</span>
                <span class="galactic_watch_count"> ' . $number2 . ' sales in the last 3 hours </span>
            </div>
        </div>';
}
add_action('woocommerce_single_product_summary', 'ecommercehints_woocommerce_after_price', 12);

?>
