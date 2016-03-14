<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
// enable user error handling
libxml_use_internal_errors(true);
$bibid = $qa[1];
$text = file_get_contents('http://localhost:8983/solr/select/?q=callnumber_s:' . $bibid . '*&version=2.2&start=0&rows=100&indent=on');
if ($xml)  { print_r($text) ; exit(0); }
$txm = simplexml_load_string($text);
$id = $txm->result->doc->str;
$count = count($txm->result->doc->arr);
for ($i = 0; $i < $count; $i++) {
  $att= $txm->result->doc->arr[$i]['name'] ;
  $attval=$txm->result->doc->arr[$i]->str[0] . "";
  if ($att == 'title') {
     $title=$id . ' ' . $attval;
  }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:dc="http://purl.org/dc/elements/1.1/" >
<head>
<title>
<?php
$cn = $txm->result->doc->arr->str[0];
echo 'Title:' . $title;
?>
</title>
</head>
<body>
<?php
$dcount = count($txm->result->doc);
for ($r = 0; $r < $dcount; $r++) {
  echo '<div class="reference" about="http://object.library.cornell.edu/bibid/'  
  . $bibid
  . '" typeof="Book">';
  $count = count($txm->result->doc->arr);
  for ($i = 0; $i < $count; $i++) {
    $att= $txm->result->doc->arr[$i]['name'] ;
    $zcount = count($txm->result->doc->arr[$i]->str);
    for ($z = 0; $z < $zcount; $z++) {
      $attval=$txm->result->doc->arr[$i]->str[$z] . "";
      echo '<div class="bibatt ' . $att . '-class">'.$att.': ';
      echo    $attval . "";
      echo '</div>';
      echo "\n"; 
    }
  }
    $scount = count($txm->result->doc[$r]->str);
    $att= $txm->result->doc[$r]->str['name'] ;
    for ($s = 0; $s < $scount; $s++) {
      $attval=$txm->result->doc[$r]->str[$s] . "";
      echo '<div class="bibatt ' . $att . '-class">'.$att.': ';
      echo    $attval . "";
      echo '</div>';
      echo "\n"; 
    }

  echo '<hr/></div>';
} 

?>
</body>
</html>
