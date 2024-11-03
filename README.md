# General information
The app was developed using WSL (Windows Subsystem for Linux) 

### Login and Register
To access the application, you can login by the route: `http://localhost:85/login` with: <br>
email:`tray@test.com` <br>
password:`password`

You also can register a new user by the registration route: `http://localhost:85/register`.


### Menus
Route to access seller management: `http://localhost:85/sellers`<br>
Route to access sales management: `http://localhost:85/sales`


### Sales Report Routine
To work with the routine of sending sales report, you must configure the following `MAIL` settings on `.env` file, according your data. <br>
`MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`

The app sends 2 different reports:
1. To users, as an admin report, with the total data for the day and also segmented by each seller.
2. To seller, with his/her daily sales data.

To receive an admin report, you need a `User` registration with a valid email. <br>
To receive a seller report, you need a `Seller` registration with a valid email.

By default, the report routine is defined to run every day at 8PM. But you can change the recurrence on `route.console` file.

_**Important**_ <br>
To receive the reports by the routine, you need the Schedule working (Additional Command 2)

# Installation

1. Clone the Repository
    ```bash
    git clone https://github.com/gutoholiveira/tray-test.git
    ```

2. Access the project folder
     ```bash
    cd tray-test
    ```

3. Create the `.env` file <br>
    Copy `.env.example` to `.env` and configure the environment variables.
   ```bash
   cp .env.example .env
   ```

4. Install the project dependencies with composer
    ```bash 
    composer install
    ```

5. Run the application containers
    ```bash
    ./vendor/bin/sail up -d
    ```

6. Generate the project key
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

7. Create the database tables and create data
    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```

8. Install the project dependencies with npm
    ```bash
    ./vendor/bin/sail npm install
    ```
9. Build the application
    ```bash
    ./vendor/bin/sail npm run build
    ```

10. Run front-end development build
    ```bash
    ./vendor/bin/sail npm run dev
    ```

## Running the Application
1. Start the Docker containers
    ```bash
    ./vendor/bin/sail up -d
    ```

2. Run the front-end development build 
    ```bash
    ./vendor/bin/sail npm run dev
    ```

## Additional Commands
1. Run Tests
   ```bash
   ./vendor/bin/sail artisan test
   ```

2. Start Schedule
   ```bash
   ./vendor/bin/sail artisan schedule:work
   ```

3. Stop Containers
   ```bash
   ./vendor/bin/sail down
   ```
