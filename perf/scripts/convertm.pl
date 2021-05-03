#!/usr/bin/perl
use POSIX;
open(FILE,$ARGV[0]) or die ("Cannot open file: $!");
@lines = <FILE>;
close (FILE);
open(FILE,">mem.out") or die ("Cannot open file: $!");
printf FILE "%s\t%s\n","time","MemUsage";
for $data (@lines){
   if ( $data=~/\d+:\d+:\d+\s+\d+/) {
      $_ = $data;
      ($time)=/(\d+:\d+)/;
      ($mem)=/\s+(\d+)/;
      printf "%s\t%d\n",$time,ceil($ARGV[1]-($mem/131072));
      printf FILE "%s\t%d\n",$time,ceil($ARGV[1]-($mem/131072));
   }
 }
close(FILE);

