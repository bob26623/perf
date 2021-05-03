#!/bin/ksh
# $1 is utilze sar
# $2 is average sar
# $3 is realcpu
OUTPATH=/users/logic/report
OUTPUT=$OUTPATH/`hostname`.out 
UTIL_IN=$OUTPATH/`hostname`_asar.out
AVER_IN=$OUTPATH/`hostname`_aaversar.out
REAL_IN=$OUTPATH/`hostname`_realcpu.out
REALMEM_IN=$OUTPATH/`hostname`_realmem.out
TIME=/users/logic/scripts/time.txt

cat $UTIL_IN | while read q
do
echo $q >> $OUTPUT
done

##### Add blank to 34 line
count=`cat $OUTPUT|wc -l`
while [ $count -le 33 ]
do
echo " " >> $OUTPUT
count=$(($count+1))
done

##### merge aversar and realcpu
j=2
echo "time %user %sys %wio %idle %peak %aver \c" >> $OUTPUT
echo `cat $REAL_IN | head -1` >> $OUTPUT
cat $AVER_IN | while read k 
do
echo "$k \c" >> $OUTPUT
echo `cat $REAL_IN | head -$j|tail -1` >> $OUTPUT
j=$(($j+1))
done

##### Add blank to 1474 line
count=`cat $OUTPUT|wc -l`
while [ $count -le 1473 ]
do
echo " " >> $OUTPUT
count=$(($count+1))
done

##### merge realmem
j=2
echo "time \c" >> $OUTPUT
echo `cat $REALMEM_IN | head -1` >> $OUTPUT
cat $TIME | while read k
do
echo "$k \c" >> $OUTPUT
echo `cat $REALMEM_IN | head -$j|tail -1` >> $OUTPUT
j=$(($j+1))
done
