<?php
/**
 * REST API for bookings objects.
 *
 * Handles requests to the /bookings endpoint.
 *
 * @package WooCommerce\Bookings\Rest\Controller
 */

/**
 * REST API Products controller class.
 */
class WC_Bookings_REST_Products_Slots_Controller extends WC_REST_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = WC_Bookings_REST_API::V1_NAMESPACE;

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'products/slots';

	/**
	 * Register the route for bookings slots.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
				),
			)
		);
	}

	/**
	 * Abbreviations constants.
	 */
	const AVAILABLE = 'a';
	const BOOKED    = 'b';
	const DATE      = 'd';
	const DURATION  = 'du';
	const ID        = 'i';

	/**
	 * Mapping of abbrieviations to expanded versions of lables.
	 * Used to minimize storred transient size.
	 */
	protected $transient_keys_mapping = array(
		self::AVAILABLE => 'available',
		self::BOOKED    => 'booked',
		self::DATE      => 'date',
		self::DURATION  => 'duration',
		self::ID        => 'product_id',
	);

	/**
	 * @param $availablity with abbreviated lables.
	 * 
	 * @return object with lables expanded to their full version.
	 */
	public function transient_expand( $availability ) {
		$expanded_availability = [];
		foreach ( $availability['records'] as $key => $slot ) {
			$expanded_slot = [];
			foreach ( $slot as $abbrieviation  => $value ) {
				$expanded_slot[ $this->transient_keys_mapping[ $abbrieviation ] ] = $value;
			} 
			$expanded_availability[] = $expanded_slot;
		}
		return array(
			'records' => $expanded_availability,
			'count'   => $availability['count'],
		);
	}

	/**
	 * Format timestamp to the shortest reasonable format usable in API.
	 * 
	 * @param $timestamp
	 * 
	 * @return string
	 */
	public function get_time( $timestamp ) {
		$timezone = wc_booking_get_timezone_string();
		$server_time = new DateTime( date( 'Y-m-d\TH:i:s', $timestamp ), new DateTimeZone( $timezone ) );
		return $server_time->format( "Y-m-d\TH:i" );
	}

	/**
	 * Get available bookings slots.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_items( $request ) {
		$product_ids  = ! empty( $request['product_ids'] ) ? array_map( 'absint', explode( ',', $request['product_ids'] ) ) : array();
		$category_ids = ! empty( $request['category_ids'] ) ? array_map( 'absint', explode( ',', $request['category_ids'] ) ) : array();
		$resource_ids = ! empty( $request['resource_ids'] ) ? array_map( 'absint', explode( ',', $request['resource_ids'] ) ) : array();

		$min_date     = isset( $request['min_date'] ) ? strtotime( urldecode( $request['min_date'] ) ) : 0;
		$max_date     = isset( $request['max_date'] ) ? strtotime( urldecode( $request['max_date'] ) ) : 0;

		$page                         = isset( $request['page'] ) ? absint( $request['page'] ) : false;
		$records_per_page             = isset( $request['limit'] ) ? absint( $request['limit'] ) : 10;
		$transient_name               = 'booking_slots_' . md5( http_build_query( array( $product_ids, $category_ids, $resource_ids, $min_date, $max_date, $page, $records_per_page ) ) );
		$booking_slots_transient_keys = array_filter( (array) get_transient( 'booking_slots_transient_keys' ) );

		$cached_availabilities = get_transient( $transient_name );

		if ( $cached_availabilities ) {
			$availability = wc_bookings_paginated_availability( $cached_availabilities, $page, $records_per_page );
			return $this->transient_expand( $availability );
		}

		// If no product ids are specified, just use all products.
		if ( empty( $product_ids ) ) {
			$product_ids = WC_Data_Store::load( 'product-booking' )->get_bookable_product_ids();
		}

		foreach ( $product_ids as $product_id ) {
			if ( ! isset( $booking_slots_transient_keys[ $product_id ] ) ) {
				$booking_slots_transient_keys[ $product_id ] = array();
			}

			$booking_slots_transient_keys[ $product_id ][] = $transient_name;
		}

		$products = array_filter( array_map( function( $product_id ) {
			return get_wc_product_booking( $product_id );
		}, $product_ids ) );

		// If category ids are specified filter the product ids.
		if ( ! empty( $category_ids ) ) {
			$products = array_filter( $products, function( $product ) use ( $category_ids ) {
				$product_id = $product->get_id();

				return array_reduce( $category_ids, function( $is_in_category, $category_id ) use ( $product_id ) {
					$term = get_term_by( 'id', $category_id, 'product_cat' );

					if ( ! $term ) {
						return $is_in_category;
					}

					return $is_in_category || has_term( $term, 'product_cat', $product_id );
				}, false );
			} );
		}

		// Calculate partially booked/fully booked/unavailable days for each product.
		$booked_data = array_values( array_map( function( $bookable_product ) use ( $min_date, $max_date, $resource_ids ) {
			if ( empty( $min_date ) ) {
				// Determine a min and max date
				$min_date = strtotime( 'today' );
			}

			if ( empty( $max_date ) ) {
				$max_date = strtotime( 'tomorrow' );
			}

			$default_interval = 'hour' === $bookable_product->get_duration_unit() ? $bookable_product->get_duration() * 60 : $bookable_product->get_duration();
			$intervals        = array( $default_interval, $default_interval );

			$product_resources = $bookable_product->get_resource_ids() ?: array();
			$availability      = array();

			$resources = empty( $product_resources ) ? array( 0 ) : $product_resources;
			if ( ! empty( $resource_ids ) ) {
				$resources = array_intersect( $resources, $resource_ids );
			}

			foreach ( $resources as $resource_id ) {
				$blocks           = $bookable_product->get_blocks_in_range( $min_date, $max_date );
				$available_blocks = wc_bookings_get_time_slots( $bookable_product, $blocks, $intervals, $resource_id, $min_date, $max_date, true );
				foreach ( $available_blocks as $timestamp => $data ) {
					unset( $data['resources'] );
					$availability[] = array(
						self::DATE      => $this->get_time( $timestamp ),
						self::DURATION  => $bookable_product->get_duration(),
						self::AVAILABLE => $data['available'],
						self::BOOKED    => $data['booked'],
					);
				}
			}

			$data = array(
				'product_id'   => $bookable_product->get_id(),
				'availability' => $availability,
			);

			return $data;
		}, $products ) );

		$booked_data = apply_filters( 'woocommerce_bookings_rest_slots_get_items', $booked_data );

		$cached_availabilities = array_merge( ...array_map( function( $value ) {
			return array_map( function( $availability ) use ( $value ) {
				$availability[self::ID]   = $value['product_id'];
				return $availability;
			}, $value['availability'] );
		}, $booked_data ) );

		// Sort by date.
		usort( $cached_availabilities, function( $a, $b ) {
			return $a[self::DATE] > $b[self::DATE];
		} );

		// Give array of keys a long ttl because if it expires we won't be able to flush the keys when needed.
		// We can't use 0 to never expire because then WordPress will autoload the option on every page.
		set_transient( 'booking_slots_transient_keys', $booking_slots_transient_keys, YEAR_IN_SECONDS );

		// This transient should be cleared when booking or products are added or updated but keep it short just in case.
		set_transient( $transient_name, $cached_availabilities, HOUR_IN_SECONDS );

		$availability =  wc_bookings_paginated_availability( $cached_availabilities, $page, $records_per_page );
		return $this->transient_expand( $availability );
	}
}
