<?php
/**
 * Class WC_Global_Availability_Data_Store
 *
 * @package Woocommerce/Bookings
 */

/**
 * WC Global Availability Data Store: Stored in Custom table.
 * @todo When 2.6 support is dropped, implement WC_Object_Data_Store_Interface
 */
class WC_Global_Availability_Data_Store extends WC_Data_Store_WP {

	const TABLE_NAME  = 'wc_bookings_availability';
	const CACHE_GROUP = 'wc-bookings-availability';
	const DEFAULT_MIN_DATE = '0000-00-00';
	const DEFAULT_MAX_DATE = '9999-99-99';

	/**
	 * Create a new global availability in the database.
	 *
	 * @param WC_Global_Availability $availability WC_Global_Availability instance.
	 */
	public function create( &$availability ) {
		global $wpdb;

		$availability->apply_changes();

		$data = array(
			'gcal_event_id' => $availability->get_gcal_event_id( 'edit' ),
			'title'         => $availability->get_title( 'edit' ),
			'range_type'    => $availability->get_range_type( 'edit' ),
			'from_date'     => $availability->get_from_date( 'edit' ),
			'to_date'       => $availability->get_to_date( 'edit' ),
			'from_range'    => $availability->get_from_range( 'edit' ),
			'to_range'      => $availability->get_to_range( 'edit' ),
			'bookable'      => $availability->get_bookable( 'edit' ),
			'priority'      => $availability->get_priority( 'edit' ),
			'ordering'      => $availability->get_ordering( 'edit' ),
			'rrule'         => $availability->get_rrule( 'edit' ),
			'date_created'  => current_time( 'mysql' ),
			'date_modified' => current_time( 'mysql' ),
		);

		$wpdb->insert( $wpdb->prefix . self::TABLE_NAME, $data );
		WC_Cache_Helper::incr_cache_prefix( self::CACHE_GROUP );
		delete_booking_slots_transient();
	}

	/**
	 * Read availability from the database.
	 *
	 * @param  WC_Global_Availability $availability Instance.
	 * @throws Exception When webhook is invalid.
	 */
	public function read( &$availability ) {
		global $wpdb;

		$data = wp_cache_get( $availability->get_id(), self::CACHE_GROUP );

		if ( false === $data ) {
			$data = $wpdb->get_row(
				$wpdb->prepare(
					'SELECT 
								ID as id, 
								gcal_event_id,
								title,
								range_type,
								from_date,
								to_date,
								from_range,
								to_range,
								bookable,
								priority,
								ordering,
								date_created,
								date_modified,
       							rrule
							FROM ' . $wpdb->prefix . self::TABLE_NAME .
							' WHERE ID = %d LIMIT 1;',
					$availability->get_id()
				),
				ARRAY_A
			); // WPCS: unprepared SQL ok.

			wp_cache_add( $availability->get_id(), $data, self::CACHE_GROUP );
		}

		if ( is_array( $data ) ) {
			$availability->set_props( $data );
			$availability->set_object_read( true );
		}
	}

	/**
	 * Update a webhook.
	 *
	 * @param WC_Global_Availability $availability Instance.
	 */
	public function update( &$availability ) {
		global $wpdb;

		$changes = $availability->get_changes();

		$changes['date_modified'] = current_time( 'mysql' );

		$wpdb->update(
			$wpdb->prefix . self::TABLE_NAME,
			$changes,
			array(
				'ID' => $availability->get_id(),
			)
		);

		$availability->apply_changes();

		wp_cache_delete( $availability->get_id(), self::CACHE_GROUP );
		WC_Cache_Helper::incr_cache_prefix( self::CACHE_GROUP );
		delete_booking_slots_transient();
	}

	/**
	 * Remove a webhook from the database.
	 *
	 * @param WC_Global_Availability $availability Instance.
	 * @param array                  $options      Options array.
	 */
	public function delete( &$availability, $options = array() ) {
		global $wpdb;

		do_action( 'woocommerce_bookings_before_delete_global_availability', $availability, $this ); // WC_Data::delete does not trigger an action like save() so we have to do it here.

		$wpdb->delete(
			$wpdb->prefix . self::TABLE_NAME,
			array(
				'ID' => $availability->get_id(),
			),
			array( '%d' )
		);
		wp_cache_delete( $availability->get_id(), self::CACHE_GROUP );
		WC_Cache_Helper::incr_cache_prefix( self::CACHE_GROUP );
		delete_booking_slots_transient();
	}

	/**
	 * Get all global availabilties defined in the database as objetcs.
	 *
	 * @param array  $filters { @see self::build_query() }.
	 * @param string $min_date { @see self::build_query() }.
	 * @param string $max_date { @see self::build_query() }.
	 *
	 * @return WC_Global_Availability[]
	 * @throws Exception Validation fails.
	 */
	public function get_all( $filters = array(), $min_date = self::DEFAULT_MIN_DATE, $max_date = self::DEFAULT_MAX_DATE) {
		$data = $this->get_all_as_array( $filters, $min_date, $max_date );

		$availabilities = array();
		foreach ( $data as $row ) {
			$availability = new WC_Global_Availability();
			$availability->set_object_read( false );
			$availability->set_props( $row );
			$availability->set_object_read( true );
			$availabilities[] = $availability;
		}

		return $availabilities;
	}

	/**
	 * Get global availability as array.
	 *
	 * @param array  $filters { @see self::build_query() }.
	 * @param string $min_date { @see self::build_query() }.
	 * @param string $max_date { @see self::build_query() }.
	 *
	 * @return array|null|object
	 */
	public function get_all_as_array( $filters = array(), $min_date = self::DEFAULT_MIN_DATE, $max_date = self::DEFAULT_MAX_DATE ) {
		global $wpdb;

		if ( ! is_array( $filters ) ) {
			$filters = array(); // WC_Data_Store uses call_user_func_array to call this function so the default parameter is not used.
		}

		$sql = $this->build_query( $filters, $min_date, $max_date );

		$cache_key = WC_Cache_Helper::get_cache_prefix( self::CACHE_GROUP ) . 'get_all:' . md5( $sql );
		$array     = wp_cache_get( $cache_key, self::CACHE_GROUP );

		if ( false === $array ) {
			$array = $wpdb->get_results( $sql, ARRAY_A ); // WPCS: unprepared SQL ok.

			foreach ( $array as &$row ) {
				// Set BC keys.
				$row['type'] = $row['range_type'];
				$row['to']   = $row['to_range'];
				$row['from'] = $row['from_range'];
			}

			wp_cache_add( $cache_key, $array, self::CACHE_GROUP );
		}

		return $array;
	}

	/**
	 * Builds query string for availability.
	 *
	 * @param array $filters { @see self::build_query() }.
	 * @param string $min_date Minimum date to select intersecting availability entries for (yyyy-mm-dd format).
	 * @param string $max_date Maximum date to select intersecting availability entries for (yyyy-mm-dd format).
	 *
	 * @return string
	 */
	private function build_query( $filters, $min_date, $max_date ) {
		global $wpdb;

		/*
		 * Build list of fields with virtual fields 'start_date' and 'end_date'.
		 * 'start_date' shall be '0000-00-00' for recurring events.
		 * 'end_date' shall be '9999-99-99' for recurring events.
		 */
		$fields = array(
			'ID',
			'gcal_event_id',
			'title',
			'range_type',
			'from_date',
			'to_date',
			'from_range',
			'to_range',
			'rrule',
			'bookable',
			'priority',
			'ordering',
			'date_created',
			'date_modified',
			'(CASE 
				WHEN range_type = \'custom\' THEN from_range 
				WHEN range_type = \'time:range\' THEN from_date
				WHEN range_type = \'custom:daterange\' THEN from_date
				ELSE \'0000-00-00\' 
			END) AS start_date',
			'(CASE 
				WHEN range_type = \'custom\' THEN to_range 
				WHEN range_type = \'time:range\' THEN to_date
				WHEN range_type = \'custom:daterange\' THEN to_date
				ELSE \'9999-99-99\' 
			END) AS end_date',
		);

		// Identity for WHERE clause.
		$where = array( '1' );

		// Parse WHERE for SQL.
		foreach ( $filters as $filter ) {
			$key     = esc_sql( $filter['key'] );
			$value   = esc_sql( $filter['value'] );
			$compare = $this->validate_compare( $filter['compare'] );
			$where[] = "`{$key}` {$compare} '{$value}'";
		}

		// Query for dates that intersect with the min and max.
		if ( self::DEFAULT_MIN_DATE !== $min_date || self::DEFAULT_MAX_DATE !== $max_date ) {
			$min_max_dates       = array( esc_sql( $min_date ), esc_sql( $max_date ) );
			$date_intersect_or   = array();
			$date_intersect_or[] = vsprintf( "( start_date BETWEEN '%s' AND '%s' )", $min_max_dates );
			$date_intersect_or[] = vsprintf( "( end_date BETWEEN '%s' AND '%s' )", $min_max_dates );
			$date_intersect_or[] = vsprintf( "( start_date <= '%s' AND end_date >= '%s' )", $min_max_dates );
			$where[]             = sprintf( "( %s )", implode( ' OR ', $date_intersect_or ) );
		}
		sort( $where );

		return sprintf(
			'SELECT * FROM ( SELECT %s FROM %s ) AS a_data WHERE %s ORDER BY ordering ASC',
			implode( ', ', $fields ),
			$wpdb->prefix . self::TABLE_NAME,
			implode( ' AND ', $where )
		);
	}

	/**
	 * Validates query filter comparison (defaults to '=')
	 *
	 * @param string $compare Raw compare string.
	 * @return string Validated compare string.
	 */
	private function validate_compare( $compare ) {
		$compare = strtoupper( $compare );
		if ( ! in_array( $compare, array(
			'=', '!=', '>', '>=', '<', '<=',
			'LIKE', 'NOT LIKE',
			'IN', 'NOT IN',
			'BETWEEN', 'NOT BETWEEN'
		) ) ) {
			$compare = '=';
		}
		return $compare;
	}

}
