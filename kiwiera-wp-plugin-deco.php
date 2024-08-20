<?php
/**
 * Plugin Name: Website Decorations
 * Description: A plugin that allows users to add decorations to their e-commerce site.
 * Author: Jamie Corstorphine
 * Version: 1.0.0
 * Text Domain: website-decorations
 * 
 */

add_action('admin_menu', 'sid_add_admin_menu');

function sid_add_admin_menu()
{
    add_menu_page(
        'Simple Image Display',  // Page title
        'Image Display',         // Menu title
        'manage_options',        // Capability
        'simple-image-display',  // Menu slug
        'sid_admin_page',        // Function to display the content
        'dashicons-format-image' // Icon
    );
}

// Function to display the admin page
function sid_admin_page()
{
    ?>
    <div class="wrap">
        <h1>Upload and Display Image</h1>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="sid_image_upload" />
            <input type="submit" name="sid_image_submit" value="Upload Image" class="button-primary" />
        </form>
    </div>
    <?php

    // Handle the file upload
    if (isset($_POST['sid_image_submit'])) {
        sid_handle_image_upload();
    }
}

// Function to handle image upload
function sid_handle_image_upload()
{
    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    $uploadedfile = $_FILES['sid_image_upload'];
    $upload_overrides = array('test_form' => false);

    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

    if ($movefile && !isset($movefile['error'])) {
        update_option('sid_image_url', $movefile['url']);
        echo "<div class='updated'><p>Image uploaded successfully!</p></div>";
    } else {
        echo "<div class='error'><p>Image upload failed: " . $movefile['error'] . "</p></div>";
    }
}

// Shortcode to display the uploaded image on the site
add_shortcode('display_image', 'sid_display_image_shortcode');

function sid_display_image_shortcode()
{
    $image_url = get_option('sid_image_url');
    if ($image_url) {
        return '<img src="' . esc_url($image_url) . '" alt="Uploaded Image" />';
    } else {
        return '<p>No image uploaded yet.</p>';
    }
}
