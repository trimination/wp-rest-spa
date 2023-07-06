<?php
const WP_HTTP_BLOCK_EXTERNAL = TRUE;
const WP_ACCESSIBLE_HOSTS = ''; //csv

const PLUGIN_NAME = 'wp-rest-spa-endpoints';
const PLUGIN_NAME_FULL = 'WP REST SPA Endpoints';
const API_VERSION = '1';
const API_NAMESPACE = 'rest-spa/v';
require_once WP_PLUGIN_DIR .  '/' . PLUGIN_NAME . '/Controller/BaseController.php';
require_once WP_PLUGIN_DIR .  '/' . PLUGIN_NAME . '/Controller/PostController.php';
require_once WP_PLUGIN_DIR .  '/' . PLUGIN_NAME . '/Controller/MenuController.php';
