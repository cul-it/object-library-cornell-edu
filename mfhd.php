<?php
$prolog =  <<< HERE
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
HERE;
if ($xml)  {  }
else {print $prolog;}
// enable user error handling
$mfhd = $qa[1];
libxml_use_internal_errors(true);
include_once("mfhd.inc");
$result = mysql_query("SELECT * FROM mfhds WHERE id = $mfhd ");
if ($xml)  { print_r($result) ; exit(0); }
?>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:dc="http://purl.org/dc/elements/1.1/" >
<head>
<title>
<?php
echo 'Result:' . $mfhd;
?>
</title>
</head>
<body>
<?php
include('common.php');
echo mfhd_fmt($result);
?>
</body>
</html>
