<?php
/**
 * WooCommerce Bookings API
 *
 * @package WooCommerce\Bookings\Rest
 */

/**
 * API class which registers all the routes.
 */
class WC_Bookings_REST_API {

	const V1_NAMESPACE = 'wc-bookings/v1';

	/**
	 * Construct.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
		add_filter( 'woocommerce_rest_check_permissions', array( $this, 'rest_check_permissions' ), 10, 4 );
	}

	/**
	 * Initialize the REST API.
	 */
	public function rest_api_init() {
		$controller = new WC_Bookings_REST_Products_Controller();
		$controller->register_routes();

		$controller = new WC_Bookings_REST_Products_Categories_Controller();
		$controller->register_routes();

		$controller = new WC_Bookings_REST_Resources_Controller();
		$controller->register_routes();

		$controller = new WC_Bookings_REST_Booking_Controller();
		$controller->register_routes();

		$controller = new WC_Bookings_REST_Products_Slots_Controller();
		$controller->register_routes();
	}

	/**
	 * Filter to override how wc_rest_check_post_permissions works.
	 * Allows non-store owners to see published products and categories.
	 *
	 * @param bool   $permission Current permission.
	 * @param string $context   Request context.
	 * @param int    $object_id Post ID.
	 * @param string $post_type Post Type.
	 * @return bool
	 */
	public function rest_check_permissions( $permission, $context, $object_id, $post_type ) {
		if ( $permission ) {
			return $permission;
		}

		if ( 'revision' === $post_type ) {
			return false;
		}

		$contexts = array(
			'read'   => 'read_post',
			'create' => 'publish_posts',
			'edit'   => 'edit_post',
			'delete' => 'delete_post',
			'batch'  => 'edit_others_posts',
		);

		$cap = $contexts[ $context ];
		if ( 'read_post' === $cap ) {

			if ( ! $object_id ) {
				// Anyone can read all.
				return true;
			}
			$post_status = get_post_status( $object_id );
			if ( $post_status ) {
				if ( 'publish' === $post_status ) {
					// Anyone can read published items.
					return true;
				}
				$post_status_obj = get_post_status_object( $post_status );
				if ( $post_status_obj && $post_status_obj->public ) {
					// Post status is equivalent to published.
					return true;
				}
			}
		}

		$post_type_object = get_post_type_object( $post_type );
		if ( $post_type_object ) {
			$permission = current_user_can( $post_type_object->cap->$cap, $object_id );
		}

		return $permission;
	}
}
