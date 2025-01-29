Expense Management API 💰📊

This is a simple API built using PHP and MySQL to manage user expenses. The API allows the following operations: Create, Read, Update, and Delete (CRUD) for expenses.
Requirements 🛠️

    PHP >= 8.0
    MySQL Database
    Composer (for package management)
    Dotenv for environment variables
    Apache server (or any other server to run PHP)
    CORS enabled for cross-origin requests

Setup 🚀
1. Clone the repository

git clone <your-repository-url>

2. Install dependencies

Navigate to the project folder and install the dependencies using Composer.

cd <your-project-folder>
composer install

3. Create a .env file

Create a .env file in the root directory of the project and add the following configuration values:

DB_HOST=localhost
DB_USER=your-database-user
DB_NAME=your-database-name
DB_PASSWORD=your-database-password

4. Set up the database

Create a MySQL database and use the following SQL queries to set up the Expense table:

CREATE DATABASE your-database-name;

USE your-database-name;

CREATE TABLE Expense (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(255) NOT NULL,
    pass_word VARCHAR(255) NOT NULL,
    phone_no VARCHAR(20),
    email_id VARCHAR(255),
    expenses JSON,
    budget JSON
);

5. Start the server

Make sure Apache is running, or use the PHP built-in server to start the project:

php -S localhost:8000

API Endpoints 📝
1. POST /expense/login 🔑

Description: Authenticates a user by username and password.

Request Body:

{
  "username": "user123",
  "password": "password123"
}

Response:

    200 OK ✅: On successful login
    401 Unauthorized ❌: Invalid username or password

2. GET /expense/{user_id} 📑

Description: Retrieves a user's expenses.

Parameters:

    user_id (path parameter): The unique ID of the user.

Response:

{
  "success": true,
  "data": [
    {
      "user_id": 1001,
      "user_name": "John Doe",
      "expenses": [
        {
          "id": 1,
          "amount": "100.00",
          "category": "Food",
          "description": "Lunch",
          "date": "2025-01-15"
        },
        {
          "id": 2,
          "amount": "200.00",
          "category": "Rent",
          "description": "Monthly rent",
          "date": "2025-01-01"
        }
      ]
    }
  ]
}

Response Code:

    200 OK ✅: On successful retrieval of expenses.
    404 Not Found ❌: User not found.

3. PATCH /expense/{user_id} 🛠️

Description: Updates a user's expenses.

Parameters:

    user_id (path parameter): The unique ID of the user.

Request Body:

{
  "type": "update",
  "data": [
    {
      "id": 1,
      "amount": "150.00",
      "category": "Food",
      "description": "Dinner",
      "date": "2025-01-20"
    },
    {
      "id": 2,
      "amount": "200.00",
      "category": "Rent",
      "description": "Updated monthly rent",
      "date": "2025-01-01"
    }
  ]
}

Response Code:

    200 OK ✅: On successful update.
    400 Bad Request ❌: Invalid data (if the request body is malformed or required fields are missing).
    402 Bad Request ❌: If there are issues with the database query.

4. DELETE /expense/{user_id} 🗑️

Description: Deletes a user's expenses.

Parameters:

    user_id (path parameter): The unique ID of the user.

Request Body:

{
  "data": [
    {
      "id": 1
    },
    {
      "id": 2
    }
  ]
}

Response Code:

    200 OK ✅: On successful deletion of expenses.
    400 Bad Request ❌: If invalid data is provided for deletion.
    402 Bad Request ❌: If database query fails.

5. POST /expense ➕

Description: Adds a new user to the database.

Request Body:

{
  "username": "newUser",
  "password": "newUserPassword",
  "phoneno": "1234567890",
  "email": "newuser@example.com"
}

Response Code:

    201 Created ✅: On successful creation of user.
    400 Bad Request ❌: Invalid data (if required fields are missing or invalid).
    402 Bad Request ❌: If the user creation query fails.

CORS 🌍

To allow cross-origin requests (from your frontend application), the server includes the following headers:

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

Make sure your frontend is properly configured to handle CORS preflight requests.
Error Handling ⚠️

The API follows standard HTTP status codes for error handling:

    200 OK ✅: The request was successful.
    201 Created ✅: The resource was created successfully.
    400 Bad Request ❌: The request was invalid (e.g., missing required fields).
    401 Unauthorized ❌: The request lacks valid authentication credentials.
    402 Bad Request ❌: The request failed due to a database or query issue.
    404 Not Found ❌: The requested resource could not be found.
    500 Internal Server Error ❌: An unexpected error occurred on the server.

Conclusion 🎉

This API helps you manage user expenses and related data. You can easily expand it by adding more features like tracking budgets or categorizing expenses.
