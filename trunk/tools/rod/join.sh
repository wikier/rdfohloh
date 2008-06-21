#!/bin/bash

DIR=$1
DEST="rdfohloh.nt"

rm -f $DIR$DEST
touch $DIR$DEST

for FILE in $( find $DIR -type f -name '*.rdf' | sort )
do
  echo $FILE
  python getnt.py $FILE >> $DEST
done

