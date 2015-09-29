#!/bin/bash

# scan the hole server for ezpublish installation and report summary

HTTPD_ROOT=/home/httpd

DEBUG=1

EZSCAN=`which ezscan`
HOSTNAME_COMMAND=`which hostname`
THIS_SCRIPT=`basename $0`

#ez_dirs=`echo /home/httpd/*/html | xargs $EZSCAN -qD`

#ez_dirs="/home/httpd/demo.opencontent.it/html"

echo "<?xml version=\"1.0\"?>"
echo " <server>"
echo " <name>`$HOSTNAME_COMMAND -f`</name>"
echo " <date>`date`</date>"
echo " <ez_dirs>"


for dir_modello in '/home/httpd/*/html' '/home/httpd/*/html/ezpublish_legacy' ; do
  #ez_dirs=`echo /home/httpd/*/html | xargs $EZSCAN -qD`
  ez_dirs=`echo $dir_modello | xargs $EZSCAN -qD`

  for dir in $ez_dirs ; do
    echo "  <ez_dir>"
    echo "   <document_root>`$EZSCAN -D $dir`</document_root>"
    echo "   <ez_version>`$EZSCAN -z $dir`</ez_version>"
    echo "   <virtualhost>`$EZSCAN -w $dir`</virtualhost>"
    siteaccess_list=`$EZSCAN -S $dir`
    echo "   <siteaccesses>"
    for siteaccess in $siteaccess_list ; do
      echo "    <siteaccess>"
      echo "     <name>$siteaccess</name>"
      echo "     <DatabaseSettings>"
      echo "      <DatabaseImplementation>`$EZSCAN  -s $siteaccess -B DatabaseSettings -V Database $dir`</DatabaseImplementation>"
      echo "      <Server>`$EZSCAN  -s $siteaccess -B DatabaseSettings -V Server $dir`</Server>"
      echo "      <User>`$EZSCAN  -s $siteaccess -B DatabaseSettings -V Database $dir`</User>"
      echo "      <Database>`$EZSCAN  -s $siteaccess -B DatabaseSettings -V Database $dir`</Database>"
      echo "     </DatabaseSettings>"
      echo "    </siteaccess>"
    done
    echo "   </siteaccesses>"
    echo "  </ez_dir>"
  done
done
echo " </ez_dirs>"
echo " </server>"

