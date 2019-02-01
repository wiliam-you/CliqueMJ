# Configuring PIPL LARAVEL LIBRARY

Please follow the belor steps to configuring the code library at your system.


## Step1
Open your command prompt/terminal . Navigate to your working directory e.g=> /var/www (Don't create a new folder, Clone command will create a folder automatically)

Clone the laravel library using below command-

"git clone http://192.168.2.200/administrator/laravel-pipl-lib.git"

After this, please navigate to newly create folder- E.g=> pipl-laravel-lib

## Step2
 No you need to run following command.

sudo composer install (in linux)

composer install (windows)
 
 This command will install all require packages files to your directory (Vendor folder).

## Step3
Now run the following command to create a new application key

php artisan key:generate


## Step4
First you have create a database in phpmyadmin.

Now change the database settings in ".env" file and config/datatbase.php file.

E.g: .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test_laravel --enter your database name
DB_USERNAME=root
DB_PASSWORD=root

E.g: config/datatbase.php

'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'test_laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix' => 'test_',
            'strict' => false,
            'engine' => null,
        ],
        

## Step4
Please make sure your application "bootstrap" and "storage" folder must have writable permissions.

## Step5
Now run below commands:

php artisan migrate

php artisan db:seed


## Step6
Now run below commands to crear symbolic link: 

Windows

mklink /J storage c:\wamp\www\laravel-demo\storage\app\public
(change 'laravel-demo' as per your folder/directory name)


LINUX

sudo ln -s /var/www/laravel-demo/storage /var/www/laravel-demo/storage/app/public/ 
(change 'laravel-demo' as per your folder/directory name)

Note: Once you upload project on 182 you have to hit below php page to maintain symbolic link and before hitting the page please make changes accordingly.

The command will be -
symlink("/var/www/html/laravel-demo/storage/app/public", "storage");
So please change your folder name on 182 server (from 'LARAVEL_LIBRARY_2016_JUNE' to 'your folder name')


So that's it. Run your project.

If of you have any query please discuss with us.


