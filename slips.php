<?php
require_once('functions.php');

// Get posted data
$submitted_printer = null;
if (isset($_POST) && !empty($_POST)) {
  if (isset($_POST['printer']) && !empty($_POST['printer'])) {
    $submitted_printer = filter_var($_POST['printer'], FILTER_SANITIZE_STRING);
  }
}

$slips_url = API_SERVER . '/almaws/v1/task-lists/printouts?printer_id=' . $submitted_printer . '&status=Pending&limit=' . SLIP_LIMIT . '&apikey=' . API_KEY;
$slips_xml = get_result($slips_url);
$slips = array();
$slip_count = (int) $slips_xml['total_record_count'];
if ($slip_count > 0) {
  
  // Place IDs and letters in slips array
  foreach ($slips_xml->printout as $printout) {
    $id = (string) $printout->id;
    $letter = (string) $printout->letter;
    $slips[$id] = $letter;
  }
  
  // Display slip count
  echo '<p id="slip-count">' . $slip_count . ' pending slip';
  if ($slip_count > 1) {
    echo 's.'; 
  }
  if ($slip_count > SLIP_LIMIT) {
    echo ' First ' . SLIP_LIMIT . ' displayed.';
  }
  echo '</p>';
  
  ?>
  <form id="print-form" action="index.php" method="post">
    <input type="hidden" name="columns" id="columns" value="<?php echo SLIPS_PER_PAGE;?>" />
    <div id="slip-actions">
      <p>
        <input type="button" value="Select All" onclick="select_all();" />
        <input type="button" value="Deselect All" onclick="deselect_all();" />
      </p>
      <p>
        <label for="action">With Selected:</label>
        <select name="action" id="action">
          <option value="print">Print</option>
          <option value="cancel">Cancel</option>
        </select>
        <input type="button" class="dark-purple" value="Go" onclick="do_action();" />
      </p>
    </div>
    
    <div id="slips">
      <?php
      foreach ($slips as $slip_id => $slip_letter) {
        echo '<div class="slip">';
        echo '<input type="checkbox" value="' . $slip_id . '" name="slip_id" checked="checked" />';
        echo '<div class="slip-html">' . $slip_letter . '</div>';
        echo '</div>';
      }
      ?>
    </div><!-- end slips -->
  </form>
<?php
}
else {
  echo '<p id="slip-count">No pending slips in this queue</p>';
}
?>