#!/usr/bin/perl

### create time column
open(FILE,"time.txt") or die ("Cannot open file: $!");
$idle[0][0]="time";
$hr=1;
while (<FILE>){
 chomp;
 $idle[0][$hr] = $_;
 $hr++;
}
close (FILE);

#### create data column
$day=1;
@list = ` ls -tr *sar-u`;
for $file (@list){
 open(FILE,$file) or die ("Cannot open file: $!");
 @lines = <FILE>;
 close (FILE);
 @a=split(/\./,$file);
 $idle[$day][0]=$a[0];
 $hr=1;
 for $data (@lines){
   if ( $data=~/\d+:\d+:\d+\s+\d+/) {
      $_ = $data;
      ($tmp)=/\s+\d+\s+\d+\s+\d+\s+(\d+)/;
      $idle[$day][$hr]=100-$tmp;
      $hr++;
   }
 }
 $day++;
}
--$day;
for(my $j = 0; $j < $hr ; $j++){
   for(my $i = 0; $i <= $day; $i++){
      print "$idle[$i][$j]\t";
   }
   print "\n";
}


