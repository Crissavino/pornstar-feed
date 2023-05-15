#!/bin/sh

# show in the terminal "hello world"
echo "This script will build, start the docker containers, run composer install, the migrations, run the command to download the feeds, run the tests and start the queue worker."

# copy the .env.example to .env
cp .env.example .env

# Build the docker images
#docker compose build --no-cache
docker compose build

# Initialize the containers
docker compose up -d

# wait for the database to be ready
sleep 5
echo "Running composer install and composer key generate"
docker exec -it mindgeektest_app bash -c "composer install && php artisan key:generate"
echo "Finish running composer install and composer key generate"

echo "Running the migrations"
docker exec -it --env="DB_HOST=mindgeektest_db" --env="DB_PORT=3306" --env="DB_DATABASE=mindgeektest" --env="DB_USERNAME=root" --env="DB_PASSWORD=root" mindgeektest_app php artisan migrate:fresh --force
sleep 5
echo "Finish running the migrations"

echo "Running Tests"
docker exec -it mindgeektest_app php artisan test --testsuite=Feature --stop-on-failure
sleep 5
echo "Finish running tests"

echo "Running the feed download command"
docker exec -it mindgeektest_app bash -c "php artisan feed:download"
echo "Finish running the feed download command"

echo "Starting the queue worker"
docker exec -it mindgeektest_app service supervisor start
docker exec -it mindgeektest_app supervisorctl reread
docker exec -it mindgeektest_app supervisorctl update
docker exec -it mindgeektest_app supervisorctl start queue-worker:*
echo "Finish starting the queue worker"

echo "Start the cron job"
docker exec -it mindgeektest_app service cron start
echo "Finish starting the cron job"

echo "You can access the application at http://localhost:80"


