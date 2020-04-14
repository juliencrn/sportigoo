<?php

/**
* Compatibility for Plugin Name: Elementor
* Compatibility checked on Version: 2.5.16
*/

    use Elementor\Core\Files\Manager as Files_Manager;
    
    class WPH_conflict_elementor
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active())
                        return FALSE;
                    
                    global $wph;
                    
                    add_action( 'wph/settings_changed',         array( 'WPH_conflict_elementor',    'settings_changed') );

                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'elementor/elementor.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
                
                
            static function settings_changed()
                {
                    
                    $files_manager = new Files_Manager();
                    $files_manager->clear_cache();
                    
                }
                
                
                
      
                            
        }


?>