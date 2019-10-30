<form method="post">
  <table class="widefat">
    <thead>
      <tr>
        <th colspan="2">
          <?= __('List of supported fields types:', 'acf-better-search'); ?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?php $this->showFieldsList(); ?>
    </tbody>
  </table>
  <table class="widefat">
    <thead>
      <tr>
        <th colspan="2">
          <?= __('Additional features:', 'acf-better-search'); ?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?php $this->showFeaturesList(); ?>
    </tbody>
  </table>
  <input type="submit" class="button button-primary" name="acfbs_save" value="<?= __('Save Changes', 'acf-better-search'); ?>">
</form>