<?php
define('API_SERVER', 'https://api-na.hosted.exlibrisgroup.com'); // Domain of the Ex Libris API server
define('API_KEY', ''); // API key with Configuration (read-only) and Task-Lists (Read/write) permissions
define('SLIPS_PER_PAGE', 4); // The number of columns to print on a page by default
define('SLIP_LIMIT', 100); // The number of slips to fetch in one call, maximum 100

// GET or POST to API
function get_result($url, $type="get") {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  if ($type == 'post') {
    curl_setopt($ch, CURLOPT_POST, 1);
  }
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);
  return simplexml_load_string($output);
}

// Get printers
function get_printers() {
  $printers_url = API_SERVER . '/almaws/v1/conf/printers?printout_queue=true&apikey=' . API_KEY;
  $printers_xml = get_result($printers_url);
  $printers = array();
  foreach ($printers_xml->printer as $printer) {
    $id = (string) $printer->id;
    $name = (string) $printer->name;
    $printers[$id] = $name;
  }
  return $printers;
}
?>