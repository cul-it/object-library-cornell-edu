<?php
// enable user error handling
libxml_use_internal_errors(true);
$callno = $qa[1];
$start = $_GET['start'] ? $_GET['start'] : 0 ;
$text = file_get_contents('http://localhost:8983/solr/select/?q=callnumber_s:' . $callno . '*&version=2.2&start=' . $start . '&rows=10&indent=on');
if ($xml)  { 
    print_r($text) ; 
    exit(0); 
}
$txm = simplexml_load_string($text);
?>
<html>
<title>
<?php
$cn = $txm->result->doc->arr->str[0];
echo 'Call number:' . $cn;
print_r($txm->result->doc->arr[0]['str']);
?>
</title>
<body>
<pre>
<?php

//print_r($txm->result->doc);
$dcount = count($txm->result->doc);
for ($r = 0; $r < $dcount; $r++) {
  echo '<hr/><div class=record>';
  $count = count($txm->result->doc[$r]->arr);
  $xdoc = $txm->result->doc[$r];
  //print_r($xdoc);
  for ($i = 0; $i < $count; $i++) {
    echo $xdoc->arr[$i]['name'] . ':';
    echo $xdoc->arr[$i]->str[0] . "<br/>";
    echo "\n"; 
  }
  echo '</div>';
}
?>
<pre>
</body>
</html>
