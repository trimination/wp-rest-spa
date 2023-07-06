<?php

function wprse_options_page() {
    add_menu_page(
        'rest-spa',
        'rest-spa',
        'manage_options',
        'wprse_plugin',
        'wprse_settings_page'
    );
}

function wprse_enqueue_style() {
    $cssUrl = untrailingslashit(plugin_dir_url(__FILE__)) . '/css/style.css';
    wp_enqueue_style('wprse-settings-css', $cssUrl);
}

add_action('admin_head', 'wprse_enqueue_style', 10);

function wprse_enqueue_js() {
    $jsUrl = untrailingslashit(plugin_dir_url(__FILE__)) . '/js/wprse-async-save.js';
    wp_enqueue_script('wprse-sync-save', $jsUrl, array('jquery'));
    $data = array('admin_url' => get_admin_url());
    wp_localize_script('wprse-sync-save', 'wprse', $data);
}

add_action('admin_footer', 'wprse_enqueue_js', 10);

add_action('admin_menu', 'wprse_options_page');

function wprse_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_GET['settings-updated'])) {
        add_settings_error('wprse_messages', 'wprse_message', __('Settings Saved', 'wprse'), 'updated');
    }

    settings_errors('wprse_messages');

    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?> Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('wprse_plugin_settings');
            // output setting sections and their fields
            do_settings_sections('wprse_plugin');
            // output save settings button
            $args = array('id' => 'wprse-submit-button');
            submit_button('Save Settings', 'primary', 'submit', true, $args);
            ?>
        </form>
    </div>
    <?php
}


function wprse_register_settings() {
    register_setting('wprse_plugin_settings', 'wprse_more_posts_count');
    register_setting('wprse_plugin_settings', 'wprse_posts_per_page');

    add_settings_section(
        'wprse_settings_section',
        __('', 'wprse'),
        'wprse_settings_section_callback',
        'wprse_plugin'
    );

    add_settings_field(
        'wprse_posts_per_page',
        __('Posts Per Page:', 'wprse'),
        'wprse_settings_render_textfield',
        'wprse_plugin',
        'wprse_settings_section',
        array(
            'value' => get_option('wprse_posts_per_page', 10),
            'name' => 'posts_per_page'
        )
    );

    add_settings_field(
        'wprse_more_posts_count',
        __('Recent Posts Count: ', 'wprse_more_posts_count'),
        'wprse_settings_render_textfield',
        'wprse_plugin',
        'wprse_settings_section',
        array(
            'value' => get_option('wprse_more_posts_count', 3),
            'name' => 'more_posts_count'
        )
    );
}

add_action('admin_init', 'wprse_register_settings');

function wprse_validate_settings($input) {
    if ($input == 'on') {
        return "true";
    } else {
        return "false";
    }
}


function wprse_settings_render_textfield($args) {
    if (!isset($args)) {
        return;
    }
    $name = $args['name'];
    $value = $args['value'];
    echo "<input type='text' class='wprse-textfield' class='g-star-textarea' id='wprse_{$name}' value='$value'/>" . PHP_EOL;
}

function wprse_settings_section_callback() {
    echo "<p>Default data request limits</p>" . PHP_EOL;
}

function wprse_async_save() {
    $morePostsCount = $_POST['more_posts_count'] ?? false;
    $p3 = $_POST['posts_per_page'] ?? false;

    if (false === $morePostsCount || false === $p3) {
        die(
        json_encode(
            array(
                'success' => false,
                'message' => 'Missing required information. p3: ' . $p3 . ', mpc: ' . $morePostsCount
            )
        )
        );
    }

    update_option('wprse_more_posts_count', $morePostsCount);
    update_option('wprse_post_per_page', $p3);
    die(
    json_encode(
        array(
            'success' => true,
            'message' => 'Settings updated successfully.'
        )
    )
    );
}

add_action('wp_ajax_wprse_async_save', 'wprse_async_save');
