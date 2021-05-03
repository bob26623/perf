#!/usr/bin/perl
open(FILE,$ARGV[0]) or die ("Cannot open file: $!");
@lines = <FILE>;
close (FILE);
$count=0;
open(FILE1,">wsvc.out") or die ("Cannot open file: $!");
open(FILE2,">asvc.out") or die ("Cannot open file: $!");
open(FILE3,">read.out") or die ("Cannot open file: $!");
open(FILE4,">write.out") or die ("Cannot open file: $!");
for $data (@lines){
        chomp;
   if ( $data=~/\w+\s+\w+\s+\d+\s+\d+:\d+:\d+/) {
        if ($count == 0){
          printf FILE1 "time\t";
          printf FILE2 "time\t";
          printf FILE3 "time\t";
          printf FILE4 "time\t";
        }else{
          if($count == 1){
            foreach $ctrl (sort(keys %read)) {
              printf FILE1 "%s\t",$ctrl;
              printf FILE2 "%s\t",$ctrl;
              printf FILE3 "%s\t",$ctrl;
              printf FILE4 "%s\t",$ctrl;
            }
            printf FILE1 "\n";
            printf FILE2 "\n";
            printf FILE3 "\n";
            printf FILE4 "\n";
            quit;
          }
          $_ = $data;
          ($time)=/\w+\s+\w+\s+\d+\s+(\d+:\d+)/;
          printf FILE1 "%s\t",$time;
          printf FILE2 "%s\t",$time;
          printf FILE3 "%s\t",$time;
          printf FILE4 "%s\t",$time;
          foreach $ctrl (sort(keys %read)) {
            printf FILE1 "%0.1f\t",$wsvc{$ctrl}/$disk{$ctrl};
            printf FILE2 "%0.1f\t",$asvc{$ctrl}/$disk{$ctrl};
            printf FILE3 "%0.1f\t",$read{$ctrl}/1024;
            printf FILE4 "%0.1f\t",$write{$ctrl}/1024;
        
            $read{$ctrl}=0;
            $write{$ctrl}=0;
            $wsvc{$ctrl}=0;
            $asvc{$ctrl}=0;
            $disk{$ctrl}=0;
          }
          print FILE1 "\n";
          print FILE2 "\n";
          print FILE3 "\n";
          print FILE4 "\n";
        }
   }
   @tmp=split(/\s+/,$data);
   if ( $tmp[0] eq "" && $tmp[11] ne "" ){
     ## count 
     if ($tmp[1] eq "r/s" ){
        $count+=1;
     }else { 
        if ($tmp[11] =~ /^c\d{1,}t\S{16}d.+/ ){

        ## usage each controller

          @ctrl=split(/t/,$tmp[11]);
          $read{$ctrl[0]}+=$tmp[3];
          $write{$ctrl[0]}+=$tmp[4];
          $wsvc{$ctrl[0]}+=$tmp[7];
          $asvc{$ctrl[0]}+=$tmp[8];
          $disk{$ctrl[0]}+=1;
        }
     }
   }

} #for $data
