Rest Api
======

Symfony 3.0 REST API

Generate the SSH keys :

```
$ mkdir -p var/jwt # For Symfony3+, no need of the -p option
$ openssl genrsa -out var/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem 
```

Install dependencies :

```
$ composer install 
```



Create database:

```
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force
```


Create user :

```
$ php bin/console fos:user:create testuser test@example.com p@ssword
```
