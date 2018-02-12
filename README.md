# Tours

A php-based API for a tour and event company.

## url

https://tours-server.herokuapp.com/

## installation

- clone the project. `git clone https://github.com/geeeh/tours.git`

- navigate to the project root folder.

- install packages. `composer install`

- setup the database. once the database is setup add your credentials to `.env` file.

- run migrations. `php artisan migrate`

- run project. `php -S localhost:3000 -t public`

## testing

- install `postman`

- make api calls to the routes.

## routes

- `auth/register`

- `auth/login`

- `requests/send`

- `events/createEvent`

- `events/upcomingEvents`

- `events/updateEvent/{id}`

- `events/deleteEvent/{id}`

## author

Godwin Gitonga.
