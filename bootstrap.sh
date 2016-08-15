#!/usr/bin/env bash

# ---------------------------------------
#    Virtual Machine/Repository Setup
# ---------------------------------------
# Install language pack to support ondrej repositories
apt-get -y install language-pack-en-base

# Add repositories
LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
add-apt-repository ppa:ondrej/nginx
apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xcbcb082a1bb943db
add-apt-repository 'deb [arch=amd64,i386,ppc64el] http://nyc2.mirrors.digitalocean.com/mariadb/repo/10.1/ubuntu trusty main'

# Updating
apt-get -y update
apt-get -y autoremove

# ---------------------------------------
#              MariaDB Setup
# ---------------------------------------
debconf-set-selections <<< 'mariadb-server-10.1 mysql-server/root_password password password'
debconf-set-selections <<< 'mariadb-server-10.1 mysql-server/root_password_again password password'
apt-get -y install mariadb-server

# Create the project database
mysql -uroot -ppassword -e "CREATE DATABASE IF NOT EXISTS \`slim3-api\`;"
echo "Database created."

# ---------------------------------------
#              Cron Setup
# ---------------------------------------
# Create a cron to backup database daily
cron="0 10,22 * * * mysqldump -u root -ppassword --databases slim3-api | gzip > /var/lib/mysql/slim3-api/\`date +\%Y-\%m-\%d_\%H:\%M:\%S\`.sql.gz"
# Escape all the asterisks so we can grep for it
cron_escaped=$(echo "$cron" | sed s/\*/\\\\*/g)
# Check if cron job already in crontab
crontab -l | grep "${cronescaped}"
if [[ $? -eq 0 ]] ;
  then
    echo "Crontab already exists. Exiting..."
    exit
  else
    # Write out current crontab into temp file
    crontab -l > mycron
    # Append new cron into cron file
    echo "$cron" >> mycron
    # Install new cron file
    crontab mycron
    # Remove temp file
    rm mycron
    echo "Crontab created."
fi

# ---------------------------------------
#             Nginx Setup
# ---------------------------------------

# Installing Nginx
apt-get -y install nginx
service nginx start

# Symlink web root to /vagrant/public
if ! [ -L /var/www/html ]; then
  rm -rf /var/www/html
  ln -fs /vagrant/public /var/www/html
fi

# ---------------------------------------
#               PHP Setup
# ---------------------------------------
apt-get -y install php7.0-fpm php7.0-mysql php7.0-mcrypt php7.0-curl php7.0-gd php7.0-json php7.0-xml php7.0-mbstring
# Tighten up PHP security
sed -i s/\;cgi\.fix_pathinfo\s*\=\s*1/cgi.fix_pathinfo\=0/ /etc/php/7.0/fpm/php.ini
service php7.0-fpm restart

# ---------------------------------------
#      Configure the virtual host
# ---------------------------------------
mv /etc/nginx/sites-available/default /etc/nginx/sites-available/default.bak
cp /vagrant/default /etc/nginx/sites-available/default
service nginx restart