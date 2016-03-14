<?php
// docs array in solr xml result
function solr_fmt_list(&$doclist,$bibid) {
  $dcount = count($doclist);
  $ret= '';
  $ret .= "\n<!--<h1>Count of results $dcount</h1>-->\n";
  for ($d = 0; $d < $dcount; $d++) {
    $ret .= "\n<!--" ."object $d:" . print_r($doclist[$d]->arr,TRUE). "</h2>-->\n";
    $ret .= solr_fmt($doclist[$d]->arr,$bibid);
  }
  return $ret;
}
function solr_fmt(&$o,$bibid) {
$dcount = count($o);
$ret= '';
for ($r = 0; $r < $dcount; $r++) {
  $count = count($o[$r]->arr);
  $ret .= "\n<!--<h2>Count of array fields $count</h2>-->\n";
  for ($i = 0; $i < $count; $i++) {
    $att= $o[$r]->arr[$i]['name'] ;
    $zcount = count($o->arr[$i]->str);
    for ($z = 0; $z < $zcount; $z++) {
      $attval=$o[$r]->arr[$i]->str[$z] . "";
      $ret .= '<div class="bibatt ' . $att . '-class">'.$att.': ';
      $ret .= '<span property="dc:'.$att.'">' . htmlspecialchars($attval) . "</span>";
      $ret .= '</div>';
      $ret .= "\n"; 
    }
  }
    $scount = count($o[$r]->str);
    $ret .= "\n<!--<h2>Count of string fields $scount</h2>-->\n";
    for ($s = 0; $s < $scount; $s++) {
      $att= $o[$r]->str[$s]['name'] ;
      $attval=$o[$r]->str[$s] . "";
      $ret .= '<div class="bibatt ' . $att . '-class">'.$att.': ';
      $ret .= '<span property="dc:'.$att.'">' . htmlspecialchars($attval) . "</span>";
      $ret .= '</div>';
      if ($att == 'id') {$id = str_replace('voyager.bibid.','',$attval);}
      $ret .= "\n"; 
    }
  $ret = "\n" . '<div class="reference" about="http://object.library.cornell.edu/bibid/'  
  . $id
  . '" typeof="Book">' . $ret;
  if ($bibid) { 
      $att= "marcxml";
      $attval = "http://library27.library.cornell.edu:9090/voyager?query=rec.id%3D$bibid&startRecord=1&maximumRecords=1&recordSchema=marcxml&version=1.1&operation=searchRetrieve";
      $ret .= '<div class="bibatt ' . $att . '-class">'.$att.': ';
      $href = htmlspecialchars($attval);
      $ret .= '<span property="dc:'.$att.'"><a href="' . $href . '">' .htmlspecialchars($attval) . "</a></span>";
      $ret .= '</div>';
   }
  $ret .= '<hr/></div>';
  $retarr[] = $ret; $ret = ''; 
} 
return join($retarr);
}
#    [0] => 2
#    [1] => 96
#    [2] => jgsm
#    [3] => JX1308 .R81 1969 
#    [4] => JX01308.0000R0810000000001969
#    [5] => 
// docs array in solr xml result
function mfhd_fmt(&$o) {
  while ($row = mysql_fetch_array($o, MYSQL_ASSOC)) {
    
      foreach ($row as $att => $attval) {
        $resource = '';
        if ($att == 'bib_id') {
           $resource = "resource='". 
           "http://object.library.cornell.edu/bibid/$attval'";
        }
        $ret .= '<div class="bibatt '.$att.'-class">'.$att .': ';
        $ret .= '<span '.$resource.' property="dc:'.$att.'">' . $attval . "</span>";
        $ret .= '</div>';
        $ret .= "\n"; 
      }
      
      $ret = '<div class="reference" about="http://object.library.cornell.edu/mfhd/'  
      . $row['id']
      . '" typeof="Book">' . $ret;
      $ret .= '<hr/></div>';
      $retarr[] = $ret; $ret = ''; 
}
return join($retarr);
}
