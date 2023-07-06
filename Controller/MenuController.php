<?php

class MenuController extends BaseController {

    function getMenuItems(WP_REST_Request $request) {
        $menuId = $request->get_param('id') ?? false;
        if (false !== $menuId) {
            $items = wp_get_nav_menu_items( $menuId );
            if(false !== $items){
                $items = $this->filterData($items, ['title', 'url']);
                return $this->response($items, 200);
            }
        }
        return $this->response(["error" => "id not provided or id is invalid"], 400);

    }

    function registerRoutes() {
        $version = API_VERSION;
        $namespace = API_NAMESPACE . $version;
        register_rest_route($namespace, '/menu', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'getMenuItems'),
            'permission_callback' => '__return_true',
        ));
    }
}