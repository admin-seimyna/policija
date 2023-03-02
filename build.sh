#!/bin/bash

BUILDIR=$(date +%Y%m%d_%H%M%S)
BASEDIR=$(dirname $0)

printf "Directory:${BUILDIR}\n"

mkdir "${BASEDIR}/${BUILDIR}"
unzip -q "${BASEDIR}/build.zip" -d "${BASEDIR}/${BUILDIR}"

cd "${BASEDIR}/${BUILDIR}"

cp "${BASEDIR}/../.env" "${BASEDIR}/${BUILDIR}/"

sudo rm ${BASEDIR}/current
ln -sfT "${BASEDIR}/${BUILDIR}" "${BASEDIR}/current"

# link storage
cd "${BASEDIR}/current"
ln -sf "${BASEDIR}/../storage" "storage"

# link public storage with storage
ln -sf "${BASEDIR}/../storage/app/public" "public/storage"

# clean everything
sudo php artisan view:clear
sudo php artisan config:clear

# cache everything
sudo php artisan config:cache

# refresh DB
sudo php artisan migrate --force

# build
sudo php artisan build
sudo php artisan optimize

# Reload apache server
sudo service nginx restart
sudo sudo systemctl start php7.4-fpm

sudo rm "${BASEDIR}/build.zip"
sudo rm "${BASEDIR}/build.sh"
sudo rm "${BASEDIR}/${BUILDIR}/.env"
sudo chown -R www-data:www-data "${BASEDIR}/${BUILDIR}"
sudo chown www-data:www-data "${BASEDIR}/current"

cd "${BASEDIR}"
sudo sh -c 'ls -1t | tail -n +6 | xargs rm -rf'
