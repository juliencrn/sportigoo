<?php
    
    /**
    * 
    * General compatibility class to be used for groups of plugins
    * 
    */
    

    class WPH_conflict_handle_General
        {
                        
            static function init()
                {
                    if( self::is_plugin_active( 'wp-job-manager/wp-job-manager.php'))
                        {
                            //adjust the uplod_data
                            add_filter('upload_dir',            array('WPH_conflict_handle_General', 'upload_dir' ), 999);   
                        }
                }                        
            
            static function is_plugin_active( $plugin_path )
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( $plugin_path ) ||  is_plugin_active_for_network( $plugin_path ))
                        return TRUE;
                        else
                        return FALSE;
                }
            
            /**
            * Process the upload_dir data
            * 
            * @param mixed $data
            */
            static function upload_dir( $data )
                {
                    
                    if (  ! self::check_backtrace_for_caller('create_attachment', 'WP_Job_Manager_Form_Submit_Job'))
                        return $data;
                    
                    global $wph;

                    $new_upload_path        =   $wph->functions->untrailingslashit_all(    $wph->functions->get_module_item_setting('new_upload_path')  );
                    $new_content_path       =   $wph->functions->untrailingslashit_all(    $wph->functions->get_module_item_setting('new_content_path')  );
                    
                    if  ( empty ( $new_upload_path )    &&  empty ( $new_content_path ) )
                        return $data; 
                    
                    if  (  ! empty ( $new_upload_path ) )
                        {
                            $new_url                =   trailingslashit(    home_url()  )   . $new_upload_path;
                            
                            if ( is_multisite() && ! ( is_main_network() && is_main_site() && defined( 'MULTISITE' ) ) )
                                {
                                    $ms_dir = '/sites/' . get_current_blog_id();
                                    $new_url    .=  $ms_dir;
                                }   
                        }
                        else
                        {
                            $new_url                =   trailingslashit(    home_url()  )   . str_replace( '/wp-content' , $new_content_path, $wph->default_variables['uploads_directory'] );
                        }
                    
                    $data['url']            =   str_replace($data['baseurl'], $new_url, $data['url']);
                    $data['baseurl']        =   $new_url;
                    
                    return $data;   
                }
                
                
            static function check_backtrace_for_caller( $function_name, $class_name = FALSE )
                {
                    
                    $backtrace  =   debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                    foreach ( $backtrace as  $block )
                        {
                            if ( $block['function']    ==  $function_name )
                                {
                                    if ( $class_name    ===  FALSE )
                                        return TRUE;
                                    
                                    if ( $class_name    !=  FALSE   &&  !isset( $block['class'] ) )
                                        return FALSE;
                                        
                                    if ( $block['class']    ==  $class_name )
                                        return TRUE;
                                    
                                    return FALSE;
                                    
                                }
                        
                        }
                        
                    return FALSE;
                }
            
                            
        }
        
        
    WPH_conflict_handle_General::init();


?>