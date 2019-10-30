<?php

/**
 * Get a booking object
 * @param  int $id
 * @return WC_Booking|false
 */
function get_wc_booking( $id ) {
	try {
		return new WC_Booking( $id );
	} catch ( Exception $e ) {
		return false;
	}
}

/**
 * Get a bookable product object
 * @param  int $id
 * @return WC_Product_Booking|false
 */
function get_wc_product_booking( $id ) {
	try {
		return new WC_Product_Booking( $id );
	} catch ( Exception $e ) {
		return false;
	}
}

/**
 * Gets a cost based on the base cost and default resource.
 *
 * @param  WC_Product_Booking $product
 * @return string
 */
function wc_booking_calculated_base_cost( $product ) {
	// If display cost is set, use that always.
	if ( $product->get_display_cost() ) {
		return $product->get_display_cost();
	}

	// Otherwise calculate it.
	$min_duration  = $product->get_min_duration();
	$display_cost  = ( $product->get_block_cost() * $min_duration ) + $product->get_cost();
	$resource_cost = 0;

	if ( $product->has_resources() ) {
		$resources = $product->get_resources();
		$cheapest  = null;

		foreach ( $resources as $resource ) {
			$maybe_cheapest = ( $resource->get_block_cost() * $min_duration ) + $resource->get_base_cost();
			if ( is_null( $cheapest ) || ( $maybe_cheapest < $cheapest ) ) {
				$cheapest = $maybe_cheapest;
			}
		}

		$resource_cost = $cheapest;
	}

	if ( $product->has_persons() && $product->has_person_types() ) {
		$persons       = $product->get_person_types();
		$cheapest      = null;
		$persons_costs = array();

		foreach ( $persons as $person ) {
			$min = $person->get_min();

			if ( empty( $min ) && ! is_numeric( $min ) ) {
				$min = $product->get_min_persons();
			} else {
				$persons_costs[ $person->get_id() ]['min'] = $min;
			}

			$cost = ( ( $person->get_block_cost() * $min_duration ) + $person->get_cost() ) * (float) $min;
			$persons_costs[ $person->get_id() ]['cost'] = $cost;

			if ( ! is_null( $cost ) && ( is_null( $cheapest ) || $cost < $cheapest ) ) {
				$cheapest = $cost;
			}
		}

		if ( ! $product->get_has_person_cost_multiplier() ) {
			$display_cost += $cheapest ? $cheapest : 0;
		}
	}

	if ( $product->has_persons() && $product->has_person_types() && $product->get_has_person_cost_multiplier() ) {
		$persons_total = 0;
		$persons_count = 0;

		foreach ( $persons_costs as $person ) {
			if ( isset( $person['min'] ) ) {
				$persons_total += $person['cost'];
				$persons_count += $person['min'];
			}
		}

		// if count is 0, we use the product setting
		$persons_count = ( 0 !== $persons_count ) ? $persons_count : $product->get_min_persons();
		// if total is 0, we use the cheapest from previous loop
		$persons_total = ( 0 !== $persons_total ) ? $persons_total : $cheapest;

		// don't think about this too hard, your brain will cease to function
		$display_cost = ( ( $display_cost + $persons_total ) * $persons_count ) + ( $resource_cost * $persons_count );
	} elseif ( $product->has_persons() && $product->get_min_persons() > 1 && $product->get_has_person_cost_multiplier() ) {
		$display_cost = ( $display_cost + $resource_cost ) * $product->get_min_persons();
	} else {
		$display_cost = $display_cost + $resource_cost;
	}

	return $display_cost;
}

/**
 * Santiize and format a string into a valid 24 hour time
 * @return string
 */
function wc_booking_sanitize_time( $raw_time ) {
	$time = wc_clean( $raw_time );
	$time = date( 'H:i', strtotime( $time ) );
	return $time;
}

/**
 * Returns true if the product is a booking product, false if not
 * @return bool
 */
function is_wc_booking_product( $product ) {
	$booking_product_types = apply_filters( 'woocommerce_bookings_product_types', array( 'booking' ) );
	return isset( $product ) && $product->is_type( $booking_product_types );
}

/**
 * Convert key to a nice readable label
 * @param  string $key
 * @return string
 */
function get_wc_booking_data_label( $key, $product ) {
	$labels = apply_filters( 'woocommerce_bookings_data_labels', array(
		'type'     => ( $product->get_resource_label() ? $product->get_resource_label() : __( 'Booking Type', 'woocommerce-bookings' ) ),
		'date'     => __( 'Booking Date', 'woocommerce-bookings' ),
		'time'     => __( 'Booking Time', 'woocommerce-bookings' ),
		'timezone' => __( 'Time Zone', 'woocommerce-bookings' ),
		'duration' => __( 'Duration', 'woocommerce-bookings' ),
		'persons'  => __( 'Person(s)', 'woocommerce-bookings' ),
	) );

	if ( ! array_key_exists( $key, $labels ) ) {
		return $key;
	}

	return $labels[ $key ];
}

/**
 * Convert status to human readable label.
 *
 * @since  1.10.0
 * @param  string $status
 * @return string
 */
function wc_bookings_get_status_label( $status ) {
	$statuses = array(
		'unpaid'               => __( 'Unpaid','woocommerce-bookings' ),
		'pending-confirmation' => __( 'Pending Confirmation','woocommerce-bookings' ),
		'confirmed'            => __( 'Confirmed','woocommerce-bookings' ),
		'paid'                 => __( 'Paid','woocommerce-bookings' ),
		'cancelled'            => __( 'Cancelled','woocommerce-bookings' ),
		'complete'             => __( 'Complete','woocommerce-bookings' ),
	);

	/**
	 * Filter the return value of wc_bookings_get_status_label.
	 *
	 * @since 1.11.0
	 */
	$statuses = apply_filters( 'woocommerce_bookings_get_status_label', $statuses );

	return array_key_exists( $status, $statuses ) ? $statuses[ $status ] : $status;
}

/**
 * Returns a list of booking statuses.
 *
 * @since 1.9.13 Add new parameter that allows globalised status strings as part of the array.
 * @param  string $context An optional context (filters) for user or cancel statuses
 * @param boolean $include_translation_strings. Defaults to false. This introduces status translations text string. In future (2.0) should default to true.
 * @return array $statuses
 */
function get_wc_booking_statuses( $context = 'fully_booked', $include_translation_strings = false ) {
	if ( 'user' === $context ) {
		$statuses = apply_filters( 'woocommerce_bookings_for_user_statuses', array(
			'unpaid'               => __( 'Unpaid','woocommerce-bookings' ),
			'pending-confirmation' => __( 'Pending Confirmation','woocommerce-bookings' ),
			'confirmed'            => __( 'Confirmed','woocommerce-bookings' ),
			'paid'                 => __( 'Paid','woocommerce-bookings' ),
			'cancelled'            => __( 'Cancelled','woocommerce-bookings' ),
			'complete'             => __( 'Complete','woocommerce-bookings' ),
		) );
	} elseif ( 'cancel' === $context ) {
		$statuses = apply_filters( 'woocommerce_valid_booking_statuses_for_cancel', array(
			'unpaid'               => __( 'Unpaid','woocommerce-bookings' ),
			'pending-confirmation' => __( 'Pending Confirmation','woocommerce-bookings' ),
			'confirmed'            => __( 'Confirmed','woocommerce-bookings' ),
			'paid'                 => __( 'Paid','woocommerce-bookings' ),
		) );
	} elseif ( 'scheduled' === $context ) {
		$statuses = apply_filters( 'woocommerce_bookings_scheduled_statuses', array(
			'confirmed'            => __( 'Confirmed','woocommerce-bookings' ),
			'paid'                 => __( 'Paid','woocommerce-bookings' ),
		) );
	} else {
		$statuses = apply_filters( 'woocommerce_bookings_fully_booked_statuses', array(
			'unpaid'               => __( 'Unpaid','woocommerce-bookings' ),
			'pending-confirmation' => __( 'Pending Confirmation','woocommerce-bookings' ),
			'confirmed'            => __( 'Confirmed','woocommerce-bookings' ),
			'paid'                 => __( 'Paid','woocommerce-bookings' ),
			'complete'             => __( 'Complete','woocommerce-bookings' ),
			'in-cart'              => __( 'In Cart','woocommerce-bookings' ),
		) );
	}

	/**
	 * Filter the return value of get_wc_booking_statuses.
	 *
	 * @since 1.11.0
	 */
	$statuses = apply_filters( 'woocommerce_bookings_get_wc_booking_statuses', $statuses );

	// backwards compatibility
	return $include_translation_strings ? $statuses : array_keys( $statuses );
}

/**
 * Validate and create a new booking manually.
 *
 * @version  1.10.7
 * @see      WC_Booking::new_booking() for available $new_booking_data args
 * @param    int    $product_id you are booking
 * @param    array  $new_booking_data
 * @param    string $status
 * @param    bool   $exact If false, the function will look for the next available block after your start date if the date is unavailable.
 * @return   mixed  WC_Booking object on success or false on fail
 */
function create_wc_booking( $product_id, $new_booking_data = array(), $status = 'confirmed', $exact = false ) {
	// Merge booking data
	$defaults = array(
		'product_id'  => $product_id, // Booking ID
		'start_date'  => '',
		'end_date'    => '',
		'resource_id' => '',
	);

	$new_booking_data = wp_parse_args( $new_booking_data, $defaults );
	$product          = wc_get_product( $product_id );
	$start_date       = $new_booking_data['start_date'];
	$end_date         = $new_booking_data['end_date'];
	$max_date         = $product->get_max_date();
	$all_day          = isset( $new_booking_data['all_day'] ) && $new_booking_data['all_day'] ? true : false;
	$qty = 1;

	if ( $product->has_person_qty_multiplier() && ! empty( $new_booking_data['persons'] ) ) {
		if ( is_array( $new_booking_data['persons'] ) ) {
			$qty = array_sum( $new_booking_data['persons'] );
		} else {
			$qty = $new_booking_data['persons'];
			$new_booking_data['persons'] = array( $qty );
		}
	}

	// If not set, use next available
	if ( ! $start_date ) {
		$min_date   = $product->get_min_date();
		$start_date = strtotime( "+{$min_date['value']} {$min_date['unit']}", current_time( 'timestamp' ) );
	}

	// If not set, use next available + block duration
	if ( ! $end_date ) {
		$end_date = strtotime( '+' . $product->get_duration() . ' ' . $product->get_duration_unit(), $start_date );
	}

	$searching = true;
	$date_diff = $all_day ? DAY_IN_SECONDS : $end_date - $start_date;

	while ( $searching ) {

		$available_bookings = $product->get_available_bookings( $start_date, $end_date, $new_booking_data['resource_id'], $qty );

		if ( $available_bookings && ! is_wp_error( $available_bookings ) ) {

			if ( ! $new_booking_data['resource_id'] && is_array( $available_bookings ) ) {
				$new_booking_data['resource_id'] = current( array_keys( $available_bookings ) );
			}

			$searching = false;

		} else {
			if ( $exact ) {
				return false;
			}

			$start_date += $date_diff;
			$end_date   += $date_diff;

			if ( $end_date > strtotime( "+{$max_date['value']} {$max_date['unit']}" ) ) {
				return false;
			}
		}
	}

	// Set dates
	$new_booking_data['start_date'] = $start_date;
	$new_booking_data['end_date']   = $end_date;

	// Create it
	$new_booking = get_wc_booking( $new_booking_data );
	$new_booking->create( $status );

	return $new_booking;
}

/**
 * Check if product/booking requires confirmation.
 *
 * @param  int $id Product ID.
 *
 * @return bool
 */
function wc_booking_requires_confirmation( $id ) {
	$product = wc_get_product( $id );

	if (
		is_object( $product )
		&& is_wc_booking_product( $product )
		&& $product->requires_confirmation()
	) {
		return true;
	}

	return false;
}

/**
 * Check if the cart has booking that requires confirmation.
 *
 * @return bool
 */
function wc_booking_cart_requires_confirmation() {
	$requires = false;

	if ( ! empty( WC()->cart->cart_contents ) ) {
		foreach ( WC()->cart->cart_contents as $item ) {
			if ( wc_booking_requires_confirmation( $item['product_id'] ) ) {
				$requires = true;
				break;
			}
		}
	}

	return $requires;
}

/**
 * Check if the order has booking that requires confirmation.
 *
 * @param  WC_Order $order
 *
 * @return bool
 */
function wc_booking_order_requires_confirmation( $order ) {
	$requires = false;

	if ( $order ) {
		foreach ( $order->get_items() as $item ) {
			if ( wc_booking_requires_confirmation( $item['product_id'] ) ) {
				$requires = true;
				break;
			}
		}
	}

	return $requires;
}

/**
 * Check if user has location based timezone selected in settings.
 *
 * @since 1.13.0
 *
 * @return bool
 */
function wc_booking_has_location_timezone_set() {

	$timezone = get_option( 'timezone_string' );

	if ( ! empty( $timezone ) && false !== strpos( $timezone, 'Etc/GMT' ) ) {
		$timezone = '';
	}

	if ( empty( $timezone ) ) {
		return false;
	}

	return true;
}

/**
 * Get timezone string.
 *
 * inspired by https://wordpress.org/plugins/event-organiser/
 *
 * @return string
 */
function wc_booking_get_timezone_string() {
	$timezone = wp_cache_get( 'wc_bookings_timezone_string' );

	if ( false === $timezone ) {
		$timezone   = get_option( 'timezone_string' );
		$gmt_offset = get_option( 'gmt_offset' );

		// Remove old Etc mappings. Fallback to gmt_offset.
		if ( ! empty( $timezone ) && false !== strpos( $timezone, 'Etc/GMT' ) ) {
			$timezone = '';
		}

		if ( empty( $timezone ) && 0 != $gmt_offset ) {
			// Use gmt_offset
			$gmt_offset   *= 3600; // convert hour offset to seconds
			$allowed_zones = timezone_abbreviations_list();

			foreach ( $allowed_zones as $abbr ) {
				foreach ( $abbr as $city ) {
					if ( $city['offset'] == $gmt_offset ) {
						$timezone = $city['timezone_id'];
						break 2;
					}
				}
			}
		}

		// Issue with the timezone selected, set to 'UTC'
		if ( empty( $timezone ) ) {
			$timezone = 'UTC';
		}

		// Cache the timezone string.
		wp_cache_set( 'wc_bookings_timezone_string', $timezone );
	}

	return $timezone;
}

/**
 * Get bookable product resources.
 *
 * @param int $product_id product ID.
 *
 * @return array Resources objects list.
 */
function wc_booking_get_product_resources( $product_id ) {
	global $wpdb;

	$resources = array();
	$posts     = $wpdb->get_results(
		$wpdb->prepare( "
			SELECT posts.ID, posts.post_title
			FROM {$wpdb->prefix}wc_booking_relationships AS relationships
				LEFT JOIN $wpdb->posts AS posts
				ON posts.ID = relationships.resource_id
			WHERE relationships.product_id = %d
			ORDER BY sort_order ASC
		", $product_id )
	);

	foreach ( $posts as $resource ) {
		$resources[] = new WC_Product_Booking_Resource( $resource, $product_id );
	}

	return $resources;
}

/**
 * Get bookable product resource by ID.
 *
 * @param int $product_id product ID.
 * @param int $resource_id resource ID
 *
 * @return array Resources object.
 */
function wc_booking_get_product_resource( $product_id, $resource_id ) {
	global $wpdb;

	$resources = array();
	$posts     = $wpdb->get_results(
		$wpdb->prepare( "
			SELECT posts.ID, posts.post_title
			FROM {$wpdb->prefix}wc_booking_relationships AS relationships
				LEFT JOIN $wpdb->posts AS posts
				ON posts.ID = relationships.resource_id
			WHERE relationships.product_id = %d
			ORDER BY sort_order ASC
		", $product_id )
	);

	$found = false;
	foreach ( $posts as $resource ) {
		if ( $resource->ID == $resource_id ) {
			return new WC_Product_Booking_Resource( $resource, $product_id );
		}
	}

	return $found;
}

/**
 * get_wc_booking_priority_explanation.
 *
 * @since 1.9.10
 * @return string
 */
function get_wc_booking_rules_explanation() {
	return __( 'Rules with lower priority numbers will override rules with a higher priority (e.g. 9 overrides 10 ). Ordering is only applied within the same priority and higher order overrides lower order.', 'woocommerce-bookings' );
}

/**
 * get_wc_booking_priority_explanation.
 *
 * @return string
 */
function get_wc_booking_priority_explanation() {
	return __( 'Rules with lower priority numbers will override rules with a higher priority (e.g. 9 overrides 10 ). Global rules take priority over product rules which take priority over resource rules. By using priority numbers you can execute rules in different orders.', 'woocommerce-bookings' );
}

/**
 * Get the min timestamp that is bookable based on settings.
 *
 * If $today is the current day, offset starts from NOW rather than midnight.
 *
 * @param int $today Current timestamp, defaults to now.
 * @param int $offset
 * @param string $unit
 * @return int
 */
function wc_bookings_get_min_timestamp_for_day( $date, $offset, $unit ) {
	$timestamp = $date;

	$now = current_time( 'timestamp' );
	$is_today     = date( 'y-m-d', $date ) === date( 'y-m-d', $now );

	if ( $is_today || empty( $date ) ) {
		$timestamp = strtotime( "midnight +{$offset} {$unit}", $now );
	}
	return $timestamp;
}

/**
 * Give this function a booking or resource ID, and a range of dates and get back
 * how many places are available for the requested quantity of bookings for all blocks within those dates.
 *
 * Replaces the WC_Product_Booking::get_available_bookings method.
 *
 * @param  WC_Product_Booking | integer $bookable_product Can be a product object or a booking prouct ID.
 * @param  integer $start_date
 * @param  integer $end_date
 * @param  integer|null optional $resource_id
 * @param  integer $qty
 * @param  array   $intervals
 * @return array|int|boolean|WP_Error False if no places/blocks are available or the dates are invalid.
 */
function wc_bookings_get_total_available_bookings_for_range( $bookable_product, $start_date, $end_date, $resource_id = null, $qty = 1, $intervals = array() ) {
	// alter the end date to limit it to go up to one slot if the setting is enabled
	if ( $bookable_product->get_check_start_block_only() ) {
		$end_date = strtotime( '+ ' . $bookable_product->get_duration() . ' ' . $bookable_product->get_duration_unit(), $start_date );
	}
	// Check the date is not in the past
	if ( date( 'Ymd', $start_date ) < date( 'Ymd', current_time( 'timestamp' ) ) ) {
		return false;
	}
	// Check we have a resource if needed
	$booking_resource = $resource_id ? $bookable_product->get_resource( $resource_id ) : null;
	if ( $bookable_product->has_resources() && ! is_numeric( $resource_id ) ) {
		return false;
	}
	$min_date   = $bookable_product->get_min_date();
	$max_date   = $bookable_product->get_max_date();
	$check_from = strtotime( "midnight +{$min_date['value']} {$min_date['unit']}", current_time( 'timestamp' ) );
	$check_to   = strtotime( "+{$max_date['value']} {$max_date['unit']}", current_time( 'timestamp' ) );
	// Min max checks
	if ( 'month' === $bookable_product->get_duration_unit() ) {
		$check_to = strtotime( 'midnight', strtotime( date( 'Y-m-t', $check_to ) ) );
	}
	if ( $end_date < $check_from || $start_date > $check_to ) {
		return false;
	}
	// Get availability of each resource - no resource has been chosen yet
	if ( $bookable_product->has_resources() && ! $resource_id ) {
		return $bookable_product->get_all_resources_availability( $start_date, $end_date, $qty );
	} else {
		// If we are checking for bookings for a specific resource, or have none.
		$check_date     = $start_date;
		if ( in_array( $bookable_product->get_duration_unit(), array( 'minute', 'hour' ) ) ) {
			if ( ! $bookable_product->check_availability_rules_against_time( $start_date, $end_date, $resource_id ) ) {
				return false;
			}
		} else {
			while ( $check_date < $end_date ) {
				if ( ! $bookable_product->check_availability_rules_against_date( $check_date, $resource_id ) ) {
					return false;
				}
				if ( $bookable_product->get_check_start_block_only() ) {
					break; // Only need to check first day
				}
				$check_date = strtotime( '+1 day', $check_date );
			}
		}
		// Get blocks availability
		return $bookable_product->get_blocks_availability( $start_date, $end_date, $qty, $booking_resource, $intervals );
	}
}

/**
 * Find available and booked blocks for specific resources (if any) and return them as array.
 *
 * @param \WC_Product_Booking $bookable_product
 * @param  array  $blocks
 * @param  array  $intervals
 * @param  integer $resource_id
 * @param  integer $from The starting date for the set of blocks
 * @param  integer $to
 * @return array
 *
 * @version  1.10.5
 */
function wc_bookings_get_time_slots( $bookable_product, $blocks, $intervals = array(), $resource_id = 0, $from = 0, $to = 0, $include_sold_out = false ) {
	if ( empty( $intervals ) ) {
		$default_interval = 'hour' === $bookable_product->get_duration_unit() ? $bookable_product->get_duration() * 60 : $bookable_product->get_duration();
		$interval         = $bookable_product->get_min_duration() * $default_interval;
		$intervals        = array( $interval, $default_interval );
	}

	list( $interval, $base_interval ) = $intervals;
	$interval = $bookable_product->get_check_start_block_only() ? $base_interval : $interval;

	if ( ! $include_sold_out ) {
		$blocks   = $bookable_product->get_available_blocks( array(
			'blocks'      => $blocks,
			'intervals'   => $intervals,
			'resource_id' => $resource_id,
			'from'        => $from,
			'to'          => $to,
		) );
	}

	$existing_bookings = WC_Bookings_Controller::get_all_existing_bookings( $bookable_product, $from, $to );

	$booking_resource  = $resource_id ? $bookable_product->get_resource( $resource_id ) : null;
	$available_slots   = array();

	foreach ( $blocks as $block ) {
		$resources = array();

		// Figure out how much qty have, either based on combined resource quantity,
		// single resource, or just product.
		if ( $bookable_product->has_resources() && ( ! is_a( $booking_resource, 'WC_Product_Booking_Resource' ) || ! $booking_resource->has_qty() ) ) {
			$available_qty = 0;

			foreach ( $bookable_product->get_resources() as $resource ) {

				// Only include if it is available for this selection.
				if ( ! WC_Product_Booking_Rule_Manager::check_availability_rules_against_date( $bookable_product, $resource->get_id(), $block ) ) {
					continue;
				}

				if ( in_array( $bookable_product->get_duration_unit(), array( 'minute', 'hour' ) )
					&& ! $bookable_product->check_availability_rules_against_time( $block, strtotime( "+{$interval} minutes", $block ), $resource->get_id() ) ) {
					continue;
				}

				$available_qty += $resource->get_qty();
				$resources[ $resource->get_id() ] = $resource->get_qty();
			}
		} elseif ( $bookable_product->has_resources() && $booking_resource && $booking_resource->has_qty() ) {
			// Only include if it is available for this selection. We set this block to be bookable by default, unless some of the rules apply.
			if ( ! $bookable_product->check_availability_rules_against_time( $block, strtotime( "+{$interval} minutes", $block ), $booking_resource->get_id() ) ) {
				continue;
			}

			$available_qty = $booking_resource->get_qty();
			$resources[ $booking_resource->get_id() ] = $booking_resource->get_qty();
		} else {
			$available_qty = $bookable_product->get_qty();
			$resources[0] = $bookable_product->get_qty();
		}

		$qty_booked_in_block = 0;

		foreach ( $existing_bookings as $existing_booking ) {
			if ( $existing_booking->is_within_block( $block, strtotime( "+{$interval} minutes", $block ) ) ) {
				$qty_to_add = $bookable_product->has_person_qty_multiplier() ? max( 1, array_sum( $existing_booking->get_persons() ) ) : 1;
				if ( $bookable_product->has_resources() ) {
					if ( $existing_booking->get_resource_id() === absint( $resource_id ) ) {
						// Include the quantity to subtract if an existing booking matches the selected resource id
						$qty_booked_in_block += $qty_to_add;
						$resources[ $resource_id ] = ( isset( $resources[ $resource_id ] ) ? $resources[ $resource_id ] : 0 ) - $qty_to_add;
					} elseif ( ( is_null( $booking_resource ) || ! $booking_resource->has_qty() ) && $existing_booking->get_resource() ) {
						// Include the quantity to subtract if the resource is auto selected (null/resource id empty)
						// but the existing booking includes a resource
						$qty_booked_in_block += $qty_to_add;
						$resources[ $existing_booking->get_resource_id() ] = ( isset( $resources[ $existing_booking->get_resource_id() ] ) ? $resources[ $existing_booking->get_resource_id() ] : 0 ) - $qty_to_add;
					}
				} else {
					$qty_booked_in_block += $qty_to_add;
					$resources[0] = ( isset( $resources[0] ) ? $resources[0] : 0 ) - $qty_to_add;
				}
			}
		}

		$available_slots[ $block ] = array(
			'booked'    => $qty_booked_in_block,
			'available' => $available_qty - $qty_booked_in_block,
			'resources' => $resources,
		);
	}

	return $available_slots;
}

/**
 * Builds the HTML to display the start time for hours/minutes.
 *
 * @since 1.13.0
 * @param \WC_Product_Booking $bookable_product
 * @param  array  $blocks
 * @param  array  $intervals
 * @param  integer $resource_id
 * @param  integer $from The starting date for the set of blocks
 * @param  integer $to
 * @return string
 *
 */
function wc_bookings_get_start_time_html( $bookable_product, $blocks, $intervals = array(), $resource_id = 0, $from = 0, $to = 0 ) {
	$available_blocks = wc_bookings_get_time_slots( $bookable_product, $blocks, $intervals, $resource_id, $from, $to );
	$escaped_blocks   = function_exists( 'wc_esc_json' ) ? wc_esc_json( wp_json_encode( $blocks ) ) : _wp_specialchars( wp_json_encode( $blocks ), ENT_QUOTES, 'UTF-8', true );
	$block_html       = '';
	$block_html      .= '<div class="wc-bookings-start-time-container" data-product-id="' . esc_attr( $bookable_product->get_id() ) . '" data-blocks="' . $escaped_blocks . '">';
	$block_html      .= '<label for="wc-bookings-form-start-time">' . esc_html__( 'Starts', 'woocommerce-bookings' ) . '</label>';
	$block_html      .= '<select id="wc-bookings-form-start-time" name="start_time">';
	$block_html      .= '<option value="0">' . esc_html__( 'Start time', 'woocommerce-bookings' ) . '</option>';

	foreach ( $available_blocks as $block => $quantity ) {
		if ( $quantity['available'] > 0 ) {
			$data = wc_bookings_get_end_times( $bookable_product, $blocks, get_time_as_iso8601( $block ), $intervals, $resource_id, $from, $to, true );

			// If this block does not have any end times, skip rendering the time
			if ( empty( $data ) ) {
				continue;
			}

			if ( $quantity['booked'] ) {
				/* translators: 1: quantity available */
				$block_html .= '<option data-block="' . esc_attr( date( 'Hi', $block ) ) . '" value="' . esc_attr( get_time_as_iso8601( $block ) ) . '">' . date_i18n( get_option( 'time_format' ), $block ) . ' (' . sprintf( _n( '%d left', '%d left', $quantity['available'], 'woocommerce-bookings' ), absint( $quantity['available'] ) ) . ')</option>';
			} else {
				$block_html .= '<option data-block="' . esc_attr( date( 'Hi', $block ) ) . '" value="' . esc_attr( get_time_as_iso8601( $block ) ) . '">' . date_i18n( get_option( 'time_format' ), $block ) . '</option>';
			}
		}
	}

	$block_html .= '</select></div>&nbsp;&nbsp;';

	return $block_html;
}

/**
 * Builds the data to display the end time for hours/minutes.
 *
 * @since 1.13.0
 * @param \WC_Product_Booking $bookable_product
 * @param  array  $blocks
 * @param  string $start_date_time Date of the start time.
 * @param  array  $intervals
 * @param  integer $resource_id
 * @param  integer $from The starting date for the set of blocks
 * @param  integer $to
 * @param  bool    $check Whether to just check if there's any data at all.
 * @return array
 *
 */
function wc_bookings_get_end_times( $bookable_product, $blocks, $start_date_time = '', $intervals = array(), $resource_id = 0, $from = 0, $to = 0, $check = false ) {
	$min_duration     = ! empty( $bookable_product->get_min_duration() ) ? $bookable_product->get_min_duration() : 1;
	$max_duration     = ! empty( $bookable_product->get_max_duration() ) ? $bookable_product->get_max_duration() : 12;
	$product_duration = ! empty( $bookable_product->get_duration() ) ? $bookable_product->get_duration() : 1;
	$start_time       = ! empty( $start_date_time ) ? strtotime( substr( $start_date_time, 0, 19 ) ) : '';
	$data             = array();

	if ( empty( $start_time ) ) {
		return $data;
	}

	$first_duration_multiple = intval( $product_duration ) * intval( $min_duration );
	$first_time_slot         = strtotime( '+ ' . $first_duration_multiple . ' ' . $bookable_product->get_duration_unit(), $start_time );

	if ( ! in_array( $start_time, $blocks ) ) {
		return $data;
	}

	$calc_avail              = true;
	$base_interval           = $product_duration * ( 'hour' === $bookable_product->get_duration_unit() ? 60 : 1 );

	if ( $check ) {
		$intervals        = array( $min_duration * $base_interval, $base_interval );
		$available_blocks = wc_bookings_get_total_available_bookings_for_range( $bookable_product, $start_time, $first_time_slot, $resource_id, 1, $intervals );

		return ! is_wp_error( $available_blocks ) && $available_blocks && in_array( $start_time, $blocks );
	}

	for ( $duration_index = $max_duration; $duration_index >= $min_duration; $duration_index-- ) {
		$end_time = strtotime( '+ ' . $duration_index * $product_duration . ' ' . $bookable_product->get_duration_unit(), $start_time );

		// Just need to calculate availability for max duration. If that is available, anything below it will also be.
		if ( $calc_avail ) {
			$intervals        = array( $duration_index * $base_interval, $base_interval );
			$available_blocks = wc_bookings_get_total_available_bookings_for_range( $bookable_product, $start_time, $end_time, $resource_id, 1, $intervals );

			// If there are no available blocks, skip this block
			if ( is_wp_error( $available_blocks ) || ! $available_blocks ) {
				continue;
			}

			$calc_avail = false;
		}

		$duration_units = ( $end_time - $start_time ) / 60;

		$display = ' (' . sprintf( _n( '%d Minute', '%d Minutes', $duration_units, 'woocommerce-bookings' ), $duration_units ) . ')';
		if ( 'hour' === $bookable_product->get_duration_unit() ) {
			$duration_units /= 60;
			$display = ' (' . sprintf( _n( '%d Hour', '%d Hours', $duration_units, 'woocommerce-bookings' ), $duration_units ) . ')';
		}

		$data[] = array(
			'display'  => $display,
			'end_time' => $end_time,
			'duration' => $duration_units / $bookable_product->get_duration(),
		);
	}

	return array_reverse( $data );
}

/**
 * Renders the HTML to display the end time for hours/minutes.
 *
 * @since 1.13.0
 * @param \WC_Product_Booking $bookable_product
 * @param  array  $blocks
 * @param  string $start_date_time Date of the start time.
 * @param  array  $intervals
 * @param  integer $resource_id
 * @param  integer $from The starting date for the set of blocks
 * @param  integer $to
 * @return string
 *
 */
function wc_bookings_get_end_time_html( $bookable_product, $blocks, $start_date_time = '', $intervals = array(), $resource_id = 0, $from = 0, $to = 0 ) {
	$block_html  = '';
	$block_html .= '<div class="wc-bookings-end-time-container">';
	$block_html .= '<label for="wc-bookings-form-end-time">' . esc_html__( 'Ends', 'woocommerce-bookings' ) . '</label>';
	$block_html .= '<select id="wc-bookings-form-end-time" name="end_time">';
	$block_html .= '<option value="0">' . esc_html__( 'End time', 'woocommerce-bookings' ) . '</option>';

	$data = wc_bookings_get_end_times( $bookable_product, $blocks, $start_date_time, $intervals, $resource_id, $from, $to );

	foreach ( $data as $booking_data ) {
		$display  = $booking_data['display'];
		$end_time = $booking_data['end_time'];
		$duration = $booking_data['duration'];

		$block_html .= '<option data-duration-display="' . esc_attr( $display ) . '" data-value="' . get_time_as_iso8601( $end_time ) . '" value="' . esc_attr( $duration ) . '">' . date_i18n( get_option( 'time_format' ), $end_time ) . $display . '</option>';
	}

	$block_html .= '</select></div>';

	return $block_html;
}

/**
 * Find available blocks and return HTML for the user to choose a block. Used in class-wc-bookings-ajax.php.
 *
 * @param \WC_Product_Booking $bookable_product
 * @param  array  $blocks
 * @param  array  $intervals
 * @param  integer $resource_id
 * @param  integer $from The starting date for the set of blocks
 * @param  integer $to
 * @return string
 *
 * @version  1.10.7
 */
function wc_bookings_get_time_slots_html( $bookable_product, $blocks, $intervals = array(), $resource_id = 0, $from = 0, $to = 0 ) {
	$available_blocks = wc_bookings_get_time_slots( $bookable_product, $blocks, $intervals, $resource_id, $from, $to );
	$block_html       = '';

	// If customer defined, we show two dropdowns start/end time.
	if ( 'customer' === $bookable_product->get_duration_type() ) {
		$block_html .= wc_bookings_get_start_time_html( $bookable_product, $blocks, $intervals, $resource_id, $from, $to );
		$block_html .= wc_bookings_get_end_time_html( $bookable_product, $blocks, '', $intervals, $resource_id, $from, $to );
	} else {
		foreach ( $available_blocks as $block => $quantity ) {
			if ( $quantity['available'] > 0 ) {
				if ( $quantity['booked'] ) {
					/* translators: 1: quantity available */
					$block_html .= '<li class="block" data-block="' . esc_attr( date( 'Hi', $block ) ) . '"><a href="#" data-value="' . get_time_as_iso8601( $block ) . '">' . date_i18n( get_option( 'time_format' ), $block ) . ' <small class="booking-spaces-left">(' . sprintf( _n( '%d left', '%d left', $quantity['available'], 'woocommerce-bookings' ), absint( $quantity['available'] ) ) . ')</small></a></li>';
				} else {
					$block_html .= '<li class="block" data-block="' . esc_attr( date( 'Hi', $block ) ) . '"><a href="#" data-value="' . get_time_as_iso8601( $block ) . '">' . date_i18n( get_option( 'time_format' ), $block ) . '</a></li>';
				}
			}
		}
	}

	return apply_filters( 'wc_bookings_get_time_slots_html', $block_html, $available_blocks, $blocks );
}

function get_time_as_iso8601( $timestamp ) {
	$timezone = wc_booking_get_timezone_string();
	$server_time = new DateTime( date( 'Y-m-d\TH:i:s', $timestamp ), new DateTimeZone( $timezone ) );

	return $server_time->format( DateTime::ISO8601 );
}

/**
 * Find available blocks and return HTML for the user to choose a block. Used in class-wc-bookings-ajax.php.
 *
 * @deprecated since 1.10.0
 * @param \WC_Product_Booking $bookable_product
 * @param  array  $blocks
 * @param  array  $intervals
 * @param  integer $resource_id
 * @param  string  $from The starting date for the set of blocks
 * @return string
 */
function wc_bookings_available_blocks_html( $bookable_product, $blocks, $intervals = array(), $resource_id = 0, $from = '' ) {
	_deprecated_function( 'Please use wc_bookings_get_time_slots_html', 'Bookings: 1.10.0' );
	return wc_bookings_get_time_slots_html( $bookable_product, $blocks, $intervals, $resource_id, $from );
}

/**
 * Summary of booking data for admin and checkout.
 *
 * @version 1.10.7
 *
 * @param  WC_Booking $booking
 * @param  bool       $is_admin To determine if this is being called in admin or not.
 */
function wc_bookings_get_summary_list( $booking, $is_admin = false ) {
	$product  = $booking->get_product();
	$resource = $booking->get_resource();
	$label    = $product && is_callable( array( $product, 'get_resource_label' ) ) && $product->get_resource_label() ? $product->get_resource_label() : __( 'Type', 'woocommerce-bookings' );

	$get_local_time = wc_should_convert_timezone( $booking );
	if ( strtotime( 'midnight', $booking->get_start() ) === strtotime( 'midnight', $booking->get_end() ) ) {
		$booking_date = sprintf( '%1$s', $booking->get_start_date( null, null, $get_local_time ) );
	} else {
		$booking_date = sprintf( '%1$s / %2$s', $booking->get_start_date( null, null, $get_local_time ), $booking->get_end_date( null, null, $get_local_time ) );
	}

	$template_args = array(
		'booking'            => $booking,
		'product'            => $product,
		'resource'           => $resource,
		'label'              => $label,
		'booking_date'       => $booking_date,
		'booking_timezone'   => str_replace( '_', ' ', $booking->get_local_timezone() ),
		'is_admin'           => $is_admin,
	);

	wc_get_template( 'order/booking-summary-list.php', $template_args, 'woocommerce-bookings', WC_BOOKINGS_TEMPLATE_PATH );
}

/**
 * Converts a string (e.g. yes or no) to a bool.
 * @param  string $string
 * @return boolean
 */
function wc_bookings_string_to_bool( $string ) {
	if ( function_exists( 'wc_string_to_bool' ) ) {
		return wc_string_to_bool( $string );
	}
	return is_bool( $string ) ? $string : ( 'yes' === $string || 1 === $string || 'true' === $string || '1' === $string );
}

/**
 * @since 1.10.0
 * @param $minute
 * @param $check_date
 *
 * @return int
 */
function wc_booking_minute_to_time_stamp( $minute, $check_date ) {
	return strtotime( "+ $minute minutes", $check_date );
}

/**
 * Convert a timestamp into the minutes after 0:00
 *
 * @since 1.10.0
 * @param integer $timestamp
 * @return integer $minutes_after_midnight
 */
function wc_booking_time_stamp_to_minutes_after_midnight( $timestamp ) {
	$hour    = absint( date( 'H', $timestamp ) );
	$min     = absint( date( 'i', $timestamp ) );
	return  $min + ( $hour * 60 );
}

/**
 * Get timezone offset in seconds.
 *
 * @since  1.10.3
 * @return float
 */
function wc_booking_timezone_offset() {
	$timezone = get_option( 'timezone_string' );
	if ( $timezone ) {
		$timezone_object = new DateTimeZone( $timezone );
		return $timezone_object->getOffset( new DateTime( 'now' ) );
	} else {
		return floatval( get_option( 'gmt_offset', 0 ) ) * HOUR_IN_SECONDS;
	}
}

/**
 * Determine whether Booking time should be converted to local time.
 *
 * @since  1.11.4
 * @return bool
 */
function wc_should_convert_timezone( $booking = null ) {
	if ( 'no' === WC_Bookings_Timezone_Settings::get( 'use_client_timezone' ) ) {
		return false;
	}

	// If we don't have a booking, just use the setting and return true
	if ( is_null( $booking ) ) {
		return true;
	}

	// If a Booking exists, make sure the local timezone is populated (does not happen for day duration e.g.)
	return ! empty( $booking->get_local_timezone() );
}

if ( ! function_exists( 'wc_string_to_timestamp' ) ) {
	/**
	 * Convert mysql datetime to PHP timestamp, forcing UTC. Wrapper for strtotime.
	 *
	 * Based on wcs_strtotime_dark_knight() from WC Subscriptions by Prospress.
	 *
	 * @since  3.0.0
	 *
	 * @param  string $time_string Time string.
	 * @param  int|null $from_timestamp Timestamp to convert from.
	 *
	 * @return int
	 */
	function wc_string_to_timestamp( $time_string, $from_timestamp = null ) {
		$original_timezone = date_default_timezone_get();
		// @codingStandardsIgnoreStart
		date_default_timezone_set( 'UTC' );
		if ( null === $from_timestamp ) {
			$next_timestamp = strtotime( $time_string );
		} else {
			$next_timestamp = strtotime( $time_string, $from_timestamp );
		}
		date_default_timezone_set( $original_timezone );

		// @codingStandardsIgnoreEnd
		return $next_timestamp;
	}
}

if ( ! function_exists( 'wc_timezone_offset' ) ) {
	/**
	 * Get timezone offset in seconds.
	 *
	 * @since  3.0.0
	 * @return float
	 */
	function wc_timezone_offset() {
		$timezone = get_option( 'timezone_string' );
		if ( $timezone ) {
			$timezone_object = new DateTimeZone( $timezone );

			return $timezone_object->getOffset( new DateTime( 'now' ) );
		} else {
			return floatval( get_option( 'gmt_offset', 0 ) ) * HOUR_IN_SECONDS;
		}
	}
}


/**
 * Clear booking slots transient.
 *
 * In contexts where we have a product id, it will only delete the specific ones.
 * However, not all contexts will have a product id, e.g. Global Availability.
 *
 * @param  int|null $bookable_product_id
 * @since  1.13.12
 */
function delete_booking_slots_transient( $bookable_product_id = null ) {
	$booking_slots_transient_keys = array_filter( (array) get_transient( 'booking_slots_transient_keys' ) );

	if ( is_int( $bookable_product_id ) ) {
		if ( ! isset( $booking_slots_transient_keys[ $bookable_product_id ] ) ) {
			return;
		}

		// Get a list of flushed transients
		$flushed_transients = array_map( function( $transient_name ) {
			delete_transient( $transient_name );
			return $transient_name;
		}, $booking_slots_transient_keys[ $bookable_product_id ] );

		// Remove the flushed transients referenced from other product ids (if there's such a cross-reference)
		array_walk( $booking_slots_transient_keys, function( &$transients, $bookable_product_id ) use ( $flushed_transients ) {
			$transients = array_values( array_diff( $transients, $flushed_transients ) );
		} );

		$booking_slots_transient_keys = array_filter( $booking_slots_transient_keys );

		unset( $booking_slots_transient_keys[ $bookable_product_id ] );
		set_transient( 'booking_slots_transient_keys', $booking_slots_transient_keys, YEAR_IN_SECONDS );
	} else {
		$transients = array_unique( array_reduce( $booking_slots_transient_keys, function( $result, $item ) {
			return array_merge( $result, $item );
		}, array() ) );

		foreach ( $transients as $transient_key ) {
			delete_transient( $transient_key );
		}

		delete_transient( 'booking_slots_transient_keys' );
	}
}

/**
 * Renders a json object with a paginated availability set.
 *
 * @since 1.14.0
 */
function wc_bookings_paginated_availability( $availability, $page = false, $records_per_page ) {
	$records = array();
	if( false === $page ) {
		$records = $availability;
	} else {
		$records = array_slice( $availability, ( $page - 1 ) * $records_per_page, $records_per_page );
	}
	$paginated_booking_slots = array(
		'records' => $records,
		'count' => count( $availability ),
	);

	return $paginated_booking_slots;
}
