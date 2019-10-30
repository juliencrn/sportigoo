<?php
/**
 * Class WC_Bookings_Google_Calendar_Connection
 *
 * @package WooCommerce/Bookings
 */

/**
 * Google Calendar Connection.
 */
class WC_Bookings_Google_Calendar_Connection extends WC_Settings_API {

	const TOKEN_TRANSIENT_TIME = 3500;

	const DAYS_OF_WEEK = array(
		1 => 'monday',
		2 => 'tuesday',
		3 => 'wednesday',
		4 => 'thursday',
		5 => 'friday',
		6 => 'saturday',
		7 => 'sunday',
	);

	/**
	 * The single instance of the class.
	 *
	 * @var $_instance
	 * @since 1.13.0
	 */
	protected static $_instance = null;

	/**
	 * Name for nonce to update calendar settings.
	 *
	 * @since 1.13.0
	 * @var string self::NONCE_NAME
	 */
	const NONCE_NAME = 'bookings_calendar_settings_nonce';

	/**
	 * Action name for nonce to update calendar settings.
	 *
	 * @since 1.13.0
	 * @var string self::NONCE_ACTION
	 */
	const NONCE_ACTION = 'submit_bookings_calendar_settings';

	/**
	 * Debug. 'yes' or 'no'.
	 *
	 * @var string
	 */
	protected $debug;

	/**
	 * Google Client Id
	 *
	 * @var string
	 */
	protected $client_id;
	/**
	 * Google client secret.
	 *
	 * @var string
	 */
	protected $client_secret;
	/**
	 * Google Calendar Id.
	 *
	 * @var string
	 */
	protected $calendar_id;
	/**
	 * Google sync token.
	 *
	 * @var string
	 */
	protected $sync_token;

	/**
	 * Redirect Url.
	 *
	 * @var string
	 */
	protected $redirect_uri;

	/**
	 * If the service is currently is a poll operation with google.
	 *
	 * @var bool
	 */
	protected $polling = false;

	/**
	 * Google calendar sync preference one of: 'both_ways' or 'one_way'
	 *
	 * @var string
	 */
	protected $sync_preference;


	/**
	 * WooCommerce Logger instance.
	 *
	 * @var WC_Logger_Interface
	 */
	protected $log;

	/**
	 * Google Service from SDK.
	 *
	 * @var Google_Service_Calendar
	 */
	protected $service;

	/**
	 * Init and hook in the integration.
	 */
	private function __construct() {

		$this->plugin_id = 'wc_bookings_';
		$this->id        = 'google_calendar';

		// API.
		$this->redirect_uri = WC()->api_request_url( 'wc_bookings_google_calendar' );

		// Define user set variables.
		$this->client_id       = $this->get_option( 'client_id' );
		$this->client_secret   = $this->get_option( 'client_secret' );
		$this->calendar_id     = $this->get_option( 'calendar_id' );
		$this->sync_preference = $this->get_option( 'sync_preference' );
		$this->debug           = $this->get_option( 'debug' );

		// Actions.
		add_action( 'woocommerce_api_wc_bookings_google_calendar', array( $this, 'oauth_redirect' ) );

		add_action( 'init', array( $this, 'register_booking_update_hooks' ) );

		add_action( 'woocommerce_before_booking_global_availability_object_save', array( $this, 'sync_global_availability' ) );

		add_action( 'woocommerce_bookings_before_delete_global_availability', array( $this, 'delete_global_availability' ) );

		add_action( 'trashed_post', array( $this, 'remove_booking' ) );
		add_action( 'untrashed_post', array( $this, 'sync_untrashed_booking' ) );
		add_action( 'wc-booking-poll-google-cal', array( $this, 'poll_google_calendar_events' ) );
		add_action( 'init', array( $this, 'maybe_schedule_poller' ) );

		if ( is_admin() ) {
			$this->init_calendar_settings();

			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'admin_init', array( $this, 'maybe_save_settings' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		// Active logs.
		if ( 'yes' === $this->debug ) {
			if ( class_exists( 'WC_Logger' ) ) {
				$this->log = new WC_Logger();
			} else {
				$this->log = WC()->logger();
			}
		}

		if ( isset( $_POST['wc_bookings_google_calendar_redirect'] ) && $_POST['wc_bookings_google_calendar_redirect'] && empty( $_POST['save'] ) ) {
			$this->process_calendar_redirect();
		}

		$this->sync_token = get_transient( 'wc_bookings_gcalendar_sync_token' );
	}

	/**
	 * Enqueues admin js scripts.
	 *
	 * @since 1.3.12
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'wc_bookings_calendar_connection_scripts', WC_BOOKINGS_PLUGIN_URL . '/dist/admin-calendar-connection.js', array( 'jquery' ), WC_BOOKINGS_VERSION, true );
	}

	/**
	 * Initialize settings and form data.
	 *
	 * @since 1.13.0
	 * @return void
	 */
	public function init_calendar_settings() {
		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();
	}

	/**
	 * Returns WC_Bookings_Google_Calendar_Settings singleton
	 *
	 * Ensures only one instance of WC_Bookings_Google_Calendar_Settings is created.
	 *
	 * @since 1.13.0
	 * @return WC_Bookings_Google_Calendar_Connection - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Update settings values from form.
	 *
	 * @since 1.13.0
	 */
	public function maybe_save_settings() {
		if ( isset( $_POST['Submit'] )
			&& isset( $_POST[ self::NONCE_NAME ] )
			&& wp_verify_nonce( wc_clean( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION ) ) {
				$this->process_admin_options();

			echo '<div class="updated"><p>' . esc_html__( 'Settings saved', 'woocommerce-bookings' ) . '</p></div>';
		}
	}

	/**
	 * Generates full HTML form for the instance settings.
	 *
	 * @since 1.13.0
	 */
	public static function generate_form_html() {
		?>
			<form method="post" action="" id="bookings_settings">
				<?php self::instance()->admin_options(); ?>
				<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'woocommerce-bookings' ); ?>" />
					<?php wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME ); ?>
				</p>
			</form>
		<?php
	}

	/**
	 * Attempt to schedule/unschedule poller once AS is ready.
	 */
	public function maybe_schedule_poller() {
		if ( 'both_ways' !== $this->sync_preference || ! $this->is_integration_active() ) {
			as_unschedule_action( 'wc-booking-poll-google-cal', array(), 'bookings' );

			return;
		}

		if ( ! as_next_scheduled_action( 'wc-booking-poll-google-cal' ) ) {
			$poll_interval = 1; // minutes.
			as_schedule_recurring_action( time(), $poll_interval * MINUTE_IN_SECONDS, 'wc-booking-poll-google-cal', array(), 'bookings' );
		}
	}

	/**
	 * Registers booking object lifecycle events.
	 * Needs to happen after init because of the dynamic hook names.
	 */
	public function register_booking_update_hooks() {
		foreach ( $this->get_booking_is_paid_statuses() as $status ) {
			// We have to do it this way because of the dynamic hook name.
			add_action( 'woocommerce_booking_' . $status, array( $this, 'sync_new_booking' ) );
		}

		add_action( 'woocommerce_booking_cancelled', array( $this, 'remove_booking' ) );
		add_action( 'woocommerce_booking_process_meta', array( $this, 'sync_edited_booking' ) );
	}

	/**
	 * Returns an authorized API client.
	 *
	 * @return Google_Client the authorized client object
	 */
	protected function get_client() {
		$client = new Google_Client();
		$client->setApplicationName( 'WooCommerce Bookings Google Calendar Integration' );
		$client->setScopes( Google_Service_Calendar::CALENDAR );

		$client->setClientId( $this->client_id );
		$client->setClientSecret( $this->client_secret );
		$client->setRedirectUri( $this->redirect_uri );

		$access_token  = get_transient( 'wc_bookings_gcalendar_access_token' );
		$refresh_token = get_option( 'wc_bookings_gcalendar_refresh_token' );

		$client->setAccessType( 'offline' );

		// Refresh the token if it's expired. Note that we need a refresh token for this,
		// and we get it initially by calling `fetchAccessTokenWithAuthCode` in `oauth_redirect`.
		if ( $refresh_token && empty( $access_token ) ) {
			$client->fetchAccessTokenWithRefreshToken( $refresh_token );
			$access_token = $client->getAccessToken();
			unset( $access_token['refresh_token'] ); // unset this since we store it in an option.
			set_transient( 'wc_bookings_gcalendar_access_token', $access_token, self::TOKEN_TRANSIENT_TIME );
		}

		// It may be empty, e.g. in case refresh token is empty.
		if ( ! empty( $access_token ) ) {
			$access_token['refresh_token'] = $refresh_token;
			try {
				$client->setAccessToken( $access_token );
			} catch ( InvalidArgumentException $e ) {
				// Something is wrong with the access token, customer should try to connect again.
				$this->log->add( $this->id, sprintf( 'Invalid access token. Reconnect with Google necessary. Code %s. Message: %s.', $e->getCode(), $e->getMessage() ) );
			}
		}

		return $client;
	}

	/**
	 * Set a new sync token (used when Google returns one)
	 *
	 * @param string $sync_token Google sync token.
	 */
	protected function set_sync_token( $sync_token ) {
		set_transient( 'wc_bookings_gcalendar_sync_token', $sync_token, self::TOKEN_TRANSIENT_TIME );
		$this->sync_token = $sync_token;
	}

	/**
	 * This is called by API requesters. We are not doing it on the constructor
	 * as it takes some time to init the service, so only init when necessary.
	 */
	protected function maybe_init_service() {
		if ( empty( $this->service ) ) {
			$this->service = new Google_Service_Calendar( $this->get_client() );
		}
	}

	/**
	 * Get Google Events (paginated)
	 *
	 * @param array $params Current parameters.
	 * @return array
	 */
	protected function get_event_page( $params = array() ) {
		$this->maybe_init_service();

		$request_params = array(
			'timeZone' => wc_booking_get_timezone_string(),
		);
		if ( ! empty( $this->page_token ) ) {
			$request_params              = $params;
			$request_params['pageToken'] = $this->page_token;
		} elseif ( ! empty( $this->sync_token ) ) {
			$request_params['syncToken'] = $this->sync_token;
			if ( isset( $params['maxResults'] ) ) {
				$request_params['maxResults'] = $params['maxResults'];
			}
		} else {
			$request_params = $params;
		}

		try {
			$results = $this->service->events->listEvents( $this->calendar_id, $request_params );
		} catch ( Exception $e ) {
			return array(
				'events'   => array(),
				'has_next' => false,
				'error'    => $e->getCode(),
			);
		}

		$this->page_token = $results->getNextPageToken();

		$sync_token = $results->getNextSyncToken();
		if ( ! empty( $sync_token ) ) {
			$this->set_sync_token( $sync_token );
		}

		return array(
			'events'   => $results->getItems(),
			'has_next' => empty( $sync_token ),
			'error'    => 0,
		);
	}

	/**
	 * Get a list of calendar events.
	 *
	 * @return array
	 */
	public function get_events() {
		$events = array();

		$params = apply_filters(
			'woocommerce_bookings_gcal_events_request',
			array(
				'singleEvents' => false,
				'timeMin'      => date( 'c' ),
				'timeMax'      => date( 'c', strtotime( 'now +2 years' ) ),
				'timeZone'     => wc_booking_get_timezone_string(),
			)
		);

		do {
			$page_result = $this->get_event_page( $params );

			// Full sync case.
			if ( 410 === (int) $page_result['error'] ) {
				$page_result['has_next'] = true;
				$this->set_sync_token( '' ); // Unset expired token.
				continue; // Repeat same request.
			}

			if ( 0 !== (int) $page_result['error'] ) {
				if ( 'yes' === $this->debug ) {
					$this->log->add( $this->id, $page_result['error'] );
				}
				// TODO: Unhandled error. Handle it somehow.
			}

			$events = array_merge( $events, $page_result['events'] );
		} while ( $page_result['has_next'] ); // Final page will include a syncToken.

		return $events;
	}

	/**
	 * Method for polling data from Google API.
	 *
	 * Sync path: Google API -> Bookings
	 * The sync path Bookings -> Google API will be handled by `action` and `filter` events.
	 */
	public function poll_google_calendar_events() {
		if ( 'both_ways' !== $this->sync_preference || ! $this->is_integration_active() ) {
			return;
		}

		$this->polling = true;
		try {
			if ( 'yes' === $this->debug ) {
				$this->log->add( $this->id, 'Getting Google Calendar List from Google Calendar API...' );
			}

			/**
			 * Global Availability Data store instance.
			 *
			 * @var WC_Global_Availability_Data_Store $global_availability_data_store
			 */
			$global_availability_data_store = WC_Data_Store::load( WC_Global_Availability::DATA_STORE );

			$events = $this->get_events();

			foreach ( $events as $event ) {
				$availabilities = $global_availability_data_store->get_all(
					array(
						array(
							'key'     => 'gcal_event_id',
							'value'   => $event['id'],
							'compare' => '=',
						),
					)
				);

				if ( empty( $availabilities ) ) {

					$booking_ids = WC_Booking_Data_Store::get_booking_ids_by( array( 'google_calendar_event_id' => $event['id'] ) );

					if ( ! empty( $booking_ids ) ) {
						// Google event is an existing booking not a manually created event for the global availability.
						// Ignore changes for now in future we may allow editing bookings from google calendar.
						continue;
					}

					// If no global availability found, just create one.
					$global_availability = new WC_Global_Availability();
					if ( 'cancelled' !== $event->getStatus() ) {
						$this->update_global_availability_from_event( $global_availability, $event );
						$global_availability->save();
					}

					continue;
				}

				foreach ( $availabilities as $availability ) {
					$event_date        = new WC_DateTime( $event['updated'] );
					$availability_date = $availability->get_date_modified();

					if ( $event_date > $availability_date ) {
						// Sync Google Event -> Global Availability.
						if ( 'cancelled' !== $event->getStatus() ) {

							$this->update_global_availability_from_event( $availability, $event );
							$availability->save();
						} else {
							$availability->delete();
						}
					}
				}
			}
		} catch ( Exception $e ) {
			if ( 'yes' === $this->debug ) {
				$this->log->add( $this->id, 'Error while getting list of events' );
			}
		}
		$this->polling = false;
	}

	/**
	 * Process Calendar redirect
	 *
	 * @since 1.11
	 */
	public function process_calendar_redirect() {
		$client   = $this->get_client();
		// We need this to get the refresh token every time.
		$client->setPrompt( 'consent' );
		$auth_url = $client->createAuthUrl();
		wp_redirect( $auth_url );
		exit;
	}

	/**
	 * Initialize integration settings form fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'client_id'       => array(
				'title'       => __( 'Client ID', 'woocommerce-bookings' ),
				'type'        => 'text',
				'description' => __( 'Enter with your Google Client ID.', 'woocommerce-bookings' ),
				'desc_tip'    => true,
				'default'     => '',
			),
			'client_secret'   => array(
				'title'       => __( 'Client Secret', 'woocommerce-bookings' ),
				'type'        => 'text',
				'description' => __( 'Enter with your Google Client Secret.', 'woocommerce-bookings' ),
				'desc_tip'    => true,
				'default'     => '',
			),
			'calendar_id'     => array(
				'title'       => __( 'Calendar ID', 'woocommerce-bookings' ),
				'type'        => 'text',
				'description' => __( 'Enter with your Calendar ID.', 'woocommerce-bookings' ),
				'desc_tip'    => true,
				'default'     => '',
			),
			'sync_preference' => array(
				'type'        => 'select',
				'title'       => __( 'Sync Preference', 'woocommerce-bookings' ),
				'options'     => array(
					'both_ways' => __( 'Sync both ways - between Store and Google', 'woocommerce-bookings' ),
					'one_way'   => __( 'Sync one way - from Store to Google', 'woocommerce-bookings' ),
				),
				'description' => __( 'Manage the sync flow between your Store calendar and Google calendar.', 'woocommerce-bookings' ),
				'desc_tip'    => true,
				'default'     => 'one_way',
			),
			'authorization'   => array(
				'title' => __( 'Authorization', 'woocommerce-bookings' ),
				'type'  => 'google_calendar_authorization',
			),
			'testing'         => array(
				'title'       => __( 'Testing', 'woocommerce-bookings' ),
				'type'        => 'title',
				'description' => '',
			),
			'debug'           => array(
				'title'       => __( 'Debug Log', 'woocommerce-bookings' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable logging', 'woocommerce-bookings' ),
				'default'     => 'no',
				/* translators: 1: log file path */
				'description' => sprintf( __( 'Log Google Calendar events, such as API requests, inside %s', 'woocommerce-bookings' ), '<code>woocommerce/logs/' . $this->id . '-' . sanitize_file_name( wp_hash( $this->id ) ) . '.txt</code>' ),
			),
		);
	}

	/**
	 * Validate the Google Calendar Authorization field.
	 *
	 * @param mixed $key Current Key.
	 *
	 * @return string
	 */
	public function validate_google_calendar_authorization_field( $key ) {
		return '';
	}

	/**
	 * Generate the Google Calendar Authorization field.
	 *
	 * @param  mixed $key
	 * @param  array $data
	 *
	 * @return string
	 */
	public function generate_google_calendar_authorization_html( $key, $data ) {
		$options       = $this->plugin_id . $this->id . '_';
		$client_id     = isset( $_POST[ $options . 'client_id' ] ) ? sanitize_text_field( $_POST[ $options . 'client_id' ] ) : $this->client_id;
		$client_secret = isset( $_POST[ $options . 'client_secret' ] ) ? sanitize_text_field( $_POST[ $options . 'client_secret' ] ) : $this->client_secret;
		$calendar_id   = isset( $_POST[ $options . 'calendar_id' ] ) ? sanitize_text_field( $_POST[ $options . 'calendar_id' ] ) : $this->calendar_id;
		$access_token  = $this->get_client()->getAccessToken();
		$refresh_token = get_option( 'wc_bookings_gcalendar_refresh_token' );

		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<?php echo wp_kses_post( $data['title'] ); ?>
			</th>
			<td class="forminp">
				<input type="hidden" name="wc_bookings_google_calendar_redirect" id="wc_bookings_google_calendar_redirect">
				<?php if ( ! $client_id || ! $client_secret || ! $calendar_id ) : ?>
					<p style="color:red;"><?php esc_html_e( 'Please fill out all required fields from above.', 'woocommerce-bookings' ); ?></p>
				<?php elseif ( ! $refresh_token ) : ?>
					<p class="submit"><a class="button button-primary wc-bookings-calender-connect"><?php esc_html_e( 'Connect with Google', 'woocommerce-bookings' ); ?></a></p>
				<?php elseif ( $access_token ) : ?>
					<p><?php esc_html_e( 'Successfully authenticated.', 'woocommerce-bookings' ); ?></p>
					<p class="submit"><a class="button button-primary" href="<?php echo esc_url( add_query_arg( array( 'logout' => 'true' ), $this->redirect_uri ) ); ?>"><?php esc_html_e( 'Disconnect', 'woocommerce-bookings' ); ?></a></p>
				<?php else : ?>
					<p><?php esc_html_e( 'Unable to authenticate.' ); ?></p>
					<p class="submit"><a class="button button-primary wc-bookings-calender-connect"><?php esc_html_e( 'Re-Connect with Google', 'woocommerce-bookings' ); ?></a></p>
				<?php endif; ?>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}

	/**
	 * Admin Options.
	 */
	public function admin_options() {
		/* translators: 1: link to google developers console 2: WP redirect uri */
		echo '<p>' . __( 'To start syncing with Google Calendar, you’ll need to create credentials and add them here.', 'woocommerce-bookings' ) . ' <a href="https://docs.woocommerce.com/document/google-calendar-integration/" target="_blank">' . __( 'Learn how to create credentials', 'woocommerce-bookings' ) . '</a></p>';

		echo '<table class="form-table">';
			$this->generate_settings_html();
		echo '</table>';

		echo '<div><input type="hidden" name="section" value="' . $this->id . '" /></div>';
	}

	/**
	 * OAuth Logout.
	 *
	 * @return bool
	 */
	protected function oauth_logout() {
		if ( 'yes' === $this->debug ) {
			$this->log->add( $this->id, 'Leaving the Google Calendar app...' );
		}

		$client = $this->get_client();
		$client->revokeToken();

		delete_option( 'wc_bookings_gcalendar_refresh_token' );
		delete_transient( 'wc_bookings_gcalendar_sync_token' );
		delete_transient( 'wc_bookings_gcalendar_access_token' );

		if ( 'yes' === $this->debug ) {
			$this->log->add( $this->id, 'Left the Google Calendar App. successfully' );
		}

		return true;
	}

	/**
	 * Process the oauth redirect.
	 *
	 * @return void
	 */
	public function oauth_redirect() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Permission denied!', 'woocommerce-bookings' ) );
		}

		$redirect_args = array(
			'post_type' => 'wc_booking',
			'page'      => 'wc_bookings_settings',
			'tab'       => 'connection',
		);

		// OAuth.
		if ( isset( $_GET['code'] ) ) {
			$code         = sanitize_text_field( $_GET['code'] );
			$client       = $this->get_client();
			$access_token = $client->fetchAccessTokenWithAuthCode( $code );

			if ( empty( $access_token ) ) {
				$redirect_args['wc_gcalendar_oauth'] = 'fail';

				wp_redirect( add_query_arg( $redirect_args, admin_url( '/edit.php?' ) ), 301 );
				exit;
			}

			unset( $access_token['refresh_token'] ); // unset this since we store it in an option.
			set_transient( 'wc_bookings_gcalendar_access_token', $access_token, self::TOKEN_TRANSIENT_TIME );
			update_option( 'wc_bookings_gcalendar_refresh_token', $client->getRefreshToken() );
			$redirect_args['wc_gcalendar_oauth'] = 'success';

			wp_safe_redirect( add_query_arg( $redirect_args, admin_url( '/edit.php?' ) ), 301 );
			exit;
		}
		if ( isset( $_GET['error'] ) ) {
			$redirect_args['wc_gcalendar_oauth'] = 'fail';

			wp_redirect( add_query_arg( $redirect_args, admin_url( '/edit.php?' ) ), 301 );
			exit;
		}

		// Logout.
		if ( isset( $_GET['logout'] ) ) {
			$logout                               = $this->oauth_logout();
			$redirect_args['wc_gcalendar_logout'] = ( $logout ) ? 'success' : 'fail';

			wp_redirect( add_query_arg( $redirect_args, admin_url( '/edit.php?' ) ), 301 );
			exit;
		}

		wp_die( __( 'Invalid request!', 'woocommerce-bookings' ) );
	}

	/**
	 * Display admin screen notices.
	 */
	public function admin_notices() {
		$screen = get_current_screen();

		if ( 'wc_booking_page_wc_bookings_settings' == $screen->id && isset( $_GET['wc_gcalendar_oauth'] ) ) {
			if ( 'success' == $_GET['wc_gcalendar_oauth'] ) {
				echo '<div class="updated fade"><p><strong>' . __( 'Google Calendar', 'woocommerce-bookings' ) . '</strong> ' . __( 'Account connected successfully!', 'woocommerce-bookings' ) . '</p></div>';
			} else {
				echo '<div class="error fade"><p><strong>' . __( 'Google Calendar', 'woocommerce-bookings' ) . '</strong> ' . __( 'Failed to connect to your account, please try again, if the problem persists, turn on Debug Log option and see what is happening.', 'woocommerce-bookings' ) . '</p></div>';
			}
		}

		if ( 'wc_booking_page_wc_bookings_settings' == $screen->id && isset( $_GET['wc_gcalendar_logout'] ) ) {
			if ( 'success' == $_GET['wc_gcalendar_logout'] ) {
				echo '<div class="updated fade"><p><strong>' . __( 'Google Calendar', 'woocommerce-bookings' ) . '</strong> ' . __( 'Account disconnected successfully!', 'woocommerce-bookings' ) . '</p></div>';
			} else {
				echo '<div class="error fade"><p><strong>' . __( 'Google Calendar', 'woocommerce-bookings' ) . '</strong> ' . __( 'Failed to disconnect to your account, please try again, if the problem persists, turn on Debug Log option and see what is happening.', 'woocommerce-bookings' ) . '</p></div>';
			}
		}
	}

	/**
	 * Sync new Booking with Google Calendar.
	 *
	 * @param  int $booking_id Booking ID.
	 *
	 * @return void
	 */
	public function sync_new_booking( $booking_id ) {
		if ( $this->is_edited_from_meta_box() || 'wc_booking' !== get_post_type( $booking_id ) ) {
			return;
		}
		$this->sync_booking( $booking_id );
	}

	/**
	 * Check if Google Calendar settings are supplied and we're authenticated.
	 *
	 * @return bool True is calendar is set, false otherwise.
	 */
	public function is_integration_active() {
		$refresh_token = get_option( 'wc_bookings_gcalendar_refresh_token' );

		return ! empty( $refresh_token ) && ! empty( $this->client_id ) && ! empty( $this->client_secret ) && ! empty( $this->calendar_id );
	}

	/**
	 * Sync an event resource with Google Calendar.
	 * https://developers.google.com/google-apps/calendar/v3/reference/events
	 *
	 * @param   int $booking_id Booking ID.
	 * @return  object|boolean Parsed JSON data from the http request or false if error
	 */
	public function get_event_resource( $booking_id ) {
		if ( $booking_id < 0 ) {
			return false;
		}

		$booking  = get_wc_booking( $booking_id );
		$event_id = $booking->get_google_calendar_event_id();
		$event    = false;

		$this->maybe_init_service();

		try {
			$event = $this->service->events->get( $this->calendar_id, $event_id );
		} catch ( Exception $e ) {
			if ( 'yes' === $this->debug ) {
				$this->log->add( $this->id, 'Error while getting event for Booking ' . $booking_id . ': ' . $e->getMessage() );
			}
		}

		return $event;
	}

	/**
	 * Sync Booking with Google Calendar.
	 *
	 * @param  int $booking_id Booking ID.
	 */
	public function sync_booking( $booking_id ) {
		if ( ! $this->is_integration_active() || 'wc_booking' !== get_post_type( $booking_id ) ) {
			return;
		}

		$this->maybe_init_service();

		// Booking data.
		$booking     = get_wc_booking( $booking_id );
		$event_id    = $booking->get_google_calendar_event_id();
		$product_id  = $booking->get_product_id();
		$order       = $booking->get_order();
		$product     = wc_get_product( $product_id );
		$resource    = wc_booking_get_product_resource( $product_id, $booking->get_resource_id() );
		$timezone    = wc_booking_get_timezone_string();
		$description = '';

		$booking_data = array(
			__( 'Booking ID', 'woocommerce-bookings' )   => $booking_id,
			__( 'Booking Type', 'woocommerce-bookings' ) => is_object( $resource ) ? $resource->get_title() : '',
			__( 'Persons', 'woocommerce-bookings' )      => $booking->has_persons() ? array_sum( $booking->get_persons() ) : 0,
		);

		foreach ( $booking_data as $key => $value ) {
			if ( empty( $value ) ) {
				continue;
			}

			$description .= sprintf( '%s: %s', rawurldecode( $key ), rawurldecode( $value ) ) . PHP_EOL;
		}

		$edit_booking_url = admin_url( sprintf( 'post.php?post=%s&action=edit', $booking_id ) );

		// Add read-only message.
		/* translators: %s URL to edit booking */
		$description .= PHP_EOL . sprintf( __( 'NOTE: this event cannot be edited in Google Calendar. If you need to make changes, <a href="%s" target="_blank">please edit this booking in WooCommerce</a>.', 'woocommerce-bookings' ), $edit_booking_url );

		if ( is_a( $order, 'WC_Order' ) ) {
			foreach ( $order->get_items() as $order_item ) {
				foreach ( $order_item->get_meta_data() as $order_meta_data ) {
					$the_meta_data = $order_meta_data->get_data();

					if ( is_serialized( $the_meta_data['value'] ) ) {
						continue;
					}

					$description .= sprintf( '%s: %s', html_entity_decode( $the_meta_data['key'] ), html_entity_decode( $the_meta_data['value'] ) ) . PHP_EOL;
				}
			}
		}

		$event = $this->get_event_resource( $booking_id );
		if ( empty( $event ) ) {
			$event = new Google_Service_Calendar_Event();
		}

		// If the user edited the description on the Google Calendar side we want to keep that data intact.
		if ( empty( trim( $event->getDescription() ) ) ) {
			$event->setDescription( wp_kses_post( $description ) );
		}

		// Set the event data.
		$event->setSummary( wp_kses_post( '#' . $booking->get_id() . ' - ' . ( $product ? html_entity_decode( $product->get_title() ) : __( 'Booking', 'woocommerce-bookings' ) ) ) );

		// Set the event start and end dates.
		$start = new Google_Service_Calendar_EventDateTime();
		$end   = new Google_Service_Calendar_EventDateTime();

		if ( $booking->is_all_day() ) {
			// 1440 min = 24 hours. Bookings includes 'end' in its set of days, where as GCal uses that
			// as the cut off, so we need to add 24 hours to include our final 'end' day.
			// https://developers.google.com/google-apps/calendar/v3/reference/events/insert
			$start->setDate( date( 'Y-m-d', $booking->get_start() ) );
			$end->setDate( date( 'Y-m-d', $booking->get_end() + 1440 ) );
		} else {
			$start->setDateTime( date( 'Y-m-d\TH:i:s', $booking->get_start() ) );
			$start->setTimeZone( $timezone );
			$end->setDateTime( date( 'Y-m-d\TH:i:s', $booking->get_end() ) );
			$end->setTimeZone( $timezone );
		}

		$event->setStart( $start );
		$event->setEnd( $end );

		/**
		 * Update Google event before sync.
		 *
		 * Optional filter to allow third parties to update content of Google event when a booking is created or updated.
		 *
		 * @param Google_Service_Calendar_Event $event Google event object being added or updated.
		 * @param WC_Booking                    $booking Booking object being synced to Google calendar.
		 */
		$event = apply_filters( 'woocommerce_bookings_gcalendar_sync', $event, $booking );

		if ( empty( $event->getId() ) ) {
			$event = $this->service->events->insert( $this->calendar_id, $event );
		} else {
			$this->service->events->update( $this->calendar_id, $event->getId(), $event );
		}

		$booking->set_google_calendar_event_id( wc_clean( $event->getId() ) );

		update_post_meta( $booking->get_id(), '_wc_bookings_gcalendar_event_id', $event->getId() );
	}

	/**
	 * Sync Booking with Google Calendar when booking is edited.
	 *
	 * @param  int $booking_id Booking ID.
	 *
	 * @return void
	 */
	public function sync_edited_booking( $booking_id ) {
		if ( ! $this->is_edited_from_meta_box() ) {
			return;
		}
		$this->maybe_sync_booking_from_status( $booking_id );
	}

	/**
	 * Sync Booking with Google Calendar when booking is untrashed.
	 *
	 * @param  int $booking_id Booking ID.
	 *
	 * @return void
	 */
	public function sync_untrashed_booking( $booking_id ) {
		$this->maybe_sync_booking_from_status( $booking_id );
	}

	/**
	 * Remove/cancel the booking in Google Calendar
	 *
	 * @param  int $booking_id Booking ID.
	 *
	 * @return void
	 */
	public function remove_booking( $booking_id ) {
		if ( 'wc_booking' !== get_post_type( $booking_id ) ) {
			return;
		}

		$this->maybe_init_service();

		$booking  = get_wc_booking( $booking_id );
		$event_id = $booking->get_google_calendar_event_id();

		if ( $event_id ) {
			try {
				$resp = $this->service->events->delete( $this->calendar_id, $event_id );

				if ( 204 == $resp->getStatusCode() ) {
					if ( 'yes' === $this->debug ) {
						$this->log->add( $this->id, 'Booking removed successfully!' );
					}

					// Remove event ID
					update_post_meta( $booking->get_id(), '_wc_bookings_gcalendar_event_id', '' );
				} else {
					if ( 'yes' === $this->debug ) {
						$this->log->add( $this->id, 'Error while removing the booking #' . $booking->get_id() . ': ' . print_r( $resp, true ) );
					}
				}
			} catch ( Exception $e ) {
				if ( 'yes' === $this->debug ) {
					$this->log->add( $this->id, 'Error while deleting event from Google: ' . $e->getMessage() );
				}
			}
		}
	}

	/**
	 * Maybe remove / sync booking based on booking status.
	 *
	 * @param int $booking_id Booking ID.
	 *
	 * @return void
	 */
	public function maybe_sync_booking_from_status( $booking_id ) {
		global $wpdb;

		$status = $wpdb->get_var( $wpdb->prepare( "SELECT post_status FROM $wpdb->posts WHERE post_type = 'wc_booking' AND ID = %d", $booking_id ) );

		if ( 'cancelled' === $status ) {
			$this->remove_booking( $booking_id );
		} elseif ( in_array( $status, $this->get_booking_is_paid_statuses(), true ) ) {
			$this->sync_booking( $booking_id );
		}
	}

	/**
	 * Get booking's post statuses considered as paid.
	 *
	 * @return array
	 */
	private function get_booking_is_paid_statuses() {
		/**
		 * Use this filter to add custom booking statuses that should be considered paid.
		 *
		 * @since 1.14.1
		 *
		 * @param array  $statuses All booking statuses considered to be paid.
		 */
		return apply_filters( 'woocommerce_booking_is_paid_statuses', array( 'confirmed', 'paid', 'complete' ) );
	}

	/**
	 * Is edited from post.php's meta box.
	 *
	 * @return bool
	 */
	public function is_edited_from_meta_box() {
		return (
			! empty( $_POST['wc_bookings_details_meta_box_nonce'] )
			&&
			wp_verify_nonce( $_POST['wc_bookings_details_meta_box_nonce'], 'wc_bookings_details_meta_box' )
		);
	}

	/**
	 * Maybe delete Global Availability from Google.
	 *
	 * @param WC_Global_Availability $availability Availability to delete.
	 */
	public function delete_global_availability( WC_Global_Availability $availability ) {
		$this->maybe_init_service();

		if ( $availability->get_gcal_event_id() ) {
			try {
				$this->service->events->delete( $this->calendar_id, $availability->get_gcal_event_id() );
			} catch ( Exception $e ) {
				if ( 'yes' === $this->debug ) {
					$this->log->add( $this->id, 'Error while deleting event from Google: ' . $e->getMessage() );
				}
			}
		}
	}

	/**
	 * Sync Global Availability to Google.
	 *
	 * @param WC_Global_Availability $availability Global Availability object.
	 */
	public function sync_global_availability( WC_Global_Availability $availability ) {
		if ( ! $this->is_integration_active() ) {
			return;
		}

		if ( ! $availability->get_changes() ) {
			// nothing changed don't waste time syncing.
			return;
		}

		if ( $this->polling ) {
			// Event is coming from google don't send it back.
			return;
		}

		$this->maybe_init_service();

		if ( $availability->get_gcal_event_id() ) {
			$event     = $this->service->events->get( $this->calendar_id, $availability->get_gcal_event_id() );
			$supported = $this->update_event_from_global_availability( $event, $availability );
			if ( $supported ) {
				$this->service->events->update( $this->calendar_id, $event->getId(), $event );
			}
		}
	}

	/**
	 * Update global availability object with data from google event object.
	 *
	 * @param WC_Global_Availability        $availability WooCommerce Global Availability object.
	 * @param Google_Service_Calendar_Event $event Google calendar event object.
	 *
	 * @return bool
	 */
	private function update_global_availability_from_event( WC_Global_Availability $availability, Google_Service_Calendar_Event $event ) {
		$availability->set_gcal_event_id( $event->getId() )
			->set_title( $event->getSummary() )
			->set_bookable( 'no' )
			->set_priority( 10 )
			->set_ordering( 0 );

		// TODO: check timezones.
		if ( $event->getRecurrence() ) {
			$availability->set_range_type( 'rrule' );
			$availability->set_rrule( join( "\n", $event->getRecurrence() ) );
			if ( $event->getStart()->getDateTime() ) {
				$availability->set_from_range( $event->getStart()->getDateTime() );
				$availability->set_to_range( $event->getEnd()->getDateTime() );
			} else {
				$availability->set_from_range( $event->getStart()->getDate() );
				$availability->set_to_range( $event->getEnd()->getDate() );
			}
		} elseif ( $event->getStart()->getDateTime() ) {

			$start_date = new WC_DateTime( $event->getStart()->getDateTime() );
			$end_date   = new WC_DateTime( $event->getEnd()->getDateTime() );

			$availability->set_range_type( 'custom:daterange' )
				->set_from_date( $start_date->format( 'Y-m-d' ) )
				->set_to_date( $end_date->format( 'Y-m-d' ) )
				->set_from_range( $start_date->format( 'H:i' ) )
				->set_to_range( $end_date->format( 'H:i' ) );

		} else {

			$start_date = new WC_DateTime( $event->getStart()->getDate() );
			$end_date   = new WC_DateTime( $event->getEnd()->getDate() );

			try {
				// Our date ranges our inclusive, Google's are not.
				$end_date->sub( new DateInterval( 'P1D' ) );
			} catch ( Exception $e ) {
				if ( 'yes' === $this->debug ) {
					$this->log->add( $this->id, $e->getMessage() );
				}
				// Should never happen.
			}

			$availability->set_range_type( 'custom' )
				->set_from_range( $start_date->format( 'Y-m-d' ) )
				->set_to_range( $end_date->format( 'Y-m-d' ) );

		}
		return true;
	}

	/**
	 * Update google event object with data from global availability object.
	 *
	 * @param Google_Service_Calendar_Event $event Google calendar event object.
	 * @param WC_Global_Availability        $availability WooCommerce Global Availability object.
	 *
	 * @return bool
	 */
	private function update_event_from_global_availability( Google_Service_Calendar_Event $event, WC_Global_Availability $availability ) {
		$event->setSummary( $availability->get_title() );
		$timezone        = wc_booking_get_timezone_string();
		$start           = new Google_Service_Calendar_EventDateTime();
		$end             = new Google_Service_Calendar_EventDateTime();
		$start_date_time = new WC_DateTime();
		$end_date_time   = new WC_DateTime();

		switch ( $availability->get_range_type() ) {
			case 'custom:daterange':
				$start_date_time = new WC_DateTime( $availability->get_from_date() . ' ' . $availability->get_from_range() );
				$start->setDateTime( $start_date_time->format( 'Y-m-d\TH:i:s' ) );
				$start->setTimeZone( $timezone );
				$event->setStart( $start );

				$end_date_time = new WC_DateTime( $availability->get_to_date() . ' ' . $availability->get_to_range() );
				$end->setDateTime( $end_date_time->format( 'Y-m-d\TH:i:s' ) );
				$end->setTimeZone( $timezone );
				$event->setEnd( $end );
				break;
			case 'custom':
				$start_date_time = new WC_DateTime( $availability->get_from_range() );
				$start->setDate( $start_date_time->format( 'Y-m-d' ) );
				$event->setStart( $start );

				$end_date_time = new WC_DateTime( $availability->get_to_range() );
				$end_date_time->add( new DateInterval( 'P1D' ) );
				$end->setDate( $end_date_time->format( 'Y-m-d' ) );
				$event->setEnd( $end );
				break;
			case 'months':
				$start_date_time->setDate(
					date( 'Y' ),
					$availability->get_from_range(),
					1
				);

				$start->setDate( $start_date_time->format( 'Y-m-d' ) );
				$event->setStart( $start );

				$number_of_months = 1 + intval( $availability->get_to_range() ) - intval( $availability->get_from_range() );

				$end_date_time = $start_date_time->add( new DateInterval( 'P' . $number_of_months . 'M' ) );

				$end->setDate( $end_date_time->format( 'Y-m-d' ) );
				$event->setEnd( $end );

				$event->setRecurrence(
					array(
						'RRULE:FREQ=YEARLY',
					)
				);

				break;
			case 'weeks':
				$start_date_time->setDate(
					date( 'Y' ),
					1,
					1
				);

				$end_date_time->setDate(
					date( 'Y' ),
					1,
					2
				);

				$all_days     = join( ',', array_keys( \RRule\RRule::$week_days ) );
				$week_numbers = join( ',', range( $availability->get_from_range(), $availability->get_to_range() ) );
				$rrule        = "RRULE:FREQ=YEARLY;BYWEEKNO=$week_numbers;BYDAY=$all_days";

				$start->setDate( $start_date_time->format( 'Y-m-d' ) );
				$event->setStart( $start );

				$end->setDate( $end_date_time->format( 'Y-m-d' ) );
				$event->setEnd( $end );

				$event->setRecurrence(
					array(
						$rrule,
					)
				);
				break;
			case 'days':
				$start_day = intval( $availability->get_from_range() );
				$end_day   = intval( $availability->get_to_range() );

				$start_date_time->modify( 'this ' . self::DAYS_OF_WEEK[ $start_day ] );
				$start->setDate( $start_date_time->format( 'Y-m-d' ) );
				$event->setStart( $start );

				$end_date_time = $start_date_time->modify( 'this ' . self::DAYS_OF_WEEK[ $end_day ] );

				$end->setDate( $end_date_time->format( 'Y-m-d' ) );
				$event->setEnd( $end );

				$event->setRecurrence(
					array(
						'RRULE:FREQ=WEEKLY',
					)
				);

				break;
			case 'time:1':
			case 'time:2':
			case 'time:3':
			case 'time:4':
			case 'time:5':
			case 'time:6':
			case 'time:7':
				list( , $day_of_week ) = explode( ':', $availability->get_range_type() );

				$start_date_time->modify( 'this ' . self::DAYS_OF_WEEK[ $day_of_week ] );
				$end_date_time->modify( 'this ' . self::DAYS_OF_WEEK[ $day_of_week ] );
				$rrule = 'RRULE:FREQ=WEEKLY';

				// fall through please.
			case 'time':
				if ( ! isset( $rrule ) ) {
					$rrule = 'RRULE:FREQ=DAILY';
				}

				list( $start_hour, $start_min ) = explode( ':', $availability->get_from_range() );
				$start_date_time->setTime( $start_hour, $start_min );

				list( $end_hour, $end_min ) = explode( ':', $availability->get_to_range() );
				$end_date_time->setTime( $end_hour, $end_min );

				$start->setDateTime( $start_date_time->format( 'Y-m-d\TH:i:s' ) );
				$start->setTimeZone( $timezone );
				$event->setStart( $start );

				$end->setDateTime( $end_date_time->format( 'Y-m-d\TH:i:s' ) );
				$end->setTimeZone( $timezone );
				$event->setEnd( $end );

				$event->setRecurrence(
					array(
						$rrule,
					)
				);
				break;

			default:
				// That should be everything, anything else is not supported.
				return false;
		}
		return true;
	}
}
