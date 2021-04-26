# Media Manager
This is a simple application for uploading .jpg and .mp4 files 

## Installation
1. Fork and clone the repo or download the files to your desired location
    1. I used Laravel Valet to run my application, but you are free to use what you prefer.
2. Create a database for your app. 
    1. Make sure to record the name of the database you create and the username and password for the user
3. Create an Amazon s3 bucket on your AWS account. 
    1. Make sure you also create an IAM user and get that user’s ID and secret. You will also need the region that you assigned the bucket to and the name of the bucket. 
    2. If you are unfamiliar with this process, there is a good walkthrough of it at https://dev.to/aschmelyun/getting-started-with-amazon-s3-storage-in-laravel-5b6d
4. Create your .env file by copying the .env.example file to a new file named .env. 
    1. In the .env file Set:
        1. The database connection settings from the database that you created 
        2. Your AWS settings from the bucket and user that you created 
        3. The APP_NAME to “Media Manager”. Make sure you use the quotes. Of course this can be what you want, or you can just leave it.
5. Install the php dependencies by going to the terminal and running `composer install`
6. Migrate the database by running `php artisan migrate`
7. Create your application encryption key by running `php artisan key:generate`
8. Install your JavaScript dependencies by running `npm install`
9. Build the frontend by running `npm run dev`
10. You should now be able to access your site. 
    1. If you are using Valet and have your folder parked correctly, you should be able to just go to mediamanager.test.
11. Once you are on the site, you will need to register a new user. Then you can proceed to login and use the app.

## Running Tests
This app has both frontend and backend tests. To run those tests in the command line you can open the project root in a
terminal and run the following commands:
 - `npm test` (frontend)
 - `vendor/bin/phpunit` (backend)
