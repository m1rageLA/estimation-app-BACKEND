# Estimation app
The application serves for convenient management of various estimates for multiple projects.


## Primary technologies

[![My Skills](https://skillicons.dev/icons?i=js,vue,scss,vuetify,webpack)](https://skillicons.dev)

[![My Skills](https://skillicons.dev/icons?i=php,laravel,mysql)](https://skillicons.dev)

## Installation
The project consists of 2 parts: Frontend & Backend.

To install the Frontend, you need to execute the following commands (one by one):

`git clone https://github.com/m1rageLA/estimation-app-FRONTEND`

`cd ./estimation-app-FRONTEND/`

`npm install`

`npm start`

Path to open:
http://localhost:9000/

...

To install the Backend, you need to execute the following commands (not in the Frontend folder):

`git clone https://github.com/m1rageLA/estimation-app-BACKEND`

`cd ./estimation-app-BACKEND/`

`composer install`


At this stage, it is necessary to create an empty database to store information from the project.

Open MySQL Command Line Client or another MySQL client.

Enter the command to create a database with the specified name:

`CREATE DATABASE estimation_db;"`

After that, return to the backend console (where you were writing commands previously)

`php artisan migrate`

`npm start`

# **Contacts:**
tymurrozhkovskyi@gmail.com
