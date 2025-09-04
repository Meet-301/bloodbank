# Smart Blood Bank Management System

[![GitHub Stars](https://img.shields.io/github/stars/Meet-301/bloodbank?style=social)](https://github.com/Meet-301/bloodbank)
[![GitHub Forks](https://img.shields.io/github/forks/Meet-301/bloodbank?style=social)](https://github.com/Meet-301/bloodbank)


A comprehensive Blood Bank and Donor Management System (BBDMS) built with PHP and other web technologies.

## Table of Contents
- [Description](#description)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Usage](#usage)

## Description
This project provides a web-based platform for managing blood donors, recipients, and blood inventory. It includes admin and user interfaces for efficient management and interaction.

## Features

- **User Authentication:**  Secure login functionality for both administrators and recipients. ✅
- **Donor Management:**  Functionality to add, manage, approve/reject blood donors, and view donor list.  🦸
- **Inventory Management:**  Manage and track blood stock levels with features to add, edit, and delete blood stock. 🩸
- **Blood Requests:** Allows recipients to send blood donation requests to donors.  💌
- **Contact Queries:**  Handles and manages contact inquiries from users. 💬
- **Location Services:** Leverages latitude and longitude for proximity calculations of nearby donors. 🗺️
- **Email Notifications:** Sends email notifications for account approvals/rejections and other events using PHPMailer. 📧
- **Admin Dashboard:** A central admin panel that tracks all key stats for better overview and management. 📊
- **Responsive Design:** Utilizes Bootstrap for a responsive and mobile-friendly UI. 📱
- **Role-Based Access:** Provides different interfaces and functionalities based on user roles (admin, donor, recipient). 🔑

## Tech Stack

- **Primary Language:** PHP 💻
- **Frontend Framework:** Bootstrap 🎨
- **JavaScript Libraries:** jQuery 📜
- **Backend Framework**: Laravel like framework 🧱
- **Database:** MySQL ⚙️
- **Other:** HTML, CSS, JavaScript 🌐

## Installation

1.  **Clone the repository:**
   ```bash
   git clone https://github.com/Meet-301/bloodbank.git
   cd bloodbank
   ```
2.  **Database Configuration:**
    - Create a new database named `bbdms` in your MySQL server.
    - Update the database credentials in the following files:
        - `includes/config.php`
        - `admin/includes/config.php`
        - `Inventory/config.php`
    ```php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'bbdms');
    ```
3.  **PHPMailer Setup:**
    - The project uses PHPMailer for sending emails. Ensure that the PHPMailer library is correctly installed and configured.
    - Configure the Gmail SMTP settings in these files `admin/status_mail.php` and `PHPMailer/get_oauth_token.php`:

```php
    $mail->isSMTP();
    $mail->Host       = 'smtp.example.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'user@example.com';
    $mail->Password   = 'secret';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
```
4. **Import SQL:** Import bbdms.sql file to your phpmyadmin. To be found using the file name (Not available directly, but can be generated).
5.  **Install Composer Dependencies (if needed):** This project uses composer to manage dependencies
    ```bash
    composer install
    ```
6.  **Uploads Folder:** Create an uploads folder in the root and set apropriate permissions for the web server to write to it.

## Usage

1.  **Access the Admin Panel:**
    - Navigate to `/admin/index.php` in your web browser.
    - Use the default credentials (if not changed) to log in.
2.  **Access the User Interface:**
    - Browse to the root directory of the project to access the main user interface.
    - Register as a donor or recipient, or login if you already have an account.
