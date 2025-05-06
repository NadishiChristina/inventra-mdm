# 🗂️ inventra-mdm

## Introduction

**inventra-mdm** is a PHP-based **Master Data Management System** with user authentication. It enables users to perform CRUD (Create, Read, Update, Delete) operations for managing **brands**, **categories**, and **items** within a centralized interface. 

---

## Setup Instructions

### 1. Prerequisites

- [Visual Studio Code](https://code.visualstudio.com/)
- [XAMPP](https://www.apachefriends.org/index.html) (Apache + MySQL)
- Web browser (for accessing `phpMyAdmin`)

### 2. Technologies Used

- PHP
- MySQL
- HTML, CSS
- Apache (via XAMPP)

### 3. Project Setup

1. **Clone or Download the Project**

   Copy the project folder into your XAMPP `htdocs` directory: C:\xampp\htdocs\inventra-mdm

2. **Start XAMPP**

   Open the XAMPP Control Panel and start both **Apache** and **MySQL** services.

---

## Database Setup

1. Open **phpMyAdmin** in your browser: http://localhost/phpmyadmin/


2. **Create Database**

- Create a database named `mdm_db`.
- Import the SQL script provided in the file:

  ```
  /inventra-mdm/data.sql
  ```

3. **Database Connection**

- Configure your database connection inside `db.php` with the correct MySQL credentials.

---
### Navigation Format

All pages in this project follow the same base URL format:

http://localhost:8080/inventra-mdm/your_page.php

Make sure your local server (XAMPP) is running and the project is placed inside the `htdocs` directory. You can then access any page by navigating to the corresponding route.

## Features

### Authentication

| Page              | Description                                                 | Route                                                   |
|-------------------|-------------------------------------------------------------|----------------------------------------------------------|
| **Login Page**     | User login interface                                        | `/login.php`                                             |
| **Register Page**  | New user signup interface                                   | `/signup.php`                                            |
| **Login Process**  | Validates login credentials and handles login errors        | `/login_process.php`                                     |
| **Signup Process** | Validates signup credentials and handles sign-up errors      | `/signup_process.php`                                    |
| **Logout**         | Logs out the user                                           | `/logout.php`                                            |


### Dashboard

| Page               | Description                              | Route               |
|--------------------|------------------------------------------|----------------------|
| **Main Dashboard** | Navigate to manage Brands, Categories, and Items | `/dashboard.php`     |


### Brand Management

| Action           | Description             | Route                   |
|------------------|-------------------------|--------------------------|
| **View Brands**  | List all brands         | `/brand.php`            |
| **Add Brand**    | Add a new brand         | `/brand_add.php`        |
| **Update Brand** | Edit an existing brand  | `/brand_update.php`     |
| **Delete Brand** | Remove a brand          | `/brand_delete.php`     |


### Category Management

| Action               | Description            | Route                      |
|----------------------|------------------------|-----------------------------|
| **View Categories**  | List all categories    | `/category.php`            |
| **Add Category**     | Add a new category     | `/category_add.php`        |
| **Update Category**  | Edit a category        | `/category_update.php`     |
| **Delete Category**  | Remove a category      | `/category_delete.php`     |


### Item Management

| Action          | Description                                                    | Route                  |
|-----------------|----------------------------------------------------------------|-------------------------|
| **View Items**  | List all items with **search**, **filter**, and **export**     | `/item.php`            |
| **Add Item**    | Add a new item and associate it with brand/category            | `/item_add.php`        |
| **Update Item** | Edit existing item details                                     | `/item_update.php`     |
| **Delete Item** | Remove an item from the system                                 | `/item_delete.php`     |

### Additonal Features
- Role-based access to ensure that logged-in users can only manage their own created records.
- item.php - Includes **search**, **filter**, and **export file** functionality.
- `/detectlogin.php`- Displays the username of the logged-in user across pages during the session

Demo video Link : https://drive.google.com/file/d/1qf9y8uSZ6Zu1kqTUjLxKFJgneRItoNeg/view?usp=sharing





