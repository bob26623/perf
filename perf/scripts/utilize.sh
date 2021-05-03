printf "%s\t%s\t%s\t%s\t%s\t%s\t%s\n" DATE %usr %sys %wio %idle %peak %aver > utilize.out
for DATE in 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 31
#for FILE in `ls -tr *.sar-u`
do
   FILE=$DATE???.sar-u
#   DATE=`echo $FILE |cut -f1 -d.`
   if [ -f $FILE ]; then
      DAY_IDLE=`tail -1 $FILE | awk '{print $5}'`
      if [ $DAY_IDLE != "%idle" ]; then
          DAY_AVER=`tail -1 $FILE | awk '{print $2+$3+$4}'`
          DAY_PEAK=`cat $FILE |sort -k 5 | head -4 |tail -1|awk '{print 100-$5}'`
          DAY_UTILIZE=`tail -1 $FILE | awk '{print " "$2" "$3" "$4" "$5}'`
          printf "%s\t%3.2f\t%3.2f\t%3.2f\t%3.2f\t%3.2f\t%3.2f\n" $DATE $DAY_UTILIZE $DAY_PEAK $DAY_AVER >> utilize.out
          #echo "$DATE$MONTH$YEAR $DAY_UTILIZE $DAY_PEAK $DAY_AVER" >> utilize.out
      fi
   fi
done
