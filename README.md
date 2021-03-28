### Installation SF 5 avec docker
```
// Initialiser les images docker
docker-compose build --no-cache
// Lancer les containers docker
docker-compose up -d
```

##### Command to run unit tests
```
sudo docker-compose exec php bin/phpunit
```
