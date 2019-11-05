<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package sportigoo
 */

if ( !function_exists( 'sportigoo_posted_on' ) ) {
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function sportigoo_posted_on()
    {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf( $time_string,
            esc_attr( get_the_date( DATE_W3C ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( DATE_W3C ) ),
            esc_html( get_the_modified_date() )
        );

        $posted_on = sprintf(
        /* translators: %s: post date. */
            esc_html_x( 'Posted on %s', 'post date', 'sportigoo' ),
            '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

    }
}


if ( !function_exists( 'sportigoo_posted_by' ) ) {
    /**
     * Prints HTML with meta information for the current author.
     */
    function sportigoo_posted_by()
    {
        $byline = sprintf(
        /* translators: %s: post author. */
            esc_html_x( 'by %s', 'post author', 'sportigoo' ),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

    }
}


if ( !function_exists( 'sportigoo_entry_footer' ) ) {
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function sportigoo_entry_footer()
    {
        // Hide category and tag text for pages.
        if ( 'post' === get_post_type() ) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list( esc_html__( ', ', 'sportigoo' ) );
            if ( $categories_list ) {
                /* translators: 1: list of categories. */
                printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'sportigoo' ) . '</span>', $categories_list ); // WPCS: XSS OK.
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'sportigoo' ) );
            if ( $tags_list ) {
                /* translators: 1: list of tags. */
                printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'sportigoo' ) . '</span>', $tags_list ); // WPCS: XSS OK.
            }
        }

        if ( !is_single() && !post_password_required() && (comments_open() || get_comments_number()) ) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                    /* translators: %s: post title */
                        __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'sportigoo' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                    __( 'Edit <span class="screen-reader-text">%s</span>', 'sportigoo' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
}


if ( !function_exists( 'sportigoo_post_thumbnail' ) ) {
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function sportigoo_post_thumbnail()
    {
        if ( post_password_required() || is_attachment() || !has_post_thumbnail() ) {
            return;
        }

        if ( is_singular() ) :
            ?>

            <div class="post-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail( 'post-thumbnail', array(
                    'alt' => the_title_attribute( array(
                        'echo' => false,
                    ) ),
                ) );
                ?>
            </a>

        <?php
        endif; // End is_singular().
    }
}


if ( !function_exists( 'zz_print_social_list' ) ) {
    /**
     * Print social list from social name (like name frm acf)
     *
     * look for ACF Group theme-option-social
     *
     * @param array $list
     * @param int $size icon size in px without 'px'
     * @void print HTML
     */
    function zz_print_social_list($list, $size = 18)
    {
        if ( is_array( $list ) ) {
            foreach ($list as $name) { ?>
                <a href="<?php the_field( $name, 'option' ); ?>" target="_blank">
                    <svg width="<?php echo $size ?>" height="<?php echo $size ?>">
                        <use xlink:href="#<?php echo $name ?>"></use>
                    </svg>
                </a>
            <?php }
        }
    }
}


if ( !function_exists( 'zz_custom_logo' ) ) {
    /**
     * Print the custom logo in <a> html
     *
     * @void print HTML
     */
    function zz_custom_logo()
    { ?>
        <a href="<?php echo home_url(); ?>">
            <?php $logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'medium' );
            if ( has_custom_logo() ) {
                echo '<img src="' . esc_url( $logo[0] ) . '" alt="Sportigoo">';
            } ?>
        </a>
        <?php
    }
}


if ( !function_exists( 'zz_get_the_term_list' ) ) {
    /**
     * @param $id
     * @return bool|false|string|WP_Error|WP_Term[]
     *
     * @link https://developer.wordpress.org/reference/functions/get_the_term_list/
     */
    function zz_get_the_term_list($id)
    {
      $before = '';
      $sep = '';
      $after = '';

        $terms = get_the_terms( $id, 'product_cat' );

        if ( is_wp_error( $terms ) ) {
            return $terms;
        }

        if ( empty( $terms ) ) {
            return false;
        }

        $links = array();

        foreach ($terms as $term) {
          $base_url = get_permalink(get_field('page_de_recherche', 'option'));
          $link = add_query_arg('categorie', $term->slug, $base_url);

          $links[] = '<a class="categories__item" href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
        }

        /**
         * Filters the term links for a given taxonomy.
         *
         * The dynamic portion of the filter name, `$taxonomy`, refers
         * to the taxonomy slug.
         *
         * @since 2.5.0
         *
         * @param string[] $links An array of term links.
         */
        $term_links = apply_filters( "term_links-{product_cat}", $links );

        return $before . join( $sep, $term_links ) . $after;
    }
}


if ( !function_exists( 'zz_display_product_row' ) ) {
    /**
     * Affiche un row de product filtrés
     *
     * @param $post_ids
     * @param bool $preview
     */
    function zz_display_product_row($post_ids, $preview = true)
    {
        if ( !empty($post_ids) ) { ?>
            <div class="activities__row">
                <div class="activities__slider activitiesSlider <?php echo $preview ? 'hasPrev':'' ?>">
                    <?php foreach ($post_ids as $post_id) {
                      set_query_var('post_id', $post_id);
                      get_template_part( 'woocommerce/content', 'product' );
                    }
                    ?>
                </div>
            </div>

            <?php // Preview template
            if ($preview) {
                get_template_part( 'pages/homepage/activities', 'preview' );
            }
        }
    }
}


if ( !function_exists( 'whl_get_terms' ) ) {
    /**
     * Récupérer une liste de terms->slugs
     *
     * @param int $post_id
     * @param string $term_name :  category | post_tag
     * @return array list of term slug
     */
    function whl_get_terms($post_id, $term_name = 'category')
    {
        $terms = get_the_terms( $post_id, $term_name );
        if ( empty( $terms ) ) $terms = array();
        $terms_list = wp_list_pluck( $terms, 'slug' );
        return $terms_list;
    }
}


if ( !function_exists( 'whl_get_the_excerpt' ) ) {
    /**
     * Build custom excerpt
     *
     * @param int $post_id
     * @param int $length
     * @param string $more
     * @return string excerpt
     */
    function whl_get_the_excerpt($post_id, $length = 32, $more = ' [...]')
    {
        $content_post = get_post( $post_id );
        $content = $content_post->post_content;

        $content = strip_shortcodes( $content );
        $content = apply_filters( 'the_content', $content );
        $content = str_replace( ']]>', ']]>', $content );

        $excerpt_length = apply_filters( 'excerpt_length', $length );
        $excerpt_more = apply_filters( 'excerpt_more', $more );
        $excerpt = wp_trim_words( $content, $excerpt_length, $excerpt_more );

        return $excerpt;
    }
}


if ( !function_exists( 'sportigoo_section_product_tax' ) ) {
  /**
   * Récupérer une liste de terms->slugs
   *
   * @param $arr array of terms
   * @return mixed html | null
   */
  function sportigoo_section_product_tax($arr)
  {
    if ( empty($arr) ) {
      return null;
    }
    ?>

      <?php foreach ($arr as $term) {
        $bg = get_field('image', $term);
        $bg = !empty($bg) ? $bg['sizes']['med-400'] : '';
        $title = $term->name;

        // Build pre-filtered URL
        $search_page_id = get_field('page_de_recherche', 'option');
        $base_url = get_permalink($search_page_id);
        $key = 'categorie';
        $value = $term->slug;
        $url = add_query_arg($key, $value, $base_url);
        ?>

        <div class="homepage__categories__item">
          <a
            href="<?php echo $url; ?>"
            class="homepage__categories__item--bg"
            style="background-image: url('<?php echo $bg; ?>');"
          >
            <div class="homepage__categories__item--face">
              <h3 class="homepage__categories__item--title">
                <?php echo $title; ?>
              </h3>
            </div>
            <div class="homepage__categories__item--hover">
              <h3 class="homepage__categories__item--title">
                <?php echo $title; ?>
              </h3>
              <p class="homepage__categories__item--content">
                <?php echo $term->description; ?>
              </p>
            </div>
          </a>
        </div>
      <?php } ?>

    <?php

  }
}
