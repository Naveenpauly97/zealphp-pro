# ZealTasks - Task Tracking Web Application

A single-user task tracking web application built with ZealPHP framework and OpenSwoole.

## Features

- ✅ **CRUD REST API** - Complete task management API under `/api/tasks`
- ✅ **Server-rendered pages** - Using ZealPHP's templating system
- ✅ **MYSQL Database** - Persistent storage with PDO in OpenSwoole coroutines
- ✅ **Authentication** - Signup & Login with bcrypt password hashing
- ✅ **Task Prioritization** - Low, Medium, High priority levels
- ✅ **Task Status Tracking** - Pending, In Progress, Completed
- ✅ **Due Date Management** - Track overdue tasks
- ✅ **Audit Logging** - Complete task change history
- ✅ **Session Management** - Protected routes with session middleware [AuthMmiddleware]
- ✅ **Responsive Design** - Mobile-friendly interface with vanilla JavaScript

## Setup Instructions

### Prerequisites

- PHP 8.2+ with OpenSwoole ≥ v22.1
- uopz extension
- mysql extension
- Composer

### Installation for Linux based system

1. **Clone the repository**
```bash
git clone https://github.com/Naveenpauly97/zealphp-pro.git
cd zealphp-pro
```

2. **Install dependencies**
```bash
bash install.sh
```

3. **Set up the database**

```bash
# Create MySQL database
mysql -u root -p
CREATE DATABASE zealphp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit

# Import schema
# Make Sure import the table schema from 
# location : zealphp-pro/DDL/taskddl.sql
```

### 2. Environment Configuration

Create a `.env` file in the project root:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zealphp
DB_USERNAME=root
DB_PASSWORD=your_password
```

4. **Start the server**
   ```bash
   php app.php
   ```

5. **Access the application**
   - Open your browser and go to `http://localhost:8080/login`
   - Register a new account or use the default credentials:
     - Username: `admin`
     - Password: `password`

### OR

### Installation for Docker based system [windows]

1. **Clone the repository**
```bash
git clone https://github.com/Naveenpauly97/zealphp-pro.git
cd zealphp-pro
```

2. **Install dependencies**
```bash
docker-compose up --build
```

3. **Set up the database**

```bash
# NOTE: 
# Database port Expose at 3307 in local Connect it and setup the DDL import 

# Create MySQL database
mysql -u root -p
CREATE DATABASE zealphp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit

# Import schema
# Make Sure import the table schema from 
# location : zealphp-pro/DDL/taskddl.sql
```

### 2. Environment Configuration

Create a `.env` file in the project root:

```env
DB_HOST=db
DB_PORT=3306
DB_DATABASE=zealphp
DB_USERNAME=root
DB_PASSWORD=root
```

## API Endpoints

### Authentication API
- `POST /api/auth/register` - Register a new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout user

### Authentication Pages
- `GET /register` - Register page
- `POST /register` - Register Router for new user
- `GET /login` - Login page
- `POST /login` - Login Router for user


### Tasks API
- `GET /api/tasks/list` - Get all tasks for authenticated user
- `POST /api/tasks/create` - Create a new task
- `GET /api/tasks/get?id={id}` - Get specific task
- `PUT /api/tasks/update?id={id}` - Update task
- `DELETE /api/tasks/delete?id={id}` - Delete task

### Tasks Page

- `GET /tasks` - Get all tasks page for authenticated user
- `GET /tasks/create` - Create page for new task
- `POST /tasks/create/{userId}` - Create Router for new task
- `GET /tasks/{taskId}/edit` - Update page for task 
- `POST /tasks/update/{taskId}` - Update Router for task

## Database Schema (ER Diagram)


```
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│     users       │       │     tasks       │       │   task_logs     │
├─────────────────┤       ├─────────────────┤       ├─────────────────┤
│ id (PK)         │◄─────┐│ id (PK)         │       │ id (PK)         │
│ username        │      ││ user_id (FK)    │◄─────┐│ task_id         │
│ email           │      ││ title           │      ││ user_id (FK)    │
│ password_hash   │      ││ description     │      ││ action          │
│ created_at      │      ││ status          │      ││ old_values      │
│ updated_at      │      ││ priority        │      ││ new_values      │
└─────────────────┘      ││ due_date        │      ││ created_at      │
                         ││ created_at      │      │└─────────────────┘
                         ││ updated_at      │      │
                         │└─────────────────┘      │
                         │                         │
                         └─────────────────────────┘

Relationships:
- users 1:N tasks (one user can have many tasks)
- users 1:N task_logs (one user can have many log entries)
- tasks 1:N task_logs (one task can have many log entries)
```

## Technology Stack

- **Backend**: ZealPHP Framework with OpenSwoole v22.1+
- **Database**: MYSQL with PDO
- **Frontend**: Vanilla HTML, CSS, and JavaScript
- **Authentication**: Session-based with bcrypt password hashing
- **Architecture**: PSR-12 compliant, modular design



## File Structure

```
├── api/
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   └── logout.php
│   └── tasks/
│       ├── list.php
│       ├── create.php
│       ├── update.php
│       ├── delete.php
│       └── get.php
├── config/
│   └── database.php
├── DDL/
│   └── taskddl.sql
├── route/
│   ├── auth.php
│   ├── createRouter.php
│   ├── editRouter.php
│   └── tasks.php
├── src/
│   ├── Database/
│   │   └── Connection.php
│   ├── Middleware/
│   │   └── AuthMiddleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── TaskLog.php
│   │   └── Task.php
│   ├── Repositories/
│   │   ├── UserRepository.php
│   │   ├── TaskLogRepository.php
│   │   └── TaskRepository.php
│   ├── Services/
│   │   ├── AuthService.php
│   │   ├── TaskService.php
│   │   └── TaskLogService.php
├── template/
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   └── tasks/
│       ├── _footer.php
│       ├── _head.php
│       ├── _header.php
│       ├── createPage_style.php
│       ├── createPage.php
│       ├── createPageContent.php
│       ├── editPage_style.php
│       ├── editPage.php
│       ├── editPageContent.php
│       ├── listPageContent.php
│       ├── taskListPage_style.php
│       └── taskListPage.php
└── public/
    └── tasks.php
```

## What I Learned

Building ZealTasks with ZealPHP and OpenSwoole was an enlightening experience that deepened my understanding of modern PHP development. The most significant learning was how OpenSwoole's coroutine-based architecture fundamentally changes how we approach web applications - moving from traditional request-response cycles to persistent, event-driven servers.

Working with ZealPHP's routing system and templating engine showed me the power of convention over configuration. The framework's implicit routing made development intuitive, while the session management and middleware system provided robust security patterns. Implementing authentication with bcrypt and session-based protection reinforced best practices for user security.

The database integration using PDO within coroutines was particularly interesting - it demonstrated how modern PHP can handle concurrent operations efficiently. Building the REST API with proper HTTP status codes and JSON responses taught me about API design principles and error handling strategies.

Most importantly, this project highlighted the importance of clean architecture, separation of concerns, and testable code. The modular structure with dedicated models, services, and middleware made the codebase maintainable and extensible. The experience reinforced that good software design transcends specific technologies and frameworks.

## Logging

Task changes are automatically logged to:
- Database: `task_logs` table

Each log entry includes timestamp, user ID, action type, and before/after values for complete audit trail.

## Main Pages:

/tasks - zealphp tasks main page

/ - new assessment landing pages

Refer doc - README_LANDING.md