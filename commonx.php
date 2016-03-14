<?php
// docs array in solr xml result
//include("/cullr/scripts/cullr_code/cullr_marc_extract.php");
function solr_id(&$o,$bibid) {
 $scount = count($o->str);
  for ($s = 0; $s < $scount; $s++) {
      $att= $o->str[$s]['name'] ;
      $attval=$o->str[$s] . "";
      if ($att == 'id') {
         if (strpos($attval,'voyager.bibid.') == 0) {
              $id = str_replace('voyager.bibid.','',$attval);
              $bibid = $id;
         }
         $ret .= '<bibid>http://object.library.cornell.edu/bibid/'.htmlspecialchars($bibid) . "</bibid>";
      $ret .= "\n";
      }
    }
return $ret;
}

function solr_fmt_list(&$doclist,$bibid) {
  global $list;
  global $count;
  $dcount = sizeof($doclist->result->doc);
  $ret= '';
  $ret .= ($list) ? "\n<count>$count</count>\n":
           "\n<h1>Requested count: $count</h1>\n";
  $ret .= ($list) ? "\n<rows>$dcount</rows>\n":
           "\n<h1>Count of results $dcount</h1>\n";
  for ($d = 0; $d < $dcount; $d++) {
    if ($list) {
        $ret .= solr_id($doclist->result->doc[$d],$bibid); 
    } else { $ret .= solr_fmt($doclist->result->doc[$d],$bibid); }
  }
  return $ret;
}

function solr_fmt(&$o,$bibid) {
  $reta;
  $ret ="";
  $count = count($o->arr);
  for ($i = 0; $i < $count; $i++) {
    $att= $o->arr[$i]['name'] ;
    $zcount = count($o->arr[$i]->str);
    for ($z = 0; $z < $zcount; $z++) {
      $attval=$o->arr[$i]->str[$z] . "";
      $ret .= '<div class="bibatt ' . $att . '-class">'.$att.': ';
      $ret .= '<span property="dc:'.$att.'">' . htmlspecialchars($attval) . "</span>";
      $ret .= '</div>';
      $ret .= "\n"; 
      $reta[] = $ret;
      $ret ="";
    }
  }
  $scount = count($o->str);
  $ret ="";
  for ($s = 0; $s < $scount; $s++) {
      $att= $o->str[$s]['name'] ;
      $attval=$o->str[$s] . "";
      $ret .= '<div class="bibatt ' . $att . '-class">'.$att.': ';
      $ret .= '<span property="dc:'.$att.'">' . htmlspecialchars($attval) . "</span>";
      $ret .= '</div>';
      if ($att == 'id') {
		if (strpos($attval,'voyager.bibid.') == 0) {
                  $id = str_replace('voyager.bibid.','',$attval);
                  $bibid = $id;
                }
      }
      $ret .= "\n"; 
      $reta[] = $ret;
      $ret ="";
    }
  $dcount = count($o->date);
  $ret ="";
  for ($s = 0; $s < $dcount; $s++) {
      $att= $o->date[$s]['name'] ;
      $attval=$o->date[$s] . "";
      $ret .= '<div class="bibatt ' . $att . '-class">'.$att.': ';
      $ret .= '<span property="dc:'.$att.'">' . htmlspecialchars($attval) . "</span>";
      $ret .= '</div>';
      if ($att == 'id') {
		if (strpos($attval,'voyager.bibid.') == 0) {
                  $id = str_replace('voyager.bibid.','',$attval);
                  $bibid = $id;
                }
      }
      $ret .= "\n"; 
      $reta[] = $ret;
      $ret ="";
    }
  $icount = count($o->int);
  $ret ="";
  for ($s = 0; $s < $icount; $s++) {
      $att= $o->int[$s]['name'] ;
      $attval=$o->int[$s] . "";
      $ret .= '<div class="bibatt ' . $att . '-class">'.$att.': ';
      $ret .= '<span property="dc:'.$att.'">' . htmlspecialchars($attval) . "</span>";
      $ret .= '</div>';
      $ret .= "\n"; 
      $reta[] = $ret;
      $ret ="";
    }
  $ret ="";
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
      //:w$res = cullr_marc_extract($attval); 
      if ($res !== false) {
        foreach ($res as $cullratt => $cullrattval)  {
        if (!(is_array($cullrattval))){ 
          $ret .= '<div class="bibatt ' . $cullratt . '-class">'.$cullratt.': ';
          $ret .= '<span property="'.$cullratt.'">' .htmlspecialchars($cullrattval) . "</span>";
          $ret .= "</div>";
        } else { 
            $c = count($cullrattval);
            for ($index = 0; $index < $c ; $index ++)  {
              if ($cullrattval[$index] != '' ) {
                $ret .= '<div class="bibatt ' . $cullratt . '-class">'.$cullratt.': ';
                $ret .= '<span property="'.$cullratt.'">' .htmlspecialchars($cullrattval[$index]) . "</span>\n";
                $ret .= "</div>\n";
              }
            }
        }
      }
      }
   }
  sort($reta);
  $ret = join($reta);
  //$ret .= '<hr/></div>';
  $ret .= '<hr/>';
  $retarr[] = $ret; $ret = ''; 
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
        if ($att == 'id') {
           $att = 'mfhd_id';
           $attval = 'voyager.mfhd_id.' . $attval;
        }
        $ret .= '<div class="bibatt '.$att.'-class">'.$att .': ';
        $ret .= '<span '.$resource.' property="cullr:'.$att.'">' . $attval . "</span>";
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
