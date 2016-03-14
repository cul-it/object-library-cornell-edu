#!/usr/bin/perl
use lib "$ENV{HOME}/lib/perl5";

use Library::CallNumber::LC;
use Math::BigInt;
use CGI;
use DBI;
use CGI::Carp;
my $q = CGI->new;
# Fetch the arg.
$argc = @ARGV;
$limit = '';
$start=0;
$count=10;
$ns = 'xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cullr="http://objects.library.cornell.edu/cullr/1.0/"';
print $q->header();
$xml = 0;
if ($argc) {
  $range = $ARGV[0];
  $start = $ARGV[1] ? $ARGV[1] : $start ;
  $count = $ARGV[2] ? $ARGV[2] : $count ;
} else {
  $arg = $q->param('q');
  @args = split(/\//,$arg);
  $range = $args[0];
  for ( $x=1; $x < @args; $x++ )  { 
  if ($args[$x] eq 'count') { 
     $count = $args[$x+1];
  }
  if ($args[$x] eq 'start') { 
     $start = $args[$x+1];
  }
  if ($args[$x] eq 'xml') { 
     $xml = 1;
  }
  }
}
  $limit = ' limit ' . join(',',$start,$count) . "\n"; 
# if two values, | separated -- may be blanks anywhere.
# Q 327
# one value  (like, all the QA's.) 
# or two, like 
#Q 350| Q385
@values = split(/\|/,$range);
if (@values > 1 ){
  $two = 1;
  my $low = Library::CallNumber::LC->new($values[0]);
  my $top = Library::CallNumber::LC->new($values[1]);
  $bots =  $low->start_of_range();
  $bots =~ s/ /0/g ;
  $ends =  $top->end_of_range();
  $ends =~ s/ /0/g ;
} else {
  $two = 0;
  my $low = Library::CallNumber::LC->new($values[0]);
  my $top = Library::CallNumber::LC->new($values[0]);
  $bots =  $low->start_of_range();
  $bots =~ s/ /0/g ;
  $ends =  $top->end_of_range();
  $ends =~ s/ /0/g ;
}
$dbh = DBI->connect('DBI:mysql:mfhd', 'mfhd_1', 'mfhd_1A'
	           ) || die "Could not connect to callnumber database: $DBI::errstr";
$query = "SELECT SQL_CALC_FOUND_ROWS DISTINCT bib_id,callno FROM mfhds WHERE ra_callno >= '$bots' and ra_callno <= '$ends' order by norm_callno $limit";
#print "\n$query\n";
$frows = "SELECT FOUND_ROWS()";
$sth = $dbh->prepare($query);
$fth = $dbh->prepare($frows);
$sth->execute();
$fth->execute();
@fresult = $fth->fetchrow_array();
$total = $fresult[0];
$rows = $sth->rows();
#$result = $sth->fetchrow_hashref();
my ($bib_id,$callno);
$rv = $sth->bind_columns(\$bib_id, \$callno);
if ($xml) { 
  print "<bibids>\n<total>$total</total>\n<start>$start</start>\n<count>$count</count>\n<rows>$rows</rows>\n";
} else { 
  print $q->start_html(-declare_xml=>true, -encoding => 'utf-8', -dtd => [ "-//W3C//DTD XHTML+RDFa 1.0//EN","http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd" ]);
}
while ($sth->fetch) {
      $objpath="http://object.library.cornell.edu/bibid/$bib_id";
      if ($xml) { print "<bibid>$objpath</bibid>\n";}
      else { 
            print "<div $ns class='reference' about='http://object.library.cornell.edu/bibid/$bib_id' typeof='Book'>";
            print "<div class='bibatt cullr:resource_callno'><span property='cullr:resource_callno'>$callno</span></div>";
            print "<div class='bibatt cullr:resource_bibid-class'><span property='cullr:resource_bibid'>$objpath</span></div>";
            print "</div>\n";
      }
}
if ($xml) { print "</bibids>\n";}
else {print $q->end_html;}
$sth->finish();
$fth->finish();
$dbh->disconnect();
exit 0;


