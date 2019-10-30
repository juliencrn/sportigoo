<?php

// TODO : Dynamiser Les liens et counters

class sportigoo_social_count_Widget extends WP_Widget {
    /**
     * Initialer le widget, le nommer, etc
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'my_widget',
            'description' => 'Module social counter dans le blog',
        );
        parent::__construct( 'sportigoo_social_count_Widget', 'Social Counter Sportigoo', $widget_ops );
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

        // Wrappeur HTML
        echo $args['before_widget'];

        // Titre du widget
        echo $args['before_title'] . $title . $args['after_title']; ?>

        <!-- Corps HTML de notre Widget -->
        <div class="widget__count">

            <div class="widget__count-item t-blue">
                <a class="icon t-blue" href="<?php the_field('facebook', 'option'); ?>">
                    <svg width="20" height="20">
                        <use xlink:href="#facebook"></use>
                    </svg>
                    <span>
                        <?php echo do_shortcode('[scp code="facebook"]'); ?>
                    </span>
                </a>
            </div>

            <div class="widget__count-item t-blue">
                <a class="icon t-blue" href="<?php the_field('twitter', 'option'); ?>">
                    <svg width="20" height="20" fill="#1D467A">
                        <use xlink:href="#twitter"></use>
                    </svg>
                    <span>
                        <?php echo do_shortcode('[scp code="twitter"]'); ?>
                    </span>
                </a>
            </div>

        </div>

        <?php echo $args['after_widget'];
    }

    /**
     * Formulaire de réglage dans l'administration
     *
     * @param array $instance The widget options
     */
    public function form($instance) {
        $title = !empty( $instance['title'] ) ? $instance['title'] : '';
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
        return $instance;
    }
}

