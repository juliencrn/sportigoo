<?php 

  /*
    Plugin Name: ACF: Better Search
    Description: Adds to default WordPress search engine the ability to search by content from selected fields of Advanced Custom Fields plugin.
    Version: 3.1.3
    Author: Mateusz Gbiorczyk
    Author URI: https://gbiorczyk.pl/
    Text Domain: acf-better-search
  */

  define('ACFBS_VERSION', '3.1.3');
  define('ACFBS_PATH',    plugin_dir_path(__FILE__));
  define('ACFBS_HTTP',    plugin_dir_url(__FILE__));
  define('ACFBS_NOTICE',  'acfbs_notice_hidden');

  include ACFBS_PATH . 'functions/_core.php';
  new ACFBetterSearch();