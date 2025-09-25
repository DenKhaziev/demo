1. docker compose up -d build
2. docker exec -it project_app composer install
3. docker exec -it project_node npm install
4. docker exec -it project_app php artisan migrate

[//]: # (далее пушим 2 таблицы в нашу бд)
docker exec -i project_db mysql -u root -proot so_penaty_db < /Users/deniskhaziev/Downloads/modx_testing_items.sql
docker exec -i project_db mysql -u root -proot so_penaty_db < /Users/deniskhaziev/Downloads/modx_testing_subjects.sql

[//]: # (далее в контейнере app запускаем сидеры - на проде убрать фейковые сидеры)
4. docker exec -it project_app php artisan db:seed




