# CLAUDE.md

## Project Overview

This is a PHP ToDo List CRUD web application with a Japanese UI. It uses plain PHP (no framework), PDO for MySQL database access, and has no build tools or package manager.

## Architecture

### File Structure

```
fullspeed_task/
├── index.php       # Main list page (read, delete, search, pagination)
├── edit.php        # Create/edit form page
├── confirm.php     # Confirmation page before saving (create/update)
├── function.php    # Utility/helper functions (XSS, validation, pagination, UI)
├── dbc.php         # Dbc class — OOP database methods (primary DB layer)
└── db-function.php # Procedural database functions (legacy duplicate of dbc.php)
```

### Page Flow

```
index.php ──[New Todo / Edit button]──▶ edit.php ──[Submit]──▶ confirm.php ──[Register]──▶ index.php
index.php ──[Delete button, POST to self]──▶ index.php
index.php ──[Search form, GET to self]──▶ index.php
```

### Database

- **Engine**: MySQL
- **Database name**: `todo_app`
- **Table**: `posts`
- **Credentials**: user=`post_user`, pass=`post_user` (hardcoded in `dbc.php` and `db-function.php`)
- **Timezone**: Asia/Tokyo (set in `confirm.php`)

Inferred table schema:
```sql
posts (
  ID         INT PRIMARY KEY AUTO_INCREMENT,
  title      VARCHAR(255),
  content    TEXT,
  created_at VARCHAR/DATETIME,
  updated_at VARCHAR/DATETIME
)
```

## Dependency Graph

```
function.php  ──requires──▶  dbc.php
db-function.php ──requires──▶  function.php, dbc.php
index.php   ──requires──▶  function.php, dbc.php
edit.php    ──requires──▶  function.php
confirm.php ──requires──▶  function.php, db-function.php
```

## Key Components

### `dbc.php` — `Dbc` class (primary DB layer)

The main OOP database abstraction used by `index.php`, `function.php`, and indirectly by `confirm.php`.

| Method | Description |
|---|---|
| `dbConnect()` | Opens a PDO connection to MySQL |
| `dbGetAllData()` | Returns all rows from `posts` |
| `dbGetData($id)` | Returns a single row by ID |
| `dbCreatePost($posts)` | Inserts a new post (with transaction) |
| `dbPostUpdate($posts)` | Updates an existing post (with transaction) |
| `dbDelete($id)` | Deletes a row by ID (checks existence first) |
| `dbGetAllId()` | Returns all IDs from `posts` |
| `dbCheckId($id)` | Returns a row if ID exists, falsy otherwise |
| `dbSearchData($search)` | Fuzzy LIKE search on `title` and `content` |

### `db-function.php` — Procedural DB functions (legacy)

Contains procedural versions of the same functions as `dbc.php`. This file is included by `confirm.php` but its procedural functions are not used in the current flow — the `Dbc` class methods are called instead via `function.php`. This file exists from a prior refactor to OOP and is now effectively redundant.

### `function.php` — Helpers and UI logic

| Function | Description |
|---|---|
| `h($s)` | XSS escaping via `htmlspecialchars($s, ENT_QUOTES, "UTF-8")` |
| `boxData($id, $where)` | Returns form field values for create/edit modes |
| `postValidate($posts)` | Validates `todo_title` (required, max 255 chars) and `contents` (required) |
| `getDisplayInformation($list)` | Returns `[maxPage, currentPage, displayData]` for pagination |
| `pagingFunction($now, $maxPage, $search)` | Renders pagination links |
| `backButton($back)` | Renders a "Back" button unless `$back` is set |
| `confirmConditionalBranch($nextAction, $posts)` | Handles confirm/register branching; calls `dbCreatePost` or `dbPostUpdate` |

Pagination constant: `MAX = 5` (items per page, defined inside `getDisplayInformation`).

## Security Conventions

- **XSS prevention**: All user-supplied output is wrapped with `h()` (`htmlspecialchars`).
- **SQL injection prevention**: All queries use PDO prepared statements with `bindValue` and explicit type parameters (`PDO::PARAM_INT`, `PDO::PARAM_STR`). `PDO::ATTR_EMULATE_PREPARES => false` is set.
- **Direct URL access prevention**: `confirm.php` checks for the `forcefulBrowsing` hidden POST field and calls `exit()` if absent.
- **Fuzzy search safety**: `addcslashes($search, '\_%')` escapes LIKE wildcards before binding.
- **Delete safety**: `dbDelete()` verifies the ID exists via `dbCheckId()` before deleting.

## Known Issues / Technical Debt

1. **Duplicate DB layer**: `dbc.php` (OOP) and `db-function.php` (procedural) contain near-identical logic. The procedural functions in `db-function.php` are unused in the current flow — only the `Dbc` class methods are called.

2. **Dead code after return**: `$dbh = null;` appears after `return $result;` in `dbGetAllData()` and `dbSearchData()` in both `dbc.php` and `db-function.php` — these lines are unreachable.

3. **`boxData()` calls `dbCheckId` / `dbGetData` multiple times**: In `edit.php`, `boxData($id, $where)` is called five times separately (once per array index) instead of once and destructured — this results in repeated DB queries.

4. **Hardcoded DB credentials**: Database username and password are hardcoded in `dbc.php` and `db-function.php`. There is no `.env` or configuration file.

5. **`display_errors` is On**: `ini_set('display_errors', "On")` is set in `dbc.php`, which is inappropriate for production.

6. **No CSRF protection**: Delete and edit operations use plain HTML forms with no CSRF tokens.

## Development Workflow

There are no automated tests, build scripts, linters, or Composer dependencies. Development is done by editing PHP files directly.

### Running Locally

Requirements:
- PHP (any recent version supporting PDO)
- MySQL with a `todo_app` database and `posts` table
- A user `post_user` with password `post_user` and access to `todo_app`

Serve with PHP's built-in server:
```bash
php -S localhost:8000
```

Then open `http://localhost:8000/index.php`.

### Database Setup (inferred)

```sql
CREATE DATABASE todo_app CHARACTER SET utf8;
CREATE USER 'post_user'@'localhost' IDENTIFIED BY 'post_user';
GRANT ALL PRIVILEGES ON todo_app.* TO 'post_user'@'localhost';
FLUSH PRIVILEGES;

USE todo_app;
CREATE TABLE posts (
  ID         INT AUTO_INCREMENT PRIMARY KEY,
  title      VARCHAR(255) NOT NULL,
  content    TEXT NOT NULL,
  created_at VARCHAR(50),
  updated_at VARCHAR(50)
);
```

## Conventions

- **Language**: PHP, no framework, plain HTML output
- **Encoding**: UTF-8 throughout
- **Date format**: `Y/m/d H:i:s` (e.g. `2024/01/15 13:45:00`), timezone Asia/Tokyo
- **POST/GET pattern**: Forms submit via POST for mutations; search uses GET
- **Validation**: Server-side only, errors call `exit()` with a message
- **DB access**: Use the `Dbc` class from `dbc.php` for all database operations
- **Output escaping**: Always use `h()` before echoing user-controlled data
- **Transactions**: Use `beginTransaction()` / `commit()` / `rollBack()` for INSERT and UPDATE
