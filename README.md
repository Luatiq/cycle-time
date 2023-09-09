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
vendor/bin/phpmd src ansi codesize,unusedcode,naming
```