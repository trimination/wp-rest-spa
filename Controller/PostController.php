<?php
class PostController extends BaseController {
    function getRecentPosts(WP_REST_Request $request) {
        $count = get_option('wprse_more_posts_count', 3);
        $slug  = $request->get_param('category') ?? false;
        $limit  = $request->get_param('count') ?? false;
        $count = false !== $limit ? $limit : $count;

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $count,
            'orderby' => 'post_date',
            'order' => 'DESC'
        );

        if (false !== $slug)
            $args['cat'] = get_cat_id($slug);

        $posts = $this->wpQuery($args);
        $data = $this->mergePostsCats($posts);
        return $this->response($data, 200);
    }

    function getPosts(WP_REST_Request $request) {
        $p3 = get_option('wprse_posts_per_page', 10);
        $limit  = $request->get_param('count') ?? false;
        $p3 = false !== $limit ? $limit : $p3;

        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $p3
        );
        $cat = $request->get_param('category') ?? false;
        $exclude = $request->get_param('exclude') ?? false;

        if (false !== $cat)
            $args['category_name'] = $cat;
        if (false !== $exclude) {
            $ids = explode(',', $exclude);
            if (count($ids) > 1)
                $args['post__not_in'] = $ids;
            else
                $args['post__not_in'] = [$exclude];
        }

        $page = $request->get_param('page') ?? false;
        if(false !== $page && $page > 0) {
            $args['offset'] = $p3 * $page;
        }
        $posts = $this->wpQuery($args);
        $data = $this->mergePostsCats($posts);
        return $this->response($data, 200);
    }

    function getFeaturedPost(WP_REST_Request $request) {
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'category_name' => 'featured'
        );
        $cat = $request->get_param('category') ?? false;
        if (false !== $cat) {
            $cat = get_category_by_slug($cat);
            $parent_cat_id = $cat->term_id;
            $featured_cat_id = get_cat_ID('featured');
            $args = array(
                'posts_per_page' => 1,
                'post_type' => 'post',
                'post_status' => 'publish',
                'category__in' => [$featured_cat_id],
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'term_id',
                        'include_children' => true,
                        'operator' => 'IN',
                        'terms' => $parent_cat_id
                    ),
                ),
            );
        }
        $fp = $this->wpQuery($args);
        $data = $this->mergePostsCats($fp);
        return $this->response($data, 200);
    }

    function getPostBySlug(WP_REST_Request $request) {
        $slug = $request->get_param('slug') ?? false;
        if(false === $slug)
            return $this->error("Not Found", 404);

        $args = array(
            'name'        => $slug,
            'post_type'   => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 1
        );

        $posts = $this->wpQuery($args);
        $data = $this->mergePostsCats($posts);
        return $this->response($data, 200);
    }

    function registerRoutes() {
        $version = API_VERSION;
        $namespace = API_NAMESPACE . $version;
        register_rest_route($namespace, '/posts', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'getPosts'),
            'permission_callback' => '__return_true',
        ));
        register_rest_route($namespace, '/posts/featured', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'getFeaturedPost'),
            'permission_callback' => '__return_true',
        ));

        register_rest_route($namespace, '/posts/recent', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'getRecentPosts'),
            'permission_callback' => '__return_true',
        ));
        register_rest_route($namespace, '/posts/(?P<slug>\S+)', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'getPostBySlug'),
            'permission_callback' => '__return_true',
        ));
    }
}