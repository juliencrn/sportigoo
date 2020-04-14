<?php


    class WPH_conflict_handle_wp_rocket
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active() ||  ! self::is_cache_processing() )
                        return FALSE;
                    
                    add_filter( 'wp-hide/ignore_ob_start_callback', array( 'WPH_conflict_handle_wp_rocket', 'ignore_ob_start_callback'), 999, 2);
                    add_filter( 'rocket_buffer',                    array( 'WPH_conflict_handle_wp_rocket', 'rocket_buffer'), 999 );
                    
                    add_filter( 'rocket_js_url',                    array( 'WPH_conflict_handle_wp_rocket', 'rocket_js_url'), 999 );
                    
                    /**
                    * This filter will be introduced at a later verion on WP Rocket plugin ( >= 3.4 )..
                    */
                    add_filter( 'rocket_css_content',                array( 'WPH_conflict_handle_wp_rocket', 'rocket_css_content'), 999 );
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'wp-rocket/wp-rocket.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
                
            static function is_cache_processing()
                {
                    // Don't cache robots.txt && .htaccess directory (it's happened sometimes with weird server configuration)
                    if ( strstr( $_SERVER['REQUEST_URI'], 'robots.txt' ) || strstr( $_SERVER['REQUEST_URI'], '.htaccess' ) ) {
                        return FALSE;
                    }

                    $request_uri = explode( '?', $_SERVER['REQUEST_URI'] );
                    $request_uri = reset(( $request_uri ));

                    // Don't cache disallowed extensions
                    if ( strtolower( $_SERVER['REQUEST_URI'] ) != '/index.php' && in_array( pathinfo( $request_uri, PATHINFO_EXTENSION ), array( 'php', 'xml', 'xsl' ) ) ) {
                        return FALSE;
                    }

                    // Don't cache if user is in admin
                    if ( is_admin() ) {
                        return FALSE;
                    }

                    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
                        return FALSE;
                    }

                    // Don't cache the customizer preview
                    if ( isset( $_POST['wp_customize'] ) ) {
                        return FALSE;
                    }

                    // Don't cache without GET method
                    if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || $_SERVER['REQUEST_METHOD'] != 'GET' ) {
                         return FALSE;
                    }

                    // Get the correct config file
                    $rocket_config_path = WP_CONTENT_DIR . '/wp-rocket-config/';
                    $host = ( isset( $_SERVER['HTTP_HOST'] ) ) ? $_SERVER['HTTP_HOST'] : time();
                    $host = trim( strtolower( $host ), '.' );
                    $host = str_replace( array( '..', chr(0) ), '', $host );

                    $continue = false;
                    if ( file_exists( $rocket_config_path . $host . '.php' ) ) {
                        include( $rocket_config_path . $host . '.php' );
                        $continue = true;
                    } else {
                        $path = explode( '/' , trim( $_SERVER['REQUEST_URI'], '/' ) );

                        foreach ( $path as $p ) {
                            static $dir;

                            if ( file_exists( $rocket_config_path . $host . '.' . $p . '.php' ) ) {
                                include( $rocket_config_path . $host . '.' . $p .'.php' );
                                $continue = true;
                                break;
                            }

                            if( file_exists( $rocket_config_path . $host . '.' . $dir . $p . '.php' ) ) {
                                include( $rocket_config_path . $host . '.' . $dir. $p . '.php' );
                                $continue = true;
                                break;
                            }

                            $dir .= $p . '.';
                        }
                    }

                    // Exit if no config file is exist
                    if ( ! $continue ) {
                        return FALSE;
                    }

                    $request_uri = ( isset( $rocket_cache_query_strings ) && array_intersect( array_keys( $_GET ), $rocket_cache_query_strings ) ) || isset( $_GET['lp-variation-id'] ) || isset( $_GET['lang'] ) || isset( $_GET['s'] ) ? $_SERVER['REQUEST_URI'] : $request_uri;

                    // Don't cache with variables
                    // but the cache is enabled if the visitor comes from an RSS feed, an Facebook action or Google Adsence tracking
                    // @since 2.3     Add query strings which can be cached via the options page.
                    // @since 2.1     Add compatibilty with WordPress Landing Pages (permalink_name and lp-variation-id)
                    // @since 2.1     Add compabitiliy with qTranslate and translation plugin with query string "lang"
                    if ( ! empty( $_GET )
                        && ( ! isset( $_GET['utm_source'], $_GET['utm_medium'], $_GET['utm_campaign'] ) )
                        && ( ! isset( $_GET['utm_expid'] ) )
                        && ( ! isset( $_GET['fb_action_ids'], $_GET['fb_action_types'], $_GET['fb_source'] ) )
                        && ( ! isset( $_GET['gclid'] ) )
                        && ( ! isset( $_GET['permalink_name'] ) )
                        && ( ! isset( $_GET['lp-variation-id'] ) )
                        && ( ! isset( $_GET['lang'] ) )
                        && ( ! isset( $_GET['s'] ) )
                        && ( ! isset( $_GET['age-verified'] ) )
                        && ( ! isset( $rocket_cache_query_strings ) || ! array_intersect( array_keys( $_GET ), $rocket_cache_query_strings ) )
                    ) {
                        return FALSE;
                    }

                    // Don't cache SSL
                    if ( ! isset( $rocket_cache_ssl ) && rocket_is_ssl() ) {
                        return FALSE;
                    }

                    // Don't cache these pages
                    if ( isset( $rocket_cache_reject_uri ) && preg_match( '#^(' . $rocket_cache_reject_uri . ')$#', $request_uri ) ) {
                        return FALSE;
                    }

                    // Don't cache page with these cookies
                    if ( isset( $rocket_cache_reject_cookies ) && preg_match( '#(' . $rocket_cache_reject_cookies . ')#', var_export( $_COOKIE, true ) ) ) {
                        return FALSE;
                    }

                    $ip    = self::get_ip();
                    $allowed_ips = array(
                        '85.17.131.209'  => 0, // Pingdom Tools - Amsterdam
                        '173.208.58.138' => 1, // Pingdom Tools - New-York
                        '50.22.90.226'   => 2, // Pingdom Tools - Dallas
                        '209.58.131.213' => 3, // Pingdom Tools - San Jose
                        '168.1.92.52'    => 4, // Pingdom Tools - Melbourne
                        '5.178.78.78'    => 5  // Pingdom Tools - Stockholm
                    );

                    // Don't cache page when these cookies don't exist
                    if ( ! isset( $allowed_ips[ $ip ] ) && isset( $rocket_cache_mandatory_cookies ) && ! preg_match( '#(' . $rocket_cache_mandatory_cookies . ')#', var_export( $_COOKIE, true ) ) ) {
                        return FALSE;
                    }

                    // Don't cache page with these user agents
                    if ( isset( $rocket_cache_reject_ua, $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '#(' . $rocket_cache_reject_ua . ')#', $_SERVER['HTTP_USER_AGENT'] ) ) {
                        return FALSE;
                    }

                    // Don't cache if mobile detection is activated
                    if ( ! isset( $rocket_cache_mobile ) && isset( $_SERVER['HTTP_USER_AGENT'] ) && (preg_match('#^.*(2.0\ MMP|240x320|400X240|AvantGo|BlackBerry|Blazer|Cellphone|Danger|DoCoMo|Elaine/3.0|EudoraWeb|Googlebot-Mobile|hiptop|IEMobile|KYOCERA/WX310K|LG/U990|MIDP-2.|MMEF20|MOT-V|NetFront|Newt|Nintendo\ Wii|Nitro|Nokia|Opera\ Mini|Palm|PlayStation\ Portable|portalmmm|Proxinet|ProxiNet|SHARP-TQ-GX10|SHG-i900|Small|SonyEricsson|Symbian\ OS|SymbianOS|TS21i-10|UP.Browser|UP.Link|webOS|Windows\ CE|WinWAP|YahooSeeker/M1A1-R2D2|iPhone|iPod|Android|BlackBerry9530|LG-TU915\ Obigo|LGE\ VX|webOS|Nokia5800).*#i', $_SERVER['HTTP_USER_AGENT']) || preg_match('#^(w3c\ |w3c-|acs-|alav|alca|amoi|audi|avan|benq|bird|blac|blaz|brew|cell|cldc|cmd-|dang|doco|eric|hipt|htc_|inno|ipaq|ipod|jigs|kddi|keji|leno|lg-c|lg-d|lg-g|lge-|lg/u|maui|maxo|midp|mits|mmef|mobi|mot-|moto|mwbp|nec-|newt|noki|palm|pana|pant|phil|play|port|prox|qwap|sage|sams|sany|sch-|sec-|send|seri|sgh-|shar|sie-|siem|smal|smar|sony|sph-|symb|t-mo|teli|tim-|tosh|tsm-|upg1|upsi|vk-v|voda|wap-|wapa|wapi|wapp|wapr|webc|winw|winw|xda\ |xda-).*#i', substr($_SERVER['HTTP_USER_AGENT'], 0, 4))) ) {
                        return FALSE;
                    }    
                    
                    return TRUE;
                       
                }
                
                
            static function ignore_ob_start_callback( $do_ignore, $buffer )
                {
                    
                /**
                * Allow to cache search results
                *
                * @since 2.3.8
                *
                * @param bool true will force caching search results
                */
                $rocket_cache_search = apply_filters( 'rocket_cache_search', false );
                
                /**
                * Allow to override the DONOTCACHEPAGE behavior.
                * To warn conflict with some plugins like Thrive Leads.
                *
                * @since 2.5
                *
                * @param bool true will force the override
                */
                $rocket_override_donotcachepage = apply_filters( 'rocket_override_donotcachepage', false );
                    
                    if ( strlen( $buffer ) > 255
                        && ( function_exists( 'is_404' ) && ! is_404() ) // Don't cache 404
                        && ( function_exists( 'is_search' ) && ! is_search() || $rocket_cache_search ) // Don't cache search results
                        && ( ! defined( 'DONOTCACHEPAGE' ) || ! DONOTCACHEPAGE || $rocket_override_donotcachepage ) // Don't cache template that use this constant
                        && function_exists( 'rocket_mkdir_p' )
                    )  
                    return TRUE;
                    
                    return FALSE;
                }
                
            static function rocket_buffer( $buffer )
                {
                    
                    //remove the filter to allow the buffer processing
                    remove_filter( 'wp-hide/ignore_ob_start_callback', array( 'WPH_conflict_handle_wp_rocket', 'ignore_ob_start_callback'), 999);
                        
                    global $wph;
                    
                    $buffer =   $wph->ob_start_callback( $buffer );
                    
                    return $buffer;
                    
                }
                
                
            static function get_ip() 
                {
                    $keys = array(
                        'HTTP_CF_CONNECTING_IP', // CF = CloudFlare.
                        'HTTP_CLIENT_IP',
                        'HTTP_X_FORWARDED_FOR',
                        'HTTP_X_FORWARDED',
                        'HTTP_X_CLUSTER_CLIENT_IP',
                        'HTTP_X_REAL_IP',
                        'HTTP_FORWARDED_FOR',
                        'HTTP_FORWARDED',
                        'REMOTE_ADDR',
                    );

                    foreach ( $keys as $key ) {
                        if ( array_key_exists( $key, $_SERVER ) ) {
                            $ip = explode( ',', $_SERVER[ $key ] );
                            $ip = end( $ip );

                            if ( false !== filter_var( $ip, FILTER_VALIDATE_IP ) ) {
                                return $ip;
                            }
                        }
                    }

                    return '0.0.0.0';
                }
            
            
            /**
            * Replace static inline cached file urls
            *     
            * @param mixed $url
            */
            static function rocket_js_url( $url )
                {
                    global $wph;
                    
                    //retrieve the replacements list
                    $replacement_list   =   $wph->functions->get_replacement_list();
                                            
                    //replace the urls
                    $url =   $wph->functions->content_urls_replacement($url,  $replacement_list );   
                    
                    return $url ;   
                }
            
            
            
            static public function rocket_css_content( $content )
                {
                    global $wph;
                    
                    //retrieve the replacements list
                    $replacement_list   =   $wph->functions->get_replacement_list();
                                            
                    //replace the urls
                    $content =   $wph->functions->content_urls_replacement( $content,  $replacement_list );   
                    
                    return $content;   
                }
                            
        }


?>