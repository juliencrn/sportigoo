<?php

/**
* Compatibility for Plugin Name: Shield Security
* Compatibility checked on Version: 6.10.9
*/

    class WPH_conflict_handle_wp_simple_firewall
        {
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'wp-simple-firewall/icwp-wpsf.php' ))
                        return TRUE;
                        else
                        return FALSE;
    
                }
            
            static public function custom_login_check()
                {   
                    global $wph;
                    
                    if( !   self::is_plugin_active()    || defined('WPH_conflict_handle_wp_simple_firewall') )
                        return FALSE;
                        
                    //mark as being loaded
                    define('WPH_conflict_handle_wp_simple_firewall', TRUE );
                    
                    add_action('plugins_loaded',    array( 'WPH_conflict_handle_wp_simple_firewall', 'on_plugins_loaded' ), 5);
                    
                }
                
            
            static public function on_plugins_loaded()
                {
                    
                       
                    $oICWP_Wpsf_Controller = ICWP_WPSF_Plugin_Controller::GetInstance( WP_PLUGIN_DIR . '/wp-simple-firewall/icwp-wpsf.php' );
                    
                    //check if custom login is active
                    if( !   $oICWP_Wpsf_Controller->oFeatureHandlerLoginProtect->isCustomLoginPathEnabled())
                        return FALSE;
                    
                    global $wph;
                    
                    //attempt to disable other plugin code
                    add_action( 'init', array( 'WPH_conflict_handle_wp_simple_firewall', '_on_filter_init' ), -1 );   
                    
                }
                
                
            static function _on_filter_init()
                {
                    global $wph;
      
                    if($wph->functions->anonymous_object_filter_exists('init', 'ICWP_WPSF_Processor_LoginProtect_WpLogin', 'doBlockPossibleWpLoginLoad'))
                        {
                            //ready to process
                            $wph->functions->remove_anonymous_object_filter('init',             'ICWP_WPSF_Processor_LoginProtect_WpLogin', 'doBlockPossibleWpLoginLoad');
                            $wph->functions->remove_anonymous_object_filter('wp_loaded',        'ICWP_WPSF_Processor_LoginProtect_WpLogin', 'aLoadWpLogin');
                            $wph->functions->remove_anonymous_object_filter('login_init',       'ICWP_WPSF_Processor_LoginProtect_WpLogin', 'aLoginFormAction');
                            $wph->functions->remove_anonymous_object_filter('site_url',         'ICWP_WPSF_Processor_LoginProtect_WpLogin', 'fCheckForLoginPhp');
                            $wph->functions->remove_anonymous_object_filter('network_site_url', 'ICWP_WPSF_Processor_LoginProtect_WpLogin', 'fCheckForLoginPhp');
                            $wph->functions->remove_anonymous_object_filter('wp_redirect',      'ICWP_WPSF_Processor_LoginProtect_WpLogin', 'fCheckForLoginPhp');
                            $wph->functions->remove_anonymous_object_filter('wp_redirect',      'ICWP_WPSF_Processor_LoginProtect_WpLogin', 'fProtectUnauthorizedLoginRedirect');
                            $wph->functions->remove_anonymous_object_filter('et_anticipate_exceptions',      'ICWP_WPSF_Processor_LoginProtect_WpLogin', 'fAddToEtMaintenanceExceptions');
                            
                            //add an admin notice to inform about the conflict
                            add_action('admin_notices',                                         array( 'WPH_conflict_handle_wp_simple_firewall', 'admin_notice' ));
                            add_action( 'wp_ajax_wph_notice_ignore_wp_simple_firewall',         array( 'WPH_conflict_handle_wp_simple_firewall','ajax_calls' ));
                            add_action( 'admin_print_scripts',                                  array( 'WPH_conflict_handle_wp_simple_firewall', 'admin_print_scripts' ) );
                        }
                        
                }
                
                
            static function admin_notice()
                {
                    global $current_user ;
                    
                    $user_id = $current_user->ID;
                    
                    //only for admins
                    if (    !   current_user_can( 'install_plugins' ) )
                        return;
                        
                    $WPH_notice_wp_simple_firewall__login   =  get_user_meta($user_id, 'wph_hide_notice_wp_simple_firewall__login');
                    
                    if ( empty($WPH_notice_wp_simple_firewall__login )) 
                        {
                            echo '<div id="WPH_conflict_handle_wp_simple_firewall_login" class="error notice is-dismissible"><p>'; 

                            wp_nonce_field( 'WPH_conflict_handle_wp_simple_firewall_login-error-nottice-disable', 'WPH_conflict_handle_wp_simple_firewall_login_nonce' );
                            
                            printf('<button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button>', '?wph_conflicts_report_wp_simple_firewall=0');
                            _e('<b>Conflict notice</b>: The Security Firewall - Login Protection use the Rename WP Login Page functionality which is the same as WP Hide - Admin Login Url change.  ', 'wp-hide-security-enhancer');
                            echo "</p></div>";
                        }    
                    
                }
                
            static function ajax_calls()
                {
                    global $current_user;
                    $user_id = $current_user->ID;

                    $nonce  =   isset($_POST['_wpnonce'])   ?   $_POST['_wpnonce']                      :   '';
                    $type   =   isset($_POST['type'])       ?   sanitize_text_field($_POST['type'])     :   '';
                    
                    if ( ! wp_verify_nonce( $nonce, 'WPH_conflict_handle_wp_simple_firewall_login-error-nottice-disable' ) )
                        die();
                        
                    //only for admins
                    If ( !  current_user_can ( 'manage_options' ) )
                        return FALSE;
                    
                    switch($type)
                        {
                            case 'login':
                                            update_user_meta($user_id, 'wph_hide_notice_wp_simple_firewall__login', 'true');
                                            
                                            break;
                        
                        }    
                    
                }
                
            static function admin_print_scripts()
                {
                    wp_enqueue_script( 'WPH_conflict_handle_wp_simple_firewall', WPH_URL . '/compatibility/js/wp_simple_firewall.js', array( 'jquery' ), '1.0', true );   
                }
                
        }



?>