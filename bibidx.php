<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<?php
include('commonx.php');
// enable user error handling
libxml_use_internal_errors(true);
$bibid = $qa[1];
$srch='http://localhost:8983/solr/select/?q=id:voyager.bibid.' . $bibid . '&version=2.2&start=0&rows=1&indent=on';
$text = file_get_contents($srch);
if ($xml)  { print_r($text) ; exit(0); }
$txm = simplexml_load_string($text);
$id = $txm->result->doc->str;
$count = count($txm->result->doc->arr);
for ($i = 0; $i < $count; $i++) {
  $att= $txm->result->doc->arr[$i]['name'] ;
  $attval=$txm->result->doc->arr[$i]->str[0] . "";
  if ($att == 'title') {
     $title=$id . ' ' .  htmlspecialchars($attval);
  }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cullr="http://objects.library.cornell.edu/cullr/1.0/" >
<head>
<title>
<?php
echo 'Title:' . $title;
?>
</title>
</head>
<body>
<?php
echo solr_fmt($txm->result->doc,$bibid);
if ($mfhds) {
include_once("mfhd.inc");
$result = mysql_query("SELECT * FROM mfhds WHERE bib_id = $bibid ");
print mfhd_fmt($result);
}

?>
</body>
</html>
