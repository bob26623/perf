#!/usr/bin/perl

open(FILE,$ARGV[0]) or die ("Cannot open file: $!");
@lines = <FILE>;
close (FILE);
open(FILE,">cpu.out") or die ("Cannot open file: $!");
printf FILE "%s\t%s\t%s\t%s\t%s\n","time","%usr","%sys","%wio","%idle";
for $data (@lines){
   if ( $data=~/\d+:\d+:\d+\s+\d+/) {
      $_ = $data;
      ($time)=/(\d+:\d+)/;
      ($usr)=/\s+(\d+)/;
      ($sys)=/\s+\d+\s+(\d+)/;
      ($wio)=/\s+\d+\s+\d+\s+(\d+)/;
      ($idle)=/\s+\d+\s+\d+\s+\d+\s+(\d+)/;
      printf FILE "%s\t%d\t%d\t%d\t%d\n",$time,$usr,$sys,$wio,$idle;
   }
 }
close(FILE);

