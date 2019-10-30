<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$current_tab = isset( $_GET['tab'] ) && isset( $tabs_metadata[ $_GET['tab'] ] ) ? sanitize_title( $_GET['tab'] ) : 'availability';

?>
<div class="wrap">
	<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
<?php
foreach ( $tabs_metadata as $tab => $metadata ) {
	?>
		<a class="nav-tab <?php if ( $tab == $current_tab ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( $metadata['href'] ); ?>"><?php echo esc_html( $metadata['name'] ); ?></a>
	<?php
}
?>
	</nav>
	<h1 class="screen-reader-text"><?php echo esc_html( $tabs_metadata[ $current_tab ]['name'] ); ?></h1>
	<h2><?php echo esc_html( $tabs_metadata[ $current_tab ]['name'] ); ?></h2>
	<div id="content">
		<?php $tabs_metadata[ $current_tab ]['generate_html'](); ?>
	</div>
</div>
