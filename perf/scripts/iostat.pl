#!/usr/bin/perl
$HEADER=TRUE;
@list = `ls -tr *iostat`;
for $file (@list){
 open(FILE,$file) or die ("Cannot open file: $!");
 @lines = <FILE>;
 close (FILE);
 @a=split(/\./,$file);
 $count=0;
 for $data (@lines){
   @tmp=split(/\s+/,$data);
   if ( $tmp[0] eq "" && $tmp[11] ne "" ){
     ## count 
     if ($tmp[1] eq "r/s" ){
        $count+=1;
        foreach $ctrl (sort(keys %peakr)) {
          if ($peakread{$ctrl} < $peakr{$ctrl} || $peakr{$ctrl} eq ""){
              $peakread{$ctrl}=$peakr{$ctrl};
           }
          if ($peakwrite{$ctrl} < $peakw{$ctrl} || $peakw{$ctrl}  eq ""){
              $peakwrite{$ctrl}=$peakw{$ctrl};
           }
          $peakr{$ctrl} = 0 ;
          $peakw{$ctrl} = 0 ;
        }
     }else { # data


        if ($tmp[11] =~ /^c\d{1,}t\S{16}d.+/ ){

        ## usage each controller

          @ctrl=split(/t/,$tmp[11]);
          $read{$ctrl[0]}+=$tmp[3];
          $write{$ctrl[0]}+=$tmp[4];
          $wsvc{$ctrl[0]}+=$tmp[7];
          $asvc{$ctrl[0]}+=$tmp[8];
          $disk{$ctrl[0]}+=1;
          $peakr{$ctrl[0]}+=$tmp[3];
          $peakw{$ctrl[0]}+=$tmp[4];


        ## same LUN on all ctrl
          @lun=split(/d/,$tmp[11]);
          $lun_read{$ctrl[0]."-".$lun[1]}+=$tmp[3];
          $lun_write{$ctrl[0]."-".$lun[1]}+=$tmp[4];
          $lun_wsvc{$ctrl[0]."-".$lun[1]}+=$tmp[7];
          $lun_asvc{$ctrl[0]."-".$lun[1]}+=$tmp[8];

          if ($lun_pwsvc{$ctrl[0]."-".$lun[1]} < $tmp[7] || $lun_pwsvc{$ctrl[0]."-".$lun[1]} eq "" ){
                $lun_pwsvc{$ctrl[0]."-".$lun[1]} = $tmp[7];
          }

          if ($lun_pasvc{$ctrl[0]."-".$lun[1]} < $tmp[8] || $lun_pasvc{$ctrl[0]."-".$lun[1]} eq "" ){
                $lun_pasvc{$ctrl[0]."-".$lun[1]} = $tmp[8];
          }

        } #### check tmp[11]
     } ####else if tmp[1] eq "r/s"
   } #### if ( $tmp[0] eq "" && $tmp[11] ne "" ){
 } #### for $data (@lines){

 ### write data to file for each hba in MB

if ($HEADER eq "TRUE"){
  open(FILE1,">wsvc.out") or die ("Cannot open file: $!");
  open(FILE2,">asvc.out") or die ("Cannot open file: $!");
  open(FILE3,">aread.out") or die ("Cannot open file: $!");
  open(FILE4,">awrite.out") or die ("Cannot open file: $!");
  open(FILE13,">pread.out") or die ("Cannot open file: $!");
  open(FILE14,">pwrite.out") or die ("Cannot open file: $!");
  printf FILE1 "%s\t",DATE;
  printf FILE2 "%s\t",DATE;
  printf FILE3 "%s\t",DATE;
  printf FILE4 "%s\t",DATE;
  printf FILE13 "%s\t",DATE;
  printf FILE14 "%s\t",DATE;

  foreach $lun (sort(keys %lun_read)) {
   @ctrl=split(/-/,$lun);
  if ($chk ne $ctrl[0]){
   open(FILE,">".$ctrl[0]."_wsvc.out") or die ("Cannot open file: $!");
   open(FILE5,">".$ctrl[0]."_asvc.out") or die ("Cannot open file: $!");
   open(FILE26,">".$ctrl[0]."_pwsvc.out") or die ("Cannot open file: $!");
   open(FILE25,">".$ctrl[0]."_pasvc.out") or die ("Cannot open file: $!");
   open(FILE35,">".$ctrl[0]."_read.out") or die ("Cannot open file: $!");
   open(FILE36,">".$ctrl[0]."_write.out") or die ("Cannot open file: $!");
   printf FILE "%s\t","DATE";
   printf FILE "%s\t",$lun;
   printf FILE5 "%s\t","DATE";
   printf FILE5 "%s\t",$lun;
   printf FILE26 "%s\t","DATE";
   printf FILE26 "%s\t",$lun;
   printf FILE25 "%s\t","DATE";
   printf FILE25 "%s\t",$lun;
   printf FILE35 "%s\t%s\t","DATE",$lun;
   printf FILE36 "%s\t%s\t","DATE",$lun;
   $chk = $ctrl[0];
  }else{
   printf FILE "%s\t",$lun;
   printf FILE5 "%s\t",$lun;
   printf FILE26 "%s\t",$lun;
   printf FILE25 "%s\t",$lun;
   printf FILE35 "%s\t",$lun;
   printf FILE36 "%s\t",$lun;
  }
  }
 foreach $ctrl (sort(keys %read)) {
   printf FILE1 "%s\t",$ctrl;
   printf FILE2 "%s\t",$ctrl;
   printf FILE3 "%s\t",$ctrl;
   printf FILE4 "%s\t",$ctrl;
   printf FILE13 "%s\t",$ctrl;
   printf FILE14 "%s\t",$ctrl;
 }
  printf FILE1 "\n";
  printf FILE2 "\n";
  printf FILE3 "\n";
  printf FILE4 "\n";
  printf FILE13 "\n";
  printf FILE14 "\n";
  $HEADER = FALSE;
  $chk="";
}

  printf FILE1 "%s\t",$a[0];
  printf FILE2 "%s\t",$a[0];
  printf FILE3 "%s\t",$a[0];
  printf FILE4 "%s\t",$a[0];
  printf FILE13 "%s\t",$a[0];
  printf FILE14 "%s\t",$a[0];
 foreach $ctrl (sort(keys %read)) {
  printf FILE1 "%d\t",($wsvc{$ctrl}/$count)/($disk{$ctrl}/$count);
  printf FILE2 "%d\t",($asvc{$ctrl}/$count)/($disk{$ctrl}/$count);
  printf FILE3 "%d\t",($read{$ctrl}/1024)/$count;
  printf FILE4 "%d\t",($write{$ctrl}/1024)/$count;
  printf FILE13 "%d\t",$peakread{$ctrl}/1024;
  printf FILE14 "%d\t",$peakwrite{$ctrl}/1024;
  $wsvc{$ctrl}=0;
  $asvc{$ctrl}=0;
  $read{$ctrl}=0;
  $write{$ctrl}=0;
  $disk{$ctrl}=0;
  $peakread{$ctrl}=0;
  $peakwrite{$ctrl}=0;
  
 }
  printf FILE1 "\n";
  printf FILE2 "\n";
  printf FILE3 "\n";
  printf FILE4 "\n";
  printf FILE13 "\n";
  printf FILE14 "\n";

  foreach $lun (sort(keys %lun_read)) {
  @ctrl=split(/-/,$lun);
  if ($chk ne $ctrl[0]){
    open(FILE,">>".$ctrl[0]."_wsvc.out") or die ("Cannot open file: $!");
    open(FILE5,">>".$ctrl[0]."_asvc.out") or die ("Cannot open file: $!");
    open(FILE26,">>".$ctrl[0]."_pwsvc.out") or die ("Cannot open file: $!");
    open(FILE25,">>".$ctrl[0]."_pasvc.out") or die ("Cannot open file: $!");
    open(FILE35,">>".$ctrl[0]."_read.out") or die ("Cannot open file: $!");
    open(FILE36,">>".$ctrl[0]."_write.out") or die ("Cannot open file: $!");
    printf FILE "\n%s\t",$a[0];
    printf FILE5 "\n%s\t",$a[0];
    printf FILE26 "\n%s\t",$a[0];
    printf FILE25 "\n%s\t",$a[0];
    printf FILE35 "\n%s\t",$a[0];
    printf FILE36 "\n%s\t",$a[0];
    printf FILE "%d\t",$lun_wsvc{$lun}/$count;
    printf FILE5 "%d\t",$lun_asvc{$lun}/$count;
    printf FILE26 "%d\t",$lun_pwsvc{$lun};
    printf FILE25 "%d\t",$lun_pasvc{$lun};
    printf FILE35 "%.1f\t",$lun_read{$lun}/1024;
    printf FILE36 "%.1f\t",$lun_write{$lun}/1024;
    $chk = $ctrl[0];
  }else{
    printf FILE "%d\t",$lun_wsvc{$lun}/$count;
    printf FILE5 "%d\t",$lun_asvc{$lun}/$count;
    printf FILE26 "%d\t",$lun_pwsvc{$lun};
    printf FILE25 "%d\t",$lun_pasvc{$lun};
    printf FILE35 "%.1f\t",$lun_read{$lun}/1024;
    printf FILE36 "%.1f\t",$lun_write{$lun}/1024;
  }
  $lun_read{$lun}=0;
  $lun_write{$lun}=0;
  $lun_wsvc{$lun}=0;
  $lun_asvc{$lun}=0;
  $lun_pwsvc{$lun}=0;
  $lun_pasvc{$lun}=0;
}


}
close(FILE);
close(FILE1);
close(FILE2);
close(FILE3);
close(FILE4);
close(FILE13);
close(FILE14);
close(FILE5);
close(FILE25);
close(FILE26);
close(FILE36);
close(FILE35);
