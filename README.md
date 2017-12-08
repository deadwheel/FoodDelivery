# Food Delivery

Laravel application for ordering food

### Requirements

- Laravel 5.5
- Passport
- Composer
- Artisan
- ENTRUST

### Installation

Download package and write some commands to be sure that everything will works fine

```sh
$ composer update
$ php artisan migrate
$ php artisan storage:link
```

after this run:
- yourhost.com/roles_create
- yourhost.com/attach

Roles_create is creating 2 roles:
- Administrator
- Driver

Attach - user with id=1 will get the admin role 

