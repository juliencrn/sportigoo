<?php
/**
 * Template part for displaying post content in archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package sportigoo
 */

?>
<?php
$cats = get_the_category();
$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blogcard'); ?>>
    <a class="blogcard__wrapper" href="<?php the_permalink(); ?>">
        <div class="blogcard__img" style="background-image:url(<?= $thumbnail_url ?>)">
            <?php if ( !empty( $cats ) ) : ?>
                <div class="blogcard__cat">
                    <p><?php echo $cats[0]->name; ?></p>
                </div>
            <?php endif; ?>

        </div>
        <div class="blogcard__content">
            <div class="blogcard__date">
                <p><?php the_date(); ?></p>
            </div>
            <div class="blogcard__title">
                <h3><?php the_title(); ?></h3>
            </div>
            <div class="blogcard__excerpt">
                <p><?php the_excerpt(); ?></p>
            </div>
        </div>
    </a>
</article><!-- #post-<?php the_ID(); ?> -->
