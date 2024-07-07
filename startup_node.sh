#!/bin/sh
# startup_node.sh

# if /var/www/html/node_modules is empty, copy /var/www/node_modules to /var/www/html/node_modules
if [ -z "$(ls -A /var/www/html/node_modules)" ]; then cp -r /var/www/node_modules /var/www/html; fi;

npm run dev;
