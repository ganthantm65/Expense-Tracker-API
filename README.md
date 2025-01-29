# 💰 Expense Management API

## 📌 Overview
This is a simple Expense Management API built with PHP and MySQL. The API allows users to manage their expenses, budgets, and user authentication.

## ✨ Features
- 🔐 User authentication (login, register)
- 📊 Expense tracking (add, update, delete)
- 💵 Budget management
- 📥 Fetch user data
- 🌐 RESTful API implementation

## 📂 Project Structure
```
project-root/
│-- index.php
│-- vendor/
│-- server/
│   │-- Controller.php
│   │-- GateWay.php
│   │-- DataBase.php
│-- .env
│-- composer.json
│-- README.md
```

## ⚙️ Installation

### 🔧 Prerequisites
- 🐘 PHP 8+
- 🗄️ MySQL Database
- 📦 Composer
- 🌍 Apache Server

### 🚀 Steps
1. Clone the repository:
   ```sh
   git clone https://github.com/your-username/expense-management-api.git
   ```
2. Navigate into the project directory:
   ```sh
   cd expense-management-api
   ```
3. Install dependencies:
   ```sh
   composer install
   ```
4. Configure the `.env` file:
   ```sh
   DB_HOST=your_database_host
   DB_USER=your_database_username
   DB_PASS=your_database_password
   DB_NAME=your_database_name
   ```
5. Set up the database:
   - 📥 Import the provided SQL schema into your MySQL database.
6. Start the Apache server and ensure PHP is running.

## 🔗 API Endpoints
### 🔑 Authentication
#### 🔓 Login
```http
POST /expense/login
```
**Request Body:**
```json
{
  "username": "user1",
  "password": "password123"
}
```

### 👥 User Management
#### 📝 Register a User
```http
POST /expense
```
**Request Body:**
```json
{
  "username": "user1",
  "password": "password123",
  "phoneno": "1234567890",
  "email": "user@example.com"
}
```

### 💳 Expense Management
#### 📃 Get User Expenses
```http
GET /expense/{user_id}
```
#### ➕ Add Expense
```http
PUT /expense/{user_id}
```
**Request Body:**
```json
{
  "type": "add",
  "data": {
    "id": 1,
    "amount": 100,
    "category": "Food",
    "description": "Lunch",
    "date": "2024-01-29"
  }
}
```

#### ❌ Delete Expense
```http
PUT /expense/{user_id}
```
**Request Body:**
```json
{
  "type": "delete",
  "data": []
}
```

### 🏦 Budget Management
#### 💸 Set Budget
```http
POST /expense/budget
```
**Request Body:**
```json
{
  "id": 1,
  "budget": {
    "food": 500,
    "entertainment": 200
  }
}
```

## 👤 Author
Ganthan T.M - [GitHub Profile](https://github.com/ganthantm65/)

