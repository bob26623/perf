#month=$1
mkdir /tmp/$$
touch /tmp/$$/mem1 /tmp/$$/mem2 /tmp/$$/mem3
#CD="/users/logic/sar-r"
#cd $CD
#CHECK=`wc 10*.sar-r |awk '{print $3}'`
CHECK=7862 # size of file that contain 293 lines of sar-r

for i in `ls -t *.sar-r`
do
echo $i |cut -f1 -d. > /tmp/$$/mem1
if [ $CHECK -ne `wc $i |awk '{print $3}'` ]
   then
      for j in `cat 10*.sar-r |tail +5 |head -1437 |cut -f1-2 -d:`
      #for j in `cat 10*.sar-r |tail +5 |head -287 |cut -f1-2 -d:`
      do
        T=`grep $j: $i|awk '{print length($1);}`
        TMP=`echo $T|awk '{print length($1);}'`
        if [ $TMP -eq 0 ]
          then
             echo "0" >> /tmp/$$/mem1
          else
             grep $j: $i |awk '{print $2/131072}' >> /tmp/$$/mem1
        fi
      done
   else
      #cat $i |tail +5 |head -1437 |awk '{print $2/131072}' >> /tmp/$$/mem1
      #cat $i |tail +5 |head -287 |awk '{print $2/131072}' >> /tmp/$$/mem1
      cat $i |tail +5 |head -287 |awk '{print $2/131072}' | while read line
      do
        printf "%3.2f\n" `echo "$1-$line" | /usr/bin/bc -l ` >> /tmp/$$/mem1
      done
fi
      paste /tmp/$$/mem1 /tmp/$$/mem2 > /tmp/$$/mem3
      cp /tmp/$$/mem3 /tmp/$$/mem2
done
echo "time" > /tmp/$$/time
for j in `cat 10*.sar-r |tail +5 |head -287 |cut -f1-2 -d:`
 do
  echo $j >> /tmp/$$/time
done
paste /tmp/$$/time /tmp/$$/mem2 > /tmp/$$/mem3
head -288 /tmp/$$/mem3 > realmem.out
rm -rf /tmp/$$

exit 0
