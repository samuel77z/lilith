#!/bin/bash
set -x
# creating directories
mkdir c d
# creating lock files
touch d/lock{0,1,2,3}
# creating the pipe
mkfifo d/pipe
# make d unreachable
echo "deny from all" >d/.htaccess
# make c unreachable 
echo "deny from all" >d/.htaccess
# make it accessible to apache
chmod -R a+rw c d
# change owner to the right one
chown -R apache:apache c d
