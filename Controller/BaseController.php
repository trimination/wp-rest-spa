<?php

class BaseController {
    protected function response($data, $statusCode = 200): WP_REST_Response {
        return new WP_REST_Response($data, $statusCode);
    }

    protected function error($data, $statusCode): WP_REST_Response {
        $data = ["error" => $data];
        return new WP_REST_Response($data, $statusCode);
    }

    protected function wpQuery($args) {
        return new WP_Query($args);
    }

    protected function filterData($data, $fields) {
        $newData = array();
        foreach ($fields as $field) {
            foreach ($data as $key => $value) {
                $newData[$key][$field] = $value->{$field};
            }
        }
        return $newData;
    }

    protected function mergePostsCats($posts) {
        $data = [];
        foreach($posts->posts as $post) {
            $data[] = ["post"=>$post, "categories"=>$this->getPostCategories($post->ID)];
        }
        return $data;
    }

    function getPostCategories($id) {
        return wp_get_post_categories($id, ["fields"=>"all"]);
    }
}