# 🔧 Installer Tracker (PHP + MySQL)

This is a lightweight PHP + MySQL project built for tracking installation events from authenticated users.
This was created in order to better learn & understand PHP.

## 🚀 Features

- User authentication system with auto-registration
- One pre-inserted admin user (via phpMyAdmin)
- Role-based access: 
  - **Installer**: logs in, downloads a package, and gets tracked
  - **Admin**: views a dashboard with user/install stats
- Download tracking includes:
  - `user_id`
  - `device_id` (generated in-browser)
  - `IP address`
- Dashboard shows:
  - Total registered users
  - Total install events
  - Users who didn’t install
  - Install count per user

## 🛠 Tech Stack

- PHP (Vanilla)
- MySQL (with phpMyAdmin for admin access)
- XAMPP (for local dev server)
- HTML/CSS (Facebook-style theme, red variant)
- JavaScript (used to persist device ID)

## 📁 Structure

project-root/
│
├── index.php # Login + register form
├── download.php # Installer download + tracking
├── dashboard.php # Admin panel
├── logout.php # Ends session
├── install_package.txt # Downloadable mock file
├── style.css # Unified CSS styles
└── README.md # This file

## 🧪 Usage

1. Clone repo to your local XAMPP `htdocs` folder
2. Import the included SQL schema into phpMyAdmin
3. Create an admin user manually (via phpMyAdmin)  
4. Visit `http://localhost/project-folder/index.php`
5. Log in as an installer or admin to test features

## 📌 Notes

- All users are auto-registered on login unless already existing
- Admin role available only via phpMyAdmin (For simplicity)
- Device ID is stored in localStorage to simulate real-world tracking

---

Feel free to extend this into a full installer analytics panel or use it as a learning base for PHP+MySQL apps.
