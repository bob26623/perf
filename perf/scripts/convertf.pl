#!/usr/bin/perl
open(FILE,$ARGV[0]) or die ("Cannot open file: $!");
@lines = <FILE>;
close (FILE);
open(FILEU,">utilize.out") or die ("Cannot open file: $!");
open(FILEA,">aversar.out") or die ("Cannot open file: $!");
open(FILERC,">realcpu.out") or die ("Cannot open file: $!");
printf FILEU "%s\t%s\t%s\t%s\t%s\t%s\t%s\n","DATE","%usr","%sys","%wio","%idle","%peak","%aver";
printf FILEA "%s\t%s\t%s\t%s\t%s\t%s\t%s\n","time","%usr","%sys","%wio","%idle","%peak","%aver";
$count=1;
$REALMEM=0;
for $data (@lines){
   @tmp=split(/\s/,$data);
   #utilize
   if ($count <=31) {
        $date=substr($tmp[0],0,-7);
        printf FILEU "%s\t%d\t%d\t%d\t%d\t%d\t%d\n",$date,$tmp[1],$tmp[2],$tmp[3],$tmp[4],$tmp[5],$tmp[6];
   }elsif ($count == 35){
   # print header realcpu
        printf FILERC "%s\t","time";
        for ($i=7;$i<=$#tmp;$i++) {
          printf FILERC "%s\t",$tmp[$i];
        }
        printf FILERC "\n";

   }elsif ($count >=36 && $REALMEM == 0) {  
        #if ($tmp[0] eq "" || $tmp[0] eq "time"){
        #  if ($tmp[0] eq "time"){ $REALMEM=1; }
        if ($tmp[0] eq ""){
          $REALMEM=1; 
        }else{
          #aversar
          printf FILEA "%s\t%.2f\t%.2f\t%.2f\t%.2f\t%.2f\t%.2f\n",$tmp[0],$tmp[1],$tmp[2],$tmp[3],$tmp[4],$tmp[5],$tmp[6];
          #realcpu
          printf FILERC "%s\t",$tmp[0];
          for ($i=7;$i<=$#tmp;$i++) {
            printf FILERC "%d\t",$tmp[$i];
          }
          printf FILERC "\n";
        }
   }elsif ( $REALMEM == 1 && $tmp[0] ne "") {
        #realmem
        if ($tmp[0] eq "time"){
	  open(FILERM,">realmem.out") or die ("Cannot open file: $!");
          for ($i=0;$i<=$#tmp;$i++) {
            printf FILERM "%s\t",$tmp[$i];
          }
          printf FILERM "\n";
	  close(FILERM);
        }else{
	  open(FILERM,">>realmem.out") or die ("Cannot open file: $!");
          printf FILERM "%s\t",$tmp[0];
          for ($i=1;$i<=$#tmp;$i++) {
            printf FILERM "%.2f\t",$tmp[$i];
          }
          printf FILERM "\n";
	  close(FILERM);
          }
   }
   $count++;
   
 }
close(FILEU);
close(FILEA);
close(FILERC);
