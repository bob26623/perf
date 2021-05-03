#month=$1
mkdir /tmp/$$
touch /tmp/$$/cpu1 /tmp/$$/cpu2 /tmp/$$/cpu3
#CD="/users/logic/sar-u"
#cd $CD
#CHECK=`wc 10*.sar-u |awk '{print $3}'`
CHECK=11909 # size of file that contain 293 lines of sar-u

for i in `ls -t *.sar-u`
do
echo $i |cut -f1 -d. > /tmp/$$/cpu1
if [ $CHECK -ne `wc $i |awk '{print $3}'` ]
   then
      #for j in `cat 10*.sar-u |tail +5 |head -1437 |cut -f1-2 -d:`
      for j in `cat 10*.sar-u |tail +5 |head -287 |cut -f1-2 -d:`
      do
        T=`grep $j: $i|awk '{print length($1);}`
        TMP=`echo $T|awk '{print length($1);}'`
        if [ $TMP -eq 0 ]
          then
             echo "0" >> /tmp/$$/cpu1
          else
             grep $j: $i |awk '{print 100-$5}' >> /tmp/$$/cpu1
        fi
      done
   else
      #cat $i |tail +5 |head -1437 |awk '{print 100-$5}' >> /tmp/$$/cpu1
      cat $i |tail +5 |head -287 |awk '{print 100-$5}' >> /tmp/$$/cpu1
fi
      paste /tmp/$$/cpu1 /tmp/$$/cpu2 > /tmp/$$/cpu3
      cp /tmp/$$/cpu3 /tmp/$$/cpu2
done
echo "time" > /tmp/$$/time
for j in `cat 10*.sar-u |tail +5 |head -287 |cut -f1-2 -d:`
 do
  echo $j >> /tmp/$$/time
done
paste /tmp/$$/time /tmp/$$/cpu2 > /tmp/$$/cpu3
head -288 /tmp/$$/cpu3 > realcpu.out
#cp /tmp/$$/cpu3 /tmp/$$/cpu2
#cat /tmp/$$/cpu2 |tr -s '\t' ' ' > realcpu.out
rm -rf /tmp/$$

exit 0
