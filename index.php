<?php
$list = 0;
$start = 0;
$count = 11;
$mfhds = 0;
$xml = 0;
$q = $_GET['q'];
$qa = split('/', $q);
$qasize = count($qa);
for ($q=1 ; $q < $qasize; $q++ ){
    if ('count' == $qa[$q]) { 
       $count = $qa[$q+1] ; 
    }
    if ('start' == $qa[$q]) { 
       $start = $qa[$q+1] ; 
    }
    if ($qa[$q] == 'xml') { 
       $xml = 1 ; 
    }
    if ($qa[$q] == 'list') { 
       $list = 1 ; 
    }
    if ($qa[$q] == 'mfhds') { 
       $mfhds = 1 ; 
    }
}
if ($qa[0] == 'bibid') {
  include('bibid.php');
}
if ($qa[0] == 'callno') {
  include('callno.php');
}
if ($qa[0] == 'search') {
  include('search.php');
}
if ($qa[0] == 'mfhd') {
  include('mfhd.php');
}
if ($qa[0] == 'searchx') {
  include('searchx.php');
}
if ($qa[0] == 'bibidx') {
  include('bibidx.php');
}
?>
