<a href="https://laravel.com" target="_blank">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</a>

<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
## Laravel product api

Few steps to go

- clone repository
- ```./vendor/bin/sail up ```
- ```./vendor/bin/sail composer i```
- ```./vendor/bin/sail artisan migrate```
- ```./vendor/bin/sail artisan process-product-queue```
- POST request ```curl -X POST -H "Content-Type: application/json" -d '{"name": "Super product"}' http://localhost:80/api/products```

