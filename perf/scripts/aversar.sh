printf "%s\t%s\t%s\t%s\t%s\t%s\t%s\n" time %usr %sys %wio %idle %peak %aver > aversar.out
ls -ltr |cat *|tail +5|head -287|awk '{print $1}'|cut -f1-2 -d:|while read i
#cat *$MONTH* |tail +5 |head -1437|awk '{print $1}'|cut -f1-2 -d:|while read i
do
TIME_AVER=`grep $i *|cut -f2-4 -d:|awk '{idle+=$5;n+=1}END{print 100-(idle/n)}'`
TIME_PEAK=`grep $i *|sort -k 5|head -1 |awk '{print 100-$5}'`
TIME_ALL=`grep $i *|cut -f2-4 -d:|awk '{usr+=$2;sys+=$3;wio+=$4;idle+=$5;n+=1}END{print usr/n,sys/n,wio/n,idle/n;}'`

printf "%s\t%3.2f\t%3.2f\t%3.2f\t%3.2f\t%3.2f\t%3.2f\n" $i $TIME_ALL $TIME_PEAK $TIME_AVER >> aversar.out

done
;;

exit 0


