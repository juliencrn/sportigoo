<?php

class sportigoo_twitter_Widget extends WP_Widget {
    /**
     * Initialer le widget, le nommer, etc
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'twitter_widget',
            'description' => 'Twitter Feed dans le blog',
        );
        parent::__construct( 'sportigoo_twitter_Widget', 'Twitter Feed Sportigoo', $widget_ops );
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
        $pseudo_twitter = $instance['pseudo_twitter'] ?: '';

        // Wrappeur HTML
        echo $args['before_widget'];

        // Titre du widget
        echo $args['before_title'] . $title . $args['after_title'];
        ?>

        <!-- Corps HTML de notre Widget -->
        <div class="twitter-wrapper">
            <a
                    class="twitter-timeline"
                    href="https://twitter.com/<?php echo $pseudo_twitter ?>"
                    data-width="100%"
                    data-height="600"
                    data-lang="fr"
                    data-chrome="noheader nofooter noborders noscrollbar"
            ></a>
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
        $pseudo_twitter = !empty( $instance['pseudo_twitter'] ) ? $instance['pseudo_twitter'] : '';
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
            <label for="<?php echo esc_attr( $this->get_field_id( 'pseudo_twitter' ) ); ?>">
                Pseudo Twitter
            </label>
            <input
                    class="widefat"
                    id="<?php echo esc_attr( $this->get_field_id( 'pseudo_twitter' ) ); ?>"
                    name="<?php echo esc_attr( $this->get_field_name( 'pseudo_twitter' ) ); ?>"
                    type="text"
                    value="<?php echo esc_attr( $pseudo_twitter ); ?>"
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
        $instance['pseudo_twitter'] = !empty( $new_instance['pseudo_twitter'] ) ?
            strip_tags( $new_instance['pseudo_twitter'] ) : '';
        return $instance;
    }
}

