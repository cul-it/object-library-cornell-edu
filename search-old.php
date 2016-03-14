<?php
$prolog =  <<< HERE
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
HERE;
if ($xml)  {  }
else {print $prolog;}
// enable user error handling
libxml_use_internal_errors(true);
$bibid = urlencode($qa[1]);
//echo $bibid;
$c = $count?$count:10; 
$s = $start?$start:0; 
$srch = 'http://localhost:8983/solr/select/?q=' . $bibid . "&version=2.2&start=$s&rows=$c&indent=on&sort=sort_title+asc";
//echo $srch;
$text = file_get_contents($srch);
if ($xml)  { print_r($text) ; exit(0); }
$txm = simplexml_load_string($text);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:dc="http://purl.org/dc/elements/1.1/" >
<head>
<title>
<?php
echo 'Result:' . htmlspecialchars($srch);
?>
</title>
</head>
<body>
<?php
include('common.php');
echo solr_fmt_list(&$txm);
?>
</body>
</html>
