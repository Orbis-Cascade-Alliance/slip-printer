<?php
require_once('functions.php');

// Get mark type
if (isset($_POST['type']) && ($_POST['type'] == 'canceled' || $_POST['type'] == 'printed')) {
  $type = $_POST['type'];
}

// Get printout IDs
if (isset($_POST['ids']) && !empty($_POST['ids'])) {
  $ids = $_POST['ids'];
  $post_ids = array();
  foreach ($ids as $raw_id) {
    $post_ids[] = filter_var($raw_id, FILTER_SANITIZE_NUMBER_INT);
  }
  $sanitized_string = implode(',', $post_ids);
  
  // Update status of printouts
  $url = API_SERVER . '/almaws/v1/task-lists/printouts?printout_id=' . $sanitized_string . '&op=mark_as_' . $type . '&apikey=' . API_KEY;
  $result = get_result($url, 'post');
  $modified_record_count = (int) $result['total_record_count'];
  if ($modified_record_count == count($post_ids)) {
    echo 'success';
  }
  else {
    echo 'error';
  }
}
?>