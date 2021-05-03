#month=$1
mkdir /tmp/$$
touch /tmp/$$/runq1 /tmp/$$/runq2 /tmp/$$/runq3
#CHECK=`wc 10*.sar-u |awk '{print $3}'`
CHECK=11909 # size of file that contain 293 lines of sar-q

for i in `ls -t *.sar-q`
do
echo $i |cut -f1 -d. > /tmp/$$/runq1
if [ $CHECK -ne `wc $i |awk '{print $3}'` ]
   then
      #for j in `cat 10*.sar-q |tail +5 |head -1437 |cut -f1-2 -d:`
      for j in `cat 10*.sar-q |tail +5 |head -287 |cut -f1-2 -d:`
      do
        T=`grep $j: $i|awk '{print length($1);}`
        TMP=`echo $T|awk '{print length($1);}'`
        if [ $TMP -eq 0 ]
          then
             echo "0" >> /tmp/$$/runq1
          else
             grep $j: $i |awk '{print $2}' >> /tmp/$$/runq1
        fi
      done
   else
      #cat $i |tail +5 |head -1437 |awk '{print 100-$5}' >> /tmp/$$/runq1
      cat $i |tail +5 |head -287 |awk '{print $2}' >> /tmp/$$/runq1
fi
      paste /tmp/$$/runq1 /tmp/$$/runq2 > /tmp/$$/runq3
      cp /tmp/$$/runq3 /tmp/$$/runq2
done
echo "time" > /tmp/$$/time
for j in `cat 10*.sar-q |tail +5 |head -287 |cut -f1-2 -d:`
 do
  echo $j >> /tmp/$$/time
done
paste /tmp/$$/time /tmp/$$/runq2 > /tmp/$$/runq3
head -288 /tmp/$$/runq3 > runq.out
#cp /tmp/$$/runq3 /tmp/$$/runq2
#cat /tmp/$$/runq2 |tr -s '\t' ' ' > realrunq.out
rm -rf /tmp/$$

exit 0
