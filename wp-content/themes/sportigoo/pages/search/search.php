<?php
$data_inputs = [
  "what" => [
    "id" => "what",
    "class" => "custom-select button2 button2--noradius-right",
    "label" => "Quoi ?",
    "data" => get_terms( array(
      'hide_empty' => false,
      'taxonomy' => 'product_cat',
      'child_of' => 58 // product_cat > type > x
    ) ),
    "lieu" => false
  ],
  "who" => [
    "id" => "who",
    "class" => "custom-select button2 button2--noradius",
    "label" => "Pour qui ?",
    "data" => get_terms( array(
      'hide_empty' => false,
      'taxonomy' => 'product_cat',
      'child_of' => 59 // product_cat > qui > x
    ) ),
    "lieu" => false
  ],
  "where" => [
    "id" => "where",
    "class" => "custom-select button2 button2--noradius-left",
    "label" => "Où ?",
    "data" => get_terms( array(
      'hide_empty' => false,
      'taxonomy' => 'lieu'
    ) ),
    "lieu" => true
  ],
];

function zz_search_select2($input) {
  $id = $input['id'];
  $classes = $input['class'];
  ?>
  <a
    id="<?php echo $id; ?>-group"
    onclick="this.className = '<?php echo $classes; ?>'"
    class="<?php echo $classes; ?>"
  >
    <select name="<?php echo $id; ?>">
      <option value="0"><?php echo $input['label']; ?></option>

      <?php
      foreach ($input['data'] as $term) {
        if ($input['lieu']) {
          $dept = get_field('numero', $term); ?>
          <option data-dept="<?php echo $dept ?>" value="<?php echo  $term->term_id ?>">
            <?php echo $term->name ?>
          </option>
          <?php
        } else { ?>
          <option value="<?php echo $term->term_id ?>"><?php echo $term->name ?></option>
        <?php }
      }
      ?>

    </select>
  </a>
<?php }
?>

<section id="js-search__buttons" class="search__buttons">
  <div class="container">
    <div class="wrapper activities">
      <?php zz_search_select2($data_inputs['what']); ?>
      <?php zz_search_select2($data_inputs['who']); ?>
      <?php zz_search_select2($data_inputs['where']); ?>
    </div>
  </div>
</section>
