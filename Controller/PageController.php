<?php

class PageController extends BaseController {
    function getPostsForPage(WP_REST_Request $request) {
        $slug = $request->get_param('slug') ?? false;
        $cat = get_category_by_slug($slug);
        $catId = $cat->term_id;
        $count = $request->get_param('count') ?? get_option('wprse_posts_per_page', 10);

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $count,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'cat' => $catId
        );

        $posts = $this->wpQuery($args);
        $data = $this->mergePostsCats($posts);
        return $this->response($data, 200);
    }

    function getPage(WP_REST_Request $request) {
        $slug = $request->get_param('slug') ?? false;
        $forcePostPerPage  = $request->get_param('force-post') ?? false;

        if(false === $slug)
            return $this->error("Not Found", 404);

        if(false !== $forcePostPerPage)
            return $this->getPostsForPage($request);

        $args = array(
            'name'        => $slug,
            'post_type'   => 'page',
            'post_status' => 'publish',
            'posts_per_page' => 1
        );

        $post = $this->wpQuery($args);
        return $this->response($post->posts, 200);
    }

    function registerRoutes() {
        $version = API_VERSION;
        $namespace = API_NAMESPACE . $version;
        register_rest_route($namespace, '/page/(?P<slug>\S+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'getPage'),
            'permission_callback' => '__return_true',
        ));
    }
}