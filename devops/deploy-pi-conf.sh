#!/usr/bin/env bash


if ! grep -q "monitor.pi.idevcode.com" /etc/hosts; then
  echo "append to host file"
  echo "" >> /etc/hosts
  echo "127.0.0.1 monitor.pi.idevcode.com" >> /etc/hosts
fi

if [ ! -f /etc/apache2/ssl/server.crt ]; then
  echo "Installing SSL ##########################################################"
  mkdir /etc/apache2/ssl
  openssl genrsa -des3 -passout pass:x -out /etc/apache2/ssl/server.pass.key 2048
  openssl rsa -passin pass:x -in /etc/apache2/ssl/server.pass.key -out /etc/apache2/ssl/server.key
  rm /etc/apache2/ssl/server.pass.key
  openssl req -new -key /etc/apache2/ssl/server.key -out /etc/apache2/ssl/server.csr -subj "/C=US/ST=DC/L=Texas/O=Todd/OU=IT Department/CN=*.idevcode.com"
  openssl x509 -req -days 365 -in /etc/apache2/ssl/server.csr -signkey /etc/apache2/ssl/server.key -out /etc/apache2/ssl/server.crt

fi


cp ./monitor.pi.idevcode.com.conf /etc/apache2/sites-available/monitor.pi.idevcode.com.conf
ln -s /etc/apache2/sites-available/monitor.pi.idevcode.com.conf /etc/apache2/sites-enabled/monitor.pi.idevcode.com.conf

sudo service apache2 restart
