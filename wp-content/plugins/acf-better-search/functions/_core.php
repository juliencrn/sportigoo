<?php

  class ACFBetterSearch {

    function __construct() {

      $this->loadSearchCore();
      $this->loadAdminCore();

    }

    /* ---
      Load cores
    --- */

      private function loadSearchCore() {

        $isAjax      = (defined('DOING_AJAX') && DOING_AJAX);
        $isMediaAjax = (isset($_POST['action']) && in_array($_POST['action'], ['query-attachments']));

        if ($isAjax && $isMediaAjax)
          return;

        $this->loadClass('Search');

      }

      private function loadAdminCore() {

        $this->loadClass('Assets');
        $this->loadClass('Notice');

        if (!is_network_admin())
          $this->loadClass('Settings');

      }

      private function loadClass($class) {

        $var   = strtolower($class);
        $class = $class . '_ACFBetterSearch';

        require_once ACFBS_PATH . 'functions/' . $var . '.php';

        $this->$var = new $class();

      }

  }