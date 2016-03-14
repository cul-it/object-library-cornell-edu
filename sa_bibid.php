<?php
// enable user error handling
libxml_use_internal_errors(true);
$bibid = $_GET['bibid'];
$xml = $_GET['xml'];
$text = file_get_contents('http://localhost:8983/solr/select/?q=id:voyager*' . $bibid . '&version=2.2&start=0&rows=1&indent=on');
if ($xml)  { print_r($text) ; exit(0); }
$txm = simplexml_load_string($text);
$id = $txm->result->doc->str;
$count = count($txm->result->doc->arr);
for ($i = 0; $i < $count; $i++) {
  $att= $txm->result->doc->arr[$i]['name'] ;
  $attval=$txm->result->doc->arr[$i]->str[0] . "<br/>";
  if ($att == 'title') {
     $title=$id . ' ' . $attval;
  }
  echo "\n"; 
}
?>
<html>
<title>
<?php
$cn = $txm->result->doc->arr->str[0];
echo 'Title:' . $title;
?>
</title>
<body>
<pre>
<?php
$count = count($txm->result->doc->arr);
for ($i = 0; $i < $count; $i++) {
  echo $txm->result->doc->arr[$i]['name'] . ':';
  echo $txm->result->doc->arr[$i]->str[0] . "<br/>";
  echo "\n"; 
}
?>
<pre>
</body>
</html>
