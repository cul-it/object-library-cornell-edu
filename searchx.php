<?php
global $count;
global $start;
$prolog =  <<< HERE
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
HERE;
if ($xml || $list)  { if ($list) { print "<bibids>\n"; }  }
else {print $prolog;}
// enable user error handling
libxml_use_internal_errors(true);
$bibid = $qa[1];
$c = $count?$count:10; 
$s = $start?$start:0; 
$srch = 'http://localhost:8983/solr/select/?q=' . $bibid . "&version=2.2&start=$s&rows=$c&indent=on&sort=sort_title+asc";
$srch2 = htmlspecialchars($srch);
$text = file_get_contents($srch);
if ($xml)  { print_r($text) ; exit(0); }
$txm = simplexml_load_string($text);
//print_r($txm);
$total = 0;
foreach($txm->result->attributes() as $a => $b) {
   if ($a == 'numFound') {
    $total = $b;
   }
   if ($a == 'start') {
    $start = $b;
   }
}
if (!$list) {
$head = <<< HERE
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:dc="http://purl.org/dc/elements/1.1/" >
<head>
<title>
HERE;
} else {
$head = '';
}
print $head;
echo ($list) ? '' : 'Result:' . $srch2;
if (!$list) {
$headend = <<< HERE
</title>
</head>
<body>
HERE;
} else {
$headend = "<total>$total</total>\n<start>$start</start>\n";
}
print $headend;
include('commonx.php');
echo solr_fmt_list(&$txm);
if (!$list) {
$bodyend = <<< HERE
</body>
</html>
HERE;
} else {
$bodyend = <<< HERE
</bibids>
HERE;
}
print $bodyend;
