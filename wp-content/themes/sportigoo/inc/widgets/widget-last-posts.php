<?php

class sportigoo_lastposts_Widget extends WP_Widget {
    /**
     * Initialer le widget, le nommer, etc
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'my_widget',
            'description' => 'Derniers articles',
        );
        parent::__construct( 'sportigoo_lastposts_Widget', 'Derniers articles Sportigoo', $widget_ops );
    }

    /**
     * Fonction qui affichera le résultat HTML sur le site
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        // Vérifier l’existence des données, sinon vide.
        $title = $instance['title'] ?: '';
        $limit = $instance['number'] ?: 5;

        // Wrappeur HTML
        echo $args['before_widget'];

        // Titre du widget
        echo $args['before_title'] . $title . $args['after_title'];

        $last_posts = new WP_Query(array(
            'post_type' => 'post',
            'status' => 'publish',
            'posts_per_page' => $limit,
            'order' => 'DESC',
            'orderby' => 'date'
        ));

        $i = 0;
        if ($last_posts->have_posts()) { ?>

            <!-- Corps HTML de notre Widget -->
            <div class="widget__recents-posts">

            <?php while ($last_posts->have_posts()) {
                $last_posts->the_post(); $i++;

                // Categories
                $categories = get_the_category();
                $the_cat = false;
                if ( !empty( $categories ) ) {
                    $the_cat = $categories[0];
                }

                // Img
                $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );

                // Separator
                if ($i > 1) {?>
                    <hr class="separator"/>
                    <?php } ?>

                <article class="actu-item">
                    <a class="actu-item__wrapper" href="<?php the_permalink(); ?>">
                        <div class="actu-item__img">
                            <?php if ($thumbnail_url) { ?>
                            <img src="<?php echo $thumbnail_url ?>" style="min-width: 70px;max-width: 70px"/>
                            <?php } ?>
                        </div>
                        <div class="actu-item__content">
                            <p>
                                <?php the_title(); ?>
                            </p>
                            <small>
                                <span><?php echo get_the_date(); ?></span>

                                <?php if ($the_cat) { ?>
                                    <span> |</span>
                                    <span> <?php echo $the_cat->name ?></span>
                                <?php } ?>

                            </small>
                        </div>
                    </a>
                </article>

            <?php } wp_reset_postdata(); ?>

            </div>

         <?php }

        echo $args['after_widget'];
    }

    /**
     * Formulaire de réglage dans l'administration
     *
     * @param array $instance The widget options
     */
    public function form($instance) {
        $title = !empty( $instance['title'] ) ? $instance['title'] : '';
        $nombre = !empty( $instance['number'] ) ? $instance['number'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                Titre
            </label>
            <input
                class="widefat"
                id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                type="text"
                value="<?php echo esc_attr( $title ); ?>"
            />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>">
                Nombre de posts à afficher
            </label>
            <input
                    class="widefat"
                    id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"
                    name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>"
                    type="number"
                    value="<?php echo esc_attr( $nombre ); ?>"
            />
        </p>
        <?php
    }

    /**
     * Sauvegarder les options du widget
     *
     * @param array $new_instance Nouvelles options
     * @param array $old_instance Anciennes options
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty( $new_instance['title'] ) ?
            strip_tags( $new_instance['title'] ) : '';
        $instance['number'] = !empty( $new_instance['number'] ) ?
            strip_tags( $new_instance['number'] ) : '';
        return $instance;
    }
}

