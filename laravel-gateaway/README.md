### Project Setup

0. Clone the repository
```bash
git clone https://github.com/SzeklerBoy/coffeehub.git
cd coffeehub
git checkout develop
```

1. Have these installed:
   * PHP (v8.2): https://windows.php.net/download/
     * make sure that int the php.ini file you have these uncommented:
       * extension=fileinfo
       * extension=pdo_mysql
   * Composer (v2.7.7): https://getcomposer.org/download/
   * MySQL or any other relational database server
   * Node.js: https://nodejs.org/en/download/

2. Install dependencies
```bash
npm install
composer install
```


3. Copy the .env file and configure environment variables:
```bash
cp .env.example .env
php artisan key:generate
```

4. Make sure you have a 'coffeehub' database created in your MySQL server or create using this command:
```bash
CREATE DATABASE coffeehub; # from terminal you need to run mysql `-u root -p` command first 
```
The MySQL server should be running.
5. Set up the database in .env and run migrations:
```bash
php artisan migrate
```
Set the database variables properly before (ex. DB_USERNAME, DB_PASSWORD)
6. Create a symlink from uploaded files to public directory:
```bash
php artisan storage:link
```
7. Compile assets
```bash
npm run dev
```
8. Serve the application from a different terminal
```bash
php artisan serve
```
9. (Optional) Start the queue processing for sending notifications
```bash
php artisan queue:work
```
10. (Optional) Start the schedule worker for running periodical jobs (e.g. free up inactive desks)
```bash
php artisan schedule:work
```
... or create a cron job with the following entry:
```cronexp
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

11.Visit the application in your browser at `http://localhost:8000` 
