# Installation Instruction
Requirements: PHP 7.4, MySQL 5.x and latest Chrome browser.

1. Clone this repository to your desired project folder.
2. Open the cloned project to your desired text editor.
3. Copy `.env.example` to the root directory and rename it to `.env`.
4. On the `.env` file, configure your `database` credentials and add your MailerLite api key for unit testing purposes on variable `MAILERLITE_API_KEY`
5. Also configure your test DB credentials on `phpunit.xml` if needed.
6. Create DB and import the backup database that I sent to you (`mailerlite.sql`).
7. Open your terminal and run `composer install`.
8. Run web app using `php artisan serve`. Then go to the provided url from the command, by default it is `http://127.0.0.1:8000/`.