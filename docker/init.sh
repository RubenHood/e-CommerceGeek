#!/usr/bin/env bash
# Rename .env init DockerCompose
#echo "Rename Variable init"
#cp .env.example .env

#Up docker Compose.
#echo "Docker-compose up"
#docker-compose up
#if [ ! $? -eq 0 ]; then
#    echo "Couldn't start service or Control-C was pressed"
#    echo "cleaning up"
#    docker-compose down
#    exit $?
#fi

# Execute composer
echo "Install Composer"
docker exec -ti geekshubs_php7 sh -c "cd .. && composer install"
# Rename .env
echo "Create .env"
docker exec -ti geekshubs_php7 sh -c "cd .. && cp .env.example .env"
#Add permisions
echo "Add permision to logs"
docker exec -ti geekshubs_php7 sh -c "cd .. && chown -R www-data:www-data storage"
#Create encription key.
echo "Create Encription Key"
docker exec -ti geekshubs_php7 sh -c "cd .. && php artisan key:generate"
#Create Jwt
echo "Create jwt"
docker exec -ti geekshubs_php7 sh -c "cd .. && php artisan jwt:secret"
#INSTALL VOYAGER
echo "Create jwt"
docker exec -ti geekshubs_php7 sh -c "cd .. && php artisan voyager:install"
#INSTALL SEEDER
echo "Actualizar seed"
docker exec -ti geekshubs_php7 sh -c "cd .. && php artisan db:seed"
#CREATE USER ADMIN
#echo "Creación de usuario admin"
#docker exec -ti geekshubs_php7 sh -c "cd .. && php artisan voyager:admin admin@admin.com --create"

