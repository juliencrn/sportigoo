<?php

  class Search_ACFBetterSearch {

    function __construct() {

      $this->loadSettings();

      add_filter('pre_get_posts', [$this, 'queryArgs']); 
      add_filter('posts_search',  [$this, 'sqlWhere'],    10, 2); 
      add_filter('posts_join',    [$this, 'sqlJoin'],     10, 2);
      add_filter('posts_request', [$this, 'sqlDistinct'], 10, 2); 

    }

    /* ---
      Settings
    --- */

      private function loadSettings() {

        global $wpdb;

        $this->wpdb         = $wpdb;
        $this->fieldsTypes  = get_option('acfbs_fields_types',  ['text', 'textarea', 'wysiwyg']);
        $this->wholePhrases = get_option('acfbs_whole_phrases', false) ? true : false;
        $this->liteMode     = get_option('acfbs_lite_mode',     false) ? true : false;

      }

    /* ---
      Pre get posts
    --- */

      public function queryArgs($query) {

        if (!isset($query->query_vars['s']))
          return $query;

        $query->query_vars['suppress_filters'] = false;
        return $query;

      }

    /* ---
      Where clause
    --- */

      public function sqlWhere($where, $query) {

        if (!isset($query->query_vars['s']) || empty($query->query_vars['s']))
          return $where;

        $list   = [];
        $list[] = $this->getACFConditions($query->query_vars['s']);
        $list[] = $this->getDefaultWordPressConditions($query->query_vars['s']);

        if (in_array('file', $this->fieldsTypes))
          $list[] = $this->getFileConditions($query->query_vars['s']);

        $where = 'AND (' . implode(' OR ', $list) . ')';
        return $where;

      }

      private function getACFConditions($words) {

        if (!$this->fieldsTypes && !$this->liteMode)
          return '(1 = 2)';

        $words = !$this->wholePhrases ? explode(' ', $words) : [$words];
        $list  = [];

        foreach ($words as $word) {

          $word   = addslashes($word);
          $list[] = 'a.meta_value LIKE \'%' . $word . '%\'';

        }

        $list = '(' . implode(') AND (', $list) . ')';

        if (!$this->liteMode)
          $list = '((c.post_name = b.meta_value) AND ' . $list . ')';
        else
          $list = '((b.meta_value LIKE \'field_%\') AND ' . $list . ')';

        return $list;

      }

      private function getDefaultWordPressConditions($words) {

        $words   = !$this->wholePhrases ? explode(' ', $words) : [$words];
        $columns = ['post_title', 'post_content', 'post_excerpt'];
        $list    = [];

        foreach ($words as $word) {

          $word       = addslashes($word);
          $conditions = [];

          foreach ($columns as $column) {

            $conditions[] = sprintf(
              '(%s.%s LIKE %s)',
              $this->wpdb->posts,
              $column,
              '\'%' . $word . '%\''
            );

          }

          $list[] = '(' . implode(' OR ', $conditions) . ')';

        }

        if (count($list) > 1)
          $list = '(' . implode(' AND ', $list) . ')';
        else
          $list = $list[0];

        return $list;

      }

      private function getFileConditions($words) {

        $words = !$this->wholePhrases ? explode(' ', $words) : [$words];
        $list  = [];

        foreach ($words as $word) {

          $word   = addslashes($word);
          $list[] = 'd.post_title LIKE \'%' . $word . '%\'';

        }

        $list = '(' . implode(') AND (', $list) . ')';
        return $list;

      }

    /* ---
      Join clause
    --- */

      public function sqlJoin($join, $query) {

        if (empty($query->query_vars['s']) || (!$this->fieldsTypes && !$this->liteMode))
          return $join;

        $parts = [];

        $parts[] = sprintf(
          'INNER JOIN %s AS a ON (a.post_id = %s.ID)',
          $this->wpdb->postmeta,
          $this->wpdb->posts
        );
        $parts[] = sprintf(
          'INNER JOIN %s AS b ON ((b.meta_id = a.meta_id + @@auto_increment_increment) AND (b.post_id = %s.ID))',
          $this->wpdb->postmeta,
          $this->wpdb->posts
        );

        if (!$this->liteMode || in_array('file', $this->fieldsTypes)) {

          $parts[] = sprintf(
            'INNER JOIN %s AS c ON %s',
            $this->wpdb->posts,
            $this->getFieldsTypesConditions()
          );

        }

        if (in_array('file', $this->fieldsTypes)) {

          $parts[] = sprintf(
            'LEFT JOIN %s AS d ON (d.ID = a.meta_value)',
            $this->wpdb->posts,
            '\'%:"file"%\''
          );

        }

        $join .= ' ' . implode(' ', $parts);
        return $join;

      }

      private function getFieldsTypesConditions() {

        $types = [];
        $list  = [];

        foreach ($this->fieldsTypes as $type)
          $types[] = '(c.post_content LIKE \'%:"' . $type . '"%\')';

        $list[] = '(c.post_type = \'acf-field\')';

        if (count($types) > 1)
          $list[] = '(' . implode(' OR ', $types) . ')';
        else
          $list[] = $types[0];
        
        $list = '(' . implode(' AND ', $list) . ')';
        return $list;

      }

    /* ---
      Filter SQL query
    --- */

      public function sqlDistinct($sql, $query) {

        if (empty($query->query_vars['s']))
          return $sql;

        $sql = preg_replace('/SELECT/', 'SELECT DISTINCT', $sql, 1);
        return $sql;

      }

  }