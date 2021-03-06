# JUSTNOW API

https://justnow-api.herokuapp.com

For Web Version: https://justnow-web.herokuapp.com

Justnow is a custom travel planner. It learns your interests on your every booking and gives suggestions and shows activities to you around this area.

For travellers which has limited time, you can select your availability and justnow makes you a plan according to the traffic times and transportation times between the activity locations.

#### This project made for the Setur Travel Hackaton and became a 3rd in there.


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

#### Install postgresql
```
https://www.postgresqltutorial.com/install-postgresql/
```

### Installing

After installed postgresql successfully, you have to start postgres:

```
pg_ctl -D /usr/local/var/postgres start
```

And create database

```
createdb postgres
```

### Update your .env

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=
DB_PASSWORD=
```

### Install Composer
```
brew install composer
composer install
```

### Migrate Db
```
php artisan migrate
```

### Serve
```
php artisan serve
```
