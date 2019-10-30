<?php

if ( !function_exists( 'whl_get_related_posts' ) ) {
    /**
     * Article similaire
     *
     * @param int $post_id
     * @return WP_Query
     */
    function whl_get_related_posts($post_id)
    {
        // Get current post categories & post_tags
        $category_list = whl_get_terms( $post_id, 'category' );
        $post_tag_list = whl_get_terms( $post_id, 'post_tag' );

        // Build query args
        $args = [
            "post_type" => 'post',
            "posts_per_page" => 3,
            'post_status' => 'publish',
            'post__not_in' => array($post_id), // Don't display current post
            'orderby' => 'rand', // Random query
            'tax_query' => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'category',
                    'fields' => 'slug',
                    'terms' => $category_list
                ),
                array(
                    'taxonomy' => 'post_tag',
                    'fields' => 'slug',
                    'terms' => $post_tag_list
                )
            )
        ];

        return new WP_Query( $args );
    }
}