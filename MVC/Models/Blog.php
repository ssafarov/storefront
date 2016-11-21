<?php
namespace MVC\Models;

use \MVC\Singleton;
use WP_Query;

class Blog
{
    const CATEGORY_NAME = 'Blog';
    const CATEGORIES_SLUG = 'blog_categories';
    use Singleton;

	private $posts;

	/**
     *  Return number of blog posts with offset
     *
     * @param int $post_per_page
     * @param int $offset
     * @return WP_Query
     */
    public function getPosts($postsPerPage = 5, $offset = 0, $orderBy = 'date', $order='DESC' ) {
        global $wp_query;
		$args = array(
            'post_type'        => 'post',
            'post_status'      => 'published',
			'posts_per_page'   => $postsPerPage,
			'offset'           => $offset,
			'orderby'          => $orderBy,
			'order'            => $order,
            'category_name'    => self::CATEGORY_NAME,
            'meta_query' => array(
                array(
                    'key' => 'featured_post',
                    'compare' => 'NOT EXISTS'
                )
            )
        );

        $wp_query = new WP_Query($args);
		
		return $wp_query;
		
	}

    /**
     * @return array|bool|\WP_Post
     */
    public function getFeaturedPost()
    {
        $postId = $this->get_post_id_by_meta_key_and_value('featured_post', 1);

        if (!$postId) {
            $args = array(
                'category_name'   => self::CATEGORY_NAME,
                'showposts'       => 1
                );
            $wp_query = new WP_Query($args);
            if (!empty($wp_query->posts[0]->ID)) {
                $postId = $wp_query->posts[0]->ID;
            }
        }
        $post = get_post($postId);

        $post->image = $this->getFormattedPostImage($postId);
        $post->categories = $this->getPostCategories($postId);


        if (!empty($post)) {
            return $post;
        } else {
            return false;
        }

    }


    /**
     * Get post id from meta key and value
     * @param string $key
     * @param mixed $value
     * @return int|bool
     */
    public  function get_post_id_by_meta_key_and_value($key, $value)
    {
        $returnID = false;
        $args = array(
            'post_type'  => 'post', //or a post type of your choosing
            'post_status' => 'published',
            'category_name'    => self::CATEGORY_NAME,
            'posts_per_page' => 1,
            'meta_query' => array(
                array(
                    'key' => $key,
                    'value' => $value
                )
            )
        );

        $wp_query = get_posts($args);

        if ( !empty($wp_query) ){
            $returnID = $wp_query[0]->ID;
        }

        return $returnID;

    }

    /**
     * @param $post_id
     * @return mixed
     */
    public function getFormattedPostImage($post_id)
    {
        if (has_post_thumbnail($post_id)) {
            return wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'single-post-thumbnail')[0];
        }
    }

    /**
     * @param $post_id
     * @return array|string|bool
     */
    public function getPostCategories($post_id = 0, $return_array = false )
    {
        $postCategories = wp_get_post_categories($post_id);
        if (!empty($postCategories)) {
            $cats = [];

            foreach ($postCategories as $c) {
                $cat = get_category($c);

                $cats[] = $cat->name;
            }
            
            if ($return_array) {
                return $cats;
            } else {
                $categories = implode(',&nbsp;', $cats);
                return $categories;
            }

        }
        
        return false;
    }

    /**
     * @param $post_id
     * @return array|string|bool
     */
    public function getFormattedPostCategories( $post_id = 0 )
    {
        $categories = get_the_category_list( __( ', ', 'strorefront' ), '', $post_id );
        return $categories;
    }
    /**
     * @param $id
     * @return string
     */
    public function getUserName($id)
    {
        $user = get_userdata($id);
        if (!empty($user->display_name)) {
            return $user->display_name;
        } else {
            return $user->first_name . ' ' . $user->last_name;
        }
    }

    /**
     * @return WP_Query
     */
    public function getBlogCategories($postsPerPage = 5, $offset = 0, $orderBy = 'date', $order='DESC')
    {
        $args = array(
            'posts_per_page'   => $postsPerPage,
            'offset'           => $offset,
            'orderby'          => $orderBy,
            'order'            => $order,
            'post_type'        => 'post',
            'post_status'      => 'published',
            'category_name' => self::CATEGORIES_SLUG
        );

        $query = new WP_Query($args);
        return $query;
    }
}