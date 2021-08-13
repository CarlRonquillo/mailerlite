# Installation Instruction
Requirements: PHP 7.4, MySQL 5.x and latest Chrome browser.

1. Clone this repository to your desired project folder.
2. Create DB and import the backup database that I sent to you (`mailerlite.sql`).
3. Open the cloned project to your desired text editor.
4. Copy `.env.example` to the root directory and rename it to `.env`.
5. On the `.env` file, configure your `database` credentials and add your MailerLite api key for unit testing purposes on variable `MAILERLITE_API_KEY`
6. Also configure your test DB credentials on `phpunit.xml` if needed.
7. Create `json` folder on `root/storage/app` and paste the `countries.json` included on the files that I've sent.
8. Open your terminal and run `composer install`.
9. Run `php artisan key:generate`.
10. Run web app using `php artisan serve`. Then go to the provided url from the command, by default it is `http://127.0.0.1:8000/`.