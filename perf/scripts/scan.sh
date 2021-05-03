#month=$1
mkdir /tmp/$$
touch /tmp/$$/scan1 /tmp/$$/scan2 /tmp/$$/scan3
#CHECK=`wc 10*.sar-u |awk '{print $3}'`
CHECK=15666 # size of file that contain 293 lines of sar-g

for i in `ls -t *.sar-g`
do
echo $i |cut -f1 -d. > /tmp/$$/scan1
if [ $CHECK -ne `wc $i |awk '{print $3}'` ]
   then
      #for j in `cat 10*.sar-g |tail +5 |head -1437 |cut -f1-2 -d:`
      for j in `cat 10*.sar-g |tail +5 |head -287 |cut -f1-2 -d:`
      do
        T=`grep $j: $i|awk '{print length($1);}`
        TMP=`echo $T|awk '{print length($1);}'`
        if [ $TMP -eq 0 ]
          then
             echo "0" >> /tmp/$$/scan1
          else
             grep $j: $i |awk '{print $5}' >> /tmp/$$/scan1
        fi
      done
   else
      #cat $i |tail +5 |head -1437 |awk '{print 100-$5}' >> /tmp/$$/scan1
      cat $i |tail +5 |head -287 |awk '{print $5}' >> /tmp/$$/scan1
fi
      paste /tmp/$$/scan1 /tmp/$$/scan2 > /tmp/$$/scan3
      cp /tmp/$$/scan3 /tmp/$$/scan2
done
echo "time" > /tmp/$$/time
for j in `cat 10*.sar-g |tail +5 |head -287 |cut -f1-2 -d:`
 do
  echo $j >> /tmp/$$/time
done
paste /tmp/$$/time /tmp/$$/scan2 > /tmp/$$/scan3
head -288 /tmp/$$/scan3 > scan.out
#cp /tmp/$$/scan3 /tmp/$$/scan2
#cat /tmp/$$/scan2 |tr -s '\t' ' ' > realscan.out
rm -rf /tmp/$$

exit 0
