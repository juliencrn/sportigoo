<?php

/**
 * WC_Bookings_Calendar.
 */
class WC_Bookings_Calendar {

	/**
	 * Stores Bookings/Availability.
	 *
	 * @var array Mixed type of WC_Global_Availability and WC_Booking
	 */
	private $events;

	/**
	 * Maximum number of bookings to display in each day of the monthly calendar view.
	 */
	const MAX_BOOKINGS_PER_DAY = 3;

	/**
	 * Output the calendar view.
	 */
	public function output() {
		global $wp_version;

		wp_enqueue_script( 'wc-enhanced-select' );
		wp_enqueue_script( 'wc_bookings_admin_js' );

		$product_filter  = isset( $_REQUEST['filter_bookings_product'] ) ? absint( $_REQUEST['filter_bookings_product'] ) : '';
		$resource_filter = isset( $_REQUEST['filter_bookings_resource'] ) ? absint( $_REQUEST['filter_bookings_resource'] ) : '';
		$view            = isset( $_REQUEST['view'] ) && 'month' === $_REQUEST['view'] ? 'month' : 'day';
		$booking_filter = array();
		if ( $product_filter ) {
			array_push( $booking_filter, $product_filter );
		}
		if ( $resource_filter ) {
			array_push( $booking_filter, $resource_filter );
		}

		$month = isset( $_REQUEST['calendar_month'] ) ? absint( $_REQUEST['calendar_month'] ) : current_time( 'n' );
		$year  = isset( $_REQUEST['calendar_year'] ) ? absint( $_REQUEST['calendar_year'] ) : current_time( 'Y' );
		$day   = isset( $_REQUEST['calendar_day'] ) ? wc_clean( $_REQUEST['calendar_day'] ) : current_time( 'Y-m-d' );

		if ( 'day' === $view ) {
			$day          = isset( $_REQUEST['calendar_day'] ) ? wc_clean( $_REQUEST['calendar_day'] ) : date( 'Y-m-d' );
			$day_start    = strtotime( 'midnight', strtotime( $day ) );
			$day_end      = strtotime( 'midnight +1 day', strtotime( $day ) ) - 1;
			$this->events = WC_Bookings_Controller::get_events_in_date_range(
				$day_start,
				$day_end,
				$booking_filter,
				false
			);
			$this->global_availability_minutes = $this->get_available_minutes_for_calendar_day( $day );
			$this->day                         = strtotime( $day );
		} else {
			if ( $year < ( date( 'Y' ) - 10 ) || $year > 2100 ) {
				$year = date( 'Y' );
			}

			if ( $month > 12 ) {
				$month = 1;
				$year ++;
			}

			if ( $month < 1 ) {
				$month = 12;
				$year --;
			}

			/*
			 * WordPress start_of_week is in date format 'w'.
			 * We are changing it to 'N' because we want ISO-8601.
			 * Monday is our reference first day of the week.
			 */
			$start_of_week           = absint( get_option( 'start_of_week', 1 ) ); 
			$start_of_week           = $start_of_week === 0 ? 7 : $start_of_week;

			// On which day of the week the month starts
			$month_start_day_of_week = absint( date( 'N', strtotime( "$year-$month-01" ) ) );

			/* 
			 * Calculate column where the month start will be placed.
			 * This calculates true modulo ( never negative ).
			 */
			$start_column            = ( 7 + ( $month_start_day_of_week - $start_of_week ) % 7 ) % 7;

			/*
			 * Calcu start date: how many days from the previous month we need to include,
			 * in order to have calendar without empty days in the first row.
			 */
			$start_time              = strtotime( "-{$start_column} day", strtotime( "$year-$month-01" ) );

			// How many days the month has.
			$month_number_of_days    = date( 't', strtotime( "$year-$month-01" ) );

			// On which day of the week the month ends.
			$month_end_day_of_week   = absint( date( 'N', strtotime( "$year-$month-$month_number_of_days" ) ) );

			/*
			 * Calculate column where the last day of month will be placed.
			 * This calculates true modulo ( never negative ).
			 */
			$end_column             = ( 7 + ( $month_end_day_of_week - $start_of_week ) % 7 ) % 7;

			/*
			 * Calculate end date: how many days from the next month we need to include.
			 * We want to have calendar without empty days in the last row.
			 */
			$end_padding            = 6 - $end_column;
			$end_time               = strtotime( "+{$end_padding} day midnight", strtotime( "$year-$month-$month_number_of_days" ) );

			$this->events           = WC_Bookings_Controller::get_events_in_date_range(
				$start_time,
				$end_time,
				$booking_filter,
				false
			);
		}

		$calendar_params = array(
			'default_month' => esc_html( date_i18n( 'F', mktime( 0, 0, 0, $month, 10 ) ) ),
			'default_year'  => esc_html( $year ),
			'default_day'   => esc_html( isset( $_REQUEST['calendar_day'] ) ? date( 'F d, Y', strtotime( wc_clean( $_REQUEST['calendar_day'] ) ) ) : current_time( 'F d, Y' ) ),
		);
		// First day of currently selected year/month for datepicker default.
		$default_date = "$year-$month-01";

		wp_localize_script( 'wc_bookings_admin_calendar_gutenberg_js', 'wc_bookings_admin_calendar_js_params', $calendar_params );

		include( 'views/html-calendar-' . $view . '.php' );
	}

	/**
	 * List bookings for a day.
	 */
	public function list_bookings( $day, $month, $year ) {
		$date_start    = strtotime( "$year-$month-$day midnight" ); // Midnight today.
		$date_end      = strtotime( "$year-$month-$day tomorrow" ); // Midnight next day.
		$booking_count = 0;

		foreach ( $this->events as $event ) {
			$event_classes = array();
			$title         = '';
			$id            = '';
			$style         = '';

			if ( 'WC_Booking' === get_class( $event ) ) {
				$booking = $event;
				$start   = $booking->get_start();
				$end     = $booking->get_end();
				if ( $start >= $date_end || $end < $date_start ) {
					continue;
				}
				$multi_day = false;
				// Mark an event if it will be present in multiple days.
				if ( $start < $date_start || $end > $date_end ) {
					$multi_day = true;
				}
				$product     = $booking->get_product();
				$start_date  = $booking->get_start_date( '', wc_time_format() );
				$is_all_day  = $booking->is_all_day();
				$detail_href = admin_url( 'post.php?post=' . $booking->get_id() . '&action=edit' );

				if ( $product ) {
					$title = $product->get_title();
				}

				$id    = $booking->get_id();
				$style = $multi_day ? 'color:' . $this->colours[ $id ]['color'] . ';background-color:' . $this->colours[ $id ]['background'] : '';
			} else { // Extract global availability data.
				$availability = $event;
				$range        = $availability->get_time_range_for_date( $date_start );
				if ( is_null( $range ) ) {
					continue;
				}
				$start           = $range['start'];
				$is_all_day      = $availability->is_all_day();
				$title           = ! empty( $availability->get_title() ) ? $availability->get_title() : __( 'Unavailable', 'woocommerce_bookings' );
				$title          .= ' ' . __( '(From Google Calendar)', 'woocommerce-bookings' );
				$detail_href     = admin_url( 'edit.php?post_type=wc_booking&page=wc_bookings_settings' );
				$event_classes[] = 'no_availability';
				$id              = $event->get_gcal_event_id();
				if ( $availability->date_starts_today( $start ) ) {
					$start_date = $availability->get_formatted_date( $start, '', wc_time_format() );
				} else {
					$start_date = '';
				}
			}

			$booking_count++;
			if ( $start < current_time( 'timestamp' ) ) {
				$event_classes[] = 'past_booking';
			}
			if ( self::MAX_BOOKINGS_PER_DAY >= $booking_count ) {
				echo '<li class="calendar_month_event calendar_event_id_' . $id . '" data-id="' . $id . '"><a href="' . esc_url( $detail_href ) . '"' . 'class="' . esc_attr( implode( ' ', $event_classes ) ) . '" style="' . $style . '">';
				echo ' <span class="booking-calendar-product-title">';
				echo $title;
				echo '</span>';
				echo '<span class="booking-calendar-time-range">';
				if ( $is_all_day ) {
					esc_html_e( 'All Day', 'woocommerce-bookings' );
				} else {
					echo esc_html( str_replace( ':00', '', $start_date ) );
				}
				echo '</span>';
				echo '</a></li>';
			}
		}

		$remaining_bookings_count = $booking_count - self::MAX_BOOKINGS_PER_DAY;
		if ( 0 < $remaining_bookings_count ) {
			/* translators: 1: number of bookings that are not displayed on calendar day */
			$message      = sprintf( __( '%d more', 'woocommerce-bookings' ), $remaining_bookings_count );
			$day_view_url = admin_url( 'edit.php?post_type=wc_booking&page=booking_calendar&view=day&tab=calendar&calendar_day=' . date( 'Y-m-d', $date_start ) );
			echo '<a href="' . esc_attr( $day_view_url ) . '" class="full_day_link">' . esc_html( $message ) . '</a>';
		}
	}

	private function get_available_minutes_for_calendar_day( $day ) {
		$day_start    = strtotime( 'midnight', strtotime( $day ) );
		$day_end      = strtotime( 'midnight +1 day', strtotime( $day ) ) - 1;
		$rules        = WC_Bookings_Controller::get_global_availability_in_date_range( $day_start, $day_end );
		$global_rules = array();

		if ( 0 < count( $rules ) ) {
			$global_rules = WC_Product_Booking_Rule_Manager::process_availability_rules( $rules, 'global', false );
			usort( $global_rules, array( 'WC_Product_Booking_Rule_Manager', 'sort_rules_callback' ) );
			return WC_Product_Booking_Rule_Manager::get_minutes_from_rules( $global_rules, $day_start, range( 0, 1439 ) );
		} else {
			return range( 0, 1439 );
		}
	}

	protected function is_day_unavailable( $day ) {
		$available_minutes = $this->get_available_minutes_for_calendar_day( $day );
		return 0 === count( $available_minutes );
	}

	/**
	 * Determine font color based on background color.
	 * Calculations rely on perceptive luminance (contrast).
	 *
	 * @param string $bg_color Background color as a hex code.
	 *
	 * @return string Font color as a hex code.
	 */
	protected function get_font_color( $bg_color ) {
		$bg_color = hexdec( $bg_color );
		$red      = 0xFF & ( $bg_color >> 0x10 );
		$green    = 0xFF & ( $bg_color >> 0x8 );
		$blue     = 0xFF & $bg_color;

		$luminance = 1 - ( 0.299 * $red + 0.587 * $green + 0.114 * $blue ) / 255;

		return $luminance < 0.5 ? '#000000' : '#ffffff';
	}

	/**
	 * Get color CSS styles for a given list of events.
	 *
	 * @param array $events
	 * @return array Hash event_id => color styles
	 */
	protected function get_event_color_styles( $events ) {
		$colors                = array( '#d7f1bf', '#52d4ad', '#1dbcc0', '#227a95', '#205076', '#17295a', '#fedab9', '#feaa6e', '#ffe800', '#e67e22', '#fd8d67', '#ffb2d0', '#4a2f83', '#4b0057', '#64d72c' );
		$booked_product_colors = array();
		$assigned_colors       = array();
		$index                 = 0;

		foreach ( $events as $event ) {

			if ( 'WC_Global_Availability' === get_class( $event ) ) {
				$assigned_colors[ $event->get_id() ] = '#dbdbdb';
				continue;
			}

			if ( 'WC_Booking' !== get_class( $event ) ) {
				$assigned_colors[ $event->get_id() ] = isset( $colors[ $index ] ) ? $colors[ $index ] : $this->random_color();
				$index++;
				continue;
			}

			if ( ! isset( $booked_product_colors[ $event->get_product_id() ] ) ) {
				$booked_product_colors[ $event->get_product_id() ] = isset( $colors[ $index ] ) ? $colors[ $index ] : $this->random_color();
				$index++;
			}

			$assigned_colors[ $event->get_id() ] = $booked_product_colors[ $event->get_product_id() ];
		}

		return array_map(
			function( $color ) {
				return array(
					'background' => $color,
					'color'      => $this->get_font_color( $color ),
				);
			},
			$assigned_colors
		);
	}

	/**
	 * List bookings on a day.
	 *
	 * @version  1.10.7 [<description>]
	 */
	public function list_bookings_for_day() {
		// Discard bookings that are attached to non-existent bookable products
		$this->events = array_values(
			array_filter(
				$this->events,
				function( $event ) {
					if ( 'WC_Booking' === get_class( $event ) ) {
						return $event->get_product();
					} else {
						return true;
					}
				}
			)
		);

		$assigned_colors = $this->get_event_color_styles( $this->events );

		foreach ( $this->events as $index => $event ) {
			$data = $this->get_booking_data( $event );
			if ( ! is_null( $data ) ) {
				$classes   = array( 'daily_view_booking' );
				$classes[] = ( 'WC_Global_Availability' === get_class( $event ) ) ? 'no_availability' : '';

				echo $this->render_li_element( $data, $assigned_colors[ $event->get_id() ], $classes );
			}
		}
	}

	/**
	 * Add global availability areas to calendar.
	 *
	 * @since  1.13.0
	 */
	public function list_global_availability_for_day() {
		$minutes = range( 0, 1439 );
		sort( $this->global_availability_minutes );
		$minutes_not_available = array_diff( $minutes, $this->global_availability_minutes );
		$not_available_ranges  = $this->split_minutes_array_into_ranges( $minutes_not_available );
		$color_style = array(
			'background' => '#bdbdbd2b',
		);
		$classes = array( 'daily_view_global_availabiltiy' );
		foreach ( $not_available_ranges as $range ){ 
			echo $this->render_li_element( $range, $color_style, $classes );
		}
	}

	/**
	 * Build <li> element for rendering a bookings block.
	 *
	 * @param array $data Bookings data
	 * @param array $color Color data for the event
	 *
	 * @return string
	 */
	protected function render_li_element( $data, $color_style, $classes = array() ) {
		$li_attrs = array(
			'style'          => $color_style,
		);

		$li_attrs = array_merge( $data, $li_attrs );

		$element = '<li ';

		$element .= 'class=" ' . implode( ' ', $classes ) . '" ';

		foreach ( $li_attrs as $attribute => $value ) {
			if ( is_array( $value ) ) {
				$attrs = '';
				foreach ( $value as $attr_key => $attr_val ) {
					$attrs .= "{$attr_key}: {$attr_val};";
				}
				$value = $attrs;
			}

			$element .= "{$attribute}=\"{$value}\" ";
		}

		$element .= '></li>';

		return $element;
	}

	/**
	 * From a list of minutes it creates ranges of consecutive minutes blocks
	 *
	 * @param array $minutes array of minutes
	 *
	 * @return array $array of time ranges.
	 *
	 * @since 1.13.0
	 */
	private function split_minutes_array_into_ranges( $minutes ) {
		$times_ranges = array();
		sort( $minutes );
		$size = count( $minutes );
		$id = 1;
		for ( $i = 0; $i < $size; $i++ ) {
			$range_start = $minutes[ $i ];
			$range_end   = $range_start;
			$j = $i + 1;
			if ( $j < $size ) {
				for ( ; $j < $size; $j++ ) {
					if ( $minutes[ $j - 1 ] === $minutes[ $j ] - 1 ) {
						$range_end = $minutes[$j];
					} else {
						break;
					}
				}
				$i = $j - 1;
			}

			$times_ranges[] = array(
				'data-start'                  => $range_start,
				'data-end'                    => $range_end,
				'data-global-availability-id' => $id,
			);
			$id ++;
		}

		return $times_ranges;
	}


	/**
	 * Get Bookings data to be included in the html element on the calendar.
	 *
	 * @param WC_Booking $booking
	 * @return array
	 */
	protected function get_booking_data( $event ) {
		$day              = strtotime( isset( $_REQUEST['calendar_day'] ) ? wc_clean( $_REQUEST['calendar_day'] ) : date( 'Y-m-d' ) );
		$startday         = strtotime( 'midnight', $day );
		$endday           = strtotime( 'tomorrow', $day );
		$booking_customer = '';
		$booking_id       = is_callable( array( $event, 'get_id' ) ) ? $event->get_id() : '';
		$booking_resource = '';
		$booking_persons  = array();
		$event_url        = '';

		if ( 'WC_Booking' === get_class( $event ) ) {
			$booking          = $event;
			$product          = $booking->get_product();
			$booking_customer = $booking->get_customer()->name ?: '';
			$booking_resource = $booking->get_resource();
			$booking_resource = $booking_resource ? $booking_resource->get_name() : '';
			$event_start      = $booking->get_start();
			$event_end        = $booking->get_end();
			$event_title      = $booking->get_product()->get_name();
			$event_url        = admin_url( 'post.php?post=' . $booking->get_id() . '&action=edit' );
			$booking_id       = $booking->get_id();

			if ( $booking->has_persons() ) {
				foreach ( $booking->get_persons() as $id => $qty ) {
					if ( 0 === $qty ) {
						continue;
					}

					$person_type = ( 0 < $id ) ? get_the_title( $id ) : __( 'Person(s)', 'woocommerce-bookings' );
					/* translators: 1: person type 2: quantity */
					$booking_persons[] = sprintf( __( '%1$s: %2$d', 'woocommerce-bookings' ), $person_type, $qty );
				}
			}

			if ( 'hour' === $product->get_duration_unit() ) {
				/* translators: 1: start time 2: end time */
				$event_time = $booking->is_all_day() ? __( 'All Day', 'woocommerce-bookings' ) : sprintf( __( '%1$s to %2$s', 'woocommerce-bookings' ), $booking->get_start_date( '', wc_time_format() ), $booking->get_end_date( '', wc_time_format() ) );
				$event_date = $booking->get_start_date( 'l, M j, Y', '' );
			} else {
				$event_time = $booking->get_end_date( 'l, M j, Y' );
				$event_date = $booking->get_start_date( 'l, M j, Y' );

				// If the start date is same as the end date, then this is all day for that particular date
				if ( $event_time == $event_date ) {
					$event_time = __( 'All Day', 'woocommerce-bookings' );
				}
			}
		} else {
			$availability = $event;
			$range        = $availability->get_time_range_for_date( $day );
			if ( is_null( $range ) ) {
				return null;
			}
			$event_start = $range['start'];
			$event_end   = $range['end'];

			$event_time  = $availability->get_formatted_date( $event_end, 'l, M j, Y' );
			$event_date  = $availability->get_formatted_date( $event_start, 'l, M j, Y' );
			// If the start date is same as the end date, then this is all day for that particular date
			if ( ( $event_time == $event_date ) || $availability->is_all_day() ) {
				$event_time = __( 'All Day', 'woocommerce-bookings' );
			}

			$event_title      = ! empty( $availability->get_title() ) ? $availability->get_title() : __( 'Unavailable', 'woocommerce_bookings' );
			$event_title     .= ' ' . __( '(From Google Calendar)', 'woocommerce-bookings' );
		}

		$booking_persons = ! empty( $booking_persons ) ? implode( ', ', $booking_persons ) : '';

		$start = strtotime( date( 'H:i', $event_start ), 0 ) / 60;
		if ( $event_start < $startday ) {
			$start = 0;
		}

		$end = strtotime( date( 'H:i', $event_end ), 0 ) / 60;
		if ( $endday < $event_end ) {
			$end = 1440;
		}

		$end = $end ?: 1440;

		return array(
			'data-booking-customer' => esc_attr( $booking_customer ),
			'data-booking-resource' => esc_attr( $booking_resource ),
			'data-booking-persons'  => esc_attr( $booking_persons ),
			'data-booking-time'     => esc_attr( $event_time ),
			'data-booking-date'     => esc_attr( $event_date ),
			'data-booking-title'    => esc_attr( $event_title ),
			'data-booking-url'      => esc_attr( $event_url ),
			'data-booking-id'       => esc_attr( $booking_id ),
			'data-booking-start'    => esc_attr( $start ),
			'data-booking-end'      => esc_attr( $end ),
		);
	}

	/**
	 * Get a random colour.
	 */
	public function random_color() {
		return sprintf( '#%06X', mt_rand( 0, 0xFFFFFF ) );
	}

	/**
	 * Get a tooltip in day view.
	 *
	 * @param  object $booking
	 * @return string
	 */
	public function get_tip( $booking ) {
		_deprecated_function( __METHOD__, '1.13' );

		$return = '';

		$return .= '#' . $booking->get_id() . ' - ';
		$product = $booking->get_product();

		if ( $product ) {
			$return .= $product->get_title();
		}

		$customer = $booking->get_customer();

		if ( $customer && ! empty( $customer->name ) ) {
			$return .= '<br/>' . __( 'Booked by', 'woocommerce-bookings' ) . ' ' . $customer->name;
		}

		$resource = $booking->get_resource();

		if ( $resource ) {
			$return .= '<br/>' . __( 'Resource #', 'woocommerce-bookings' ) . $resource->ID . ' - ' . $resource->post_title;
		}

		$persons  = $booking->get_persons();

		foreach ( $persons as $person_id => $person_count ) {
			$return .= '<br/>';

			/* translators: 1: person id 2: person name 3: person count */
			$return .= sprintf( __( 'Person #%1$s - %2$s (%3$s)', 'woocommerce-bookings' ), $person_id, get_the_title( $person_id ), $person_count );
		}

		return esc_attr( $return );
	}

	/**
	 * Filters products for narrowing search.
	 *
	 * @param bool $include_resources If true, each product's resources will be included.
	 * @return array list of bookable products.
	 */
	public function product_filters( $include_resources = true ) {
		$filters  = array();
		$products = WC_Bookings_Admin::get_booking_products();

		foreach ( $products as $product ) {
			$filters[ $product->get_id() ] = $product->get_name();

			if ( $include_resources ) {
				$resources = $product->get_resources();

				foreach ( $resources as $resource ) {
					$filters[ $resource->get_id() ] = '&nbsp;&nbsp;&nbsp;' . $resource->get_name();
				}
			}
		}

		return $filters;
	}

	/**
	 * Filters resources for narrowing search.
	 */
	public function resources_filters() {
		$filters   = array();
		$resources = WC_Bookings_Admin::get_booking_resources();

		foreach ( $resources as $resource ) {
			$filters[ $resource->get_id() ] = $resource->get_name();
		}

		return $filters;
	}
}
