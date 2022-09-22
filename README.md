<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## laravel crud with adminlte + restapi

feature : 
1. Auth
2. report error log using bugsnag
3. repository pattern
4. trait response builder & bugsnag
5. validation request, try catch

## Installation
1. cp .env.example .env
2. composer Install
3. php artisan migrate
4. php artisan key:generate
5. copy bugsnag_api from .env.example to .env
6. copy app_secret_key from .env.example to .env
7. composer dump-autoload
