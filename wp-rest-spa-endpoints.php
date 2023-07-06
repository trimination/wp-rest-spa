<?php
/*
Plugin Name: WP REST SPA Endpoints
Plugin URI:
Description: Provide useful REST endpoints for a Single Page Application
Version: 1.0.0
Author: trimination
Author URI: https://github.com/trimination
License: All rights reserved.
*/
require_once 'inc/Bootstrap.php';

function registerRoutes() {
    $controllers = [new PostController(), new MenuController()];

    foreach($controllers as $controller) {
        $controller->registerRoutes();
    }
}
add_action('rest_api_init', 'registerRoutes');

require_once 'inc/wprse-options.php';