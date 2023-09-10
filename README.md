# Cycle Time

## Project setup
```sh
symfony composer install
```

Create DB
```sh
symfony console d:d:c
```
Update scheme
```sh
symfony console d:s:u --complete -f
```
JWT
```sh
symfony console lexik:jwt:generate-keypair
```
Run project
```sh
symfony serve
```

## Code quality tools
PHP CS fixer
```sh
vendor/bin/php-cs-fixer fix src
```
PHPStan
```sh
vendor/bin/phpstan analyse src
```
PHP MD
```sh
vendor/bin/phpmd src ansi codesize,unusedcode
```

## Usage
### Create user & get auth token
```sh
curl -X POST -H "Content-Type: application/json" -d '{"username":"username","password":"password"}' https://127.0.0.1:8000/api/users
```
```sh
curl -X POST -H "Content-Type: application/json" -d '{"username":"username","password":"password"}' https://127.0.0.1:8000/api/login
```
### Create location
```sh
curl -X POST -H "Content-Type: application/json" -H "Authorization: token" -d '{"display": "name", "latitude": 0.0, "longitude": 0.0}' https://127.0.0.1:8000/api/locations
```