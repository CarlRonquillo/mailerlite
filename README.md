# Installation Instruction

## Setup on local machine
Requirements: PHP 7.4, MySQL 5.x and latest Chrome browser.

1. Clone this repository to your desired project folder.
2. Open the cloned project to your desired text editor.
3. Copy `.env.example` to the root directory and rename it to `.env`.
4. On the `.env` file, configure your `database` credentials.
5. Create DB and import the backup database that I sent to you (`mailerlite.sql`).
6. Open your terminal and run `composer install`.
7. Run web app using `php artisan serve`. Then go to the provided url from the command, by default it is `http://127.0.0.1:8000/`.