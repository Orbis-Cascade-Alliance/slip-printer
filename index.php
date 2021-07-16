<?php
require_once('functions.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Print Slips</title>
    <link rel="stylesheet" href="style.css" />
    <style>
      .slip {
        width: calc(850px/<?php echo SLIPS_PER_PAGE; ?>);
      }
    </style>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!-- Local scripts -->
    <script src="script.js"></script>
  </head>
  <body>
    <div id="main">
      <h1>Print Slips</h1>
      <form id="slip-form" action="index.php" method="post">
        <div class="form-select">
          <label for="printer">Print Queue:</label>
          <select name="printer" id="printer">
          <?php
          $printers = get_printers();
          foreach ($printers as $printer_id => $printer_name) {
            echo '<option value="' . $printer_id . '">' . $printer_name . '</option>';
          }
          ?>
          </select>
        </div>
        <input type="button" class="dark-purple" value="Get Slips" onclick="get_slips();" />
      </form>
      <div id="loading"><img src="loading.gif" alt="Loading" /></div>
      <div id="slip-results"></div>
    </div>
    <div id="dialog-error" title="Error">An error occurred.</div>
    <div id="dialog-no-selections" title="Select Printouts">No printouts are selected.</div>
    <div id="dialog-mark-printed" title="Mark Printed">Mark the selected printout(s) as printed?</div>
    <div id="dialog-cancel" title="Cancel Confirmation">Cancel selected printout(s)?</div>
  </body>
</html>