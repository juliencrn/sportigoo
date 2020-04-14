<?php


    class WPH_conflict_handle_BuddyPress
        {
                        
            static function init()
                {
                    if( !   self::is_plugin_active())
                        return false;
                    
                    //add bufer filtering for sueprcache plugin
                    //trigger only on admin
                    if(is_admin())
                        {
                            add_filter('wp-hide/loaded_modules', array('WPH_conflict_handle_BuddyPress', 'loaded_modules'), 999);
                        }
                        
                    //adjust bp_core_avatar_url
                    add_filter('bp_core_avatar_url', array('WPH_conflict_handle_BuddyPress', 'bp_core_avatar_url'), 999);
                    
                }                        
            
            static function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'buddypress/bp-loader.php' ))
                        return true;
                        else
                        return false;
                }
            
            static public function budypress()
                {   
                    
                               
                }
                
            static function loaded_modules( $modules )
                {   
                    //iterate all modules and seek for "remove_other_generator_meta"
                    foreach($modules    as  $block_key =>  $block)
                        {
                            foreach($block->components as  $module_key  =>  $module)    
                                {
                                    foreach($module->module_settings    as  $component_key =>  $component)
                                        {
                                            
                                            if(!isset($component['id']))
                                                continue;
                                            
                                            /**
                                            if($component['id']   ==  'styles_remove_id_attribute')
                                                {
                                                    $modules[$block_key]->components[$module_key]->module_settings[$component_key]['description']   .=  '<div class="notice-error"><div class="dashicons dashicons-warning important" alt="f534">warning</div> <span class="important">' . __('This setting produce a conflict with BuddyPress and should be kept disabled.',    'wp-hide-security-enhancer') . '</span></div>';
                                                }
                                            */
                       
                                        }
                                }
                        }
                    
                    return $modules;
                               
                }
                
            static function bp_core_avatar_url( $url )
                {
                    global $wph;
                    
                    //retrieve the replacements list
                    $replacement_list   =   $wph->functions->get_replacement_list();
                    
                    //do replacements for this url
                    $url    =   $wph->functions->content_urls_replacement($url,  $wph->functions->get_replacement_list() );                    
                
                    return $url;
                    
                }    
                 
            
                            
        }


?>