<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<?php
// enable user error handling
libxml_use_internal_errors(true);
$bibid = $qa[1];
$srch = 'http://localhost:8983/solr/select/?q=callnumber_s:' . $bibid . '*&version=2.2&start=0&rows=100&indent=on&sort=sort_title+asc';
$text = file_get_contents($srch);
if ($xml)  { print_r($text) ; exit(0); }
$txm = simplexml_load_string($text);
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:dc="http://purl.org/dc/elements/1.1/" >
<head>
<title>
<?php
echo 'Result:' . $id;
?>
</title>
</head>
<body>
<?php
include('common.php');
echo solr_fmt(&$txm->result->doc);
?>
</body>
</html>
