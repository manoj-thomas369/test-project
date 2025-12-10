# Test  – Projects Listing with Login, Join, Pagination & AJAX Sorting

This is a small PHP + MySQL web app built for a technical test.

## Features

- Login using `users` table with salted double MD5:
  - `password = md5(md5(form_password) . row_salt)`
- Sessions to protect the projects page.
- Projects list joined from 3 tables:
  - `projects` (project_title, user_id, cid)
  - `users` (user_id, username)
  - `categories` (cid, category_name)
- Output columns:
  - Project Title | Username | Category Name
- Pagination:
  - 2 rows per page
- Sort By dropdown:
  1. Recent Projects (default)
  2. Order By Category Name ASC
  3. Order By Username ASC
  4. Order By Project Title ASC
- AJAX + jQuery:
  - Changing sort or page reloads only the table, not the whole page.

## Database Setup

Create a MySQL database (e.g. `test_db`) and run the SQL script to create:

- `users`
- `categories`
- `projects`

Sample users (both use password `temp123`):

- `manoj / temp123`
- `ajay / temp123`

## How to Run

1. Copy the project into `htdocs` (XAMPP) or `www` folder.
2. Update `db.php` with your DB name, user, and password.
3. Open `http://localhost/test2/login.php` in your browser.
4. Log in and view the projects list with sorting & pagination.