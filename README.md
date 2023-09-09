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