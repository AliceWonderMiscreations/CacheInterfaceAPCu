#!/bin/bash
find . -print |grep "~$" |while read file; do
  rm -f ${file}
done
