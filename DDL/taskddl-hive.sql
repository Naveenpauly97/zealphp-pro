-- Hive DDL Script

-- Drop tables if they exist (Hive uses CASCADE to drop dependent objects)
DROP TABLE IF EXISTS task_logs;
DROP TABLE IF EXISTS tasks;
DROP TABLE IF EXISTS usr_sckt_dtls;
DROP TABLE IF EXISTS users;

-- Users table for authentication
CREATE TABLE IF NOT EXISTS users (
  id INT,
  username STRING,
  email STRING,
  password_hash STRING,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
STORED AS ORC;

-- User socket details table
CREATE TABLE IF NOT EXISTS usr_sckt_dtls (
  id INT,
  user_id INT,
  fd INT,
  md5_hash STRING,
  password_hash STRING,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
STORED AS ORC;

-- Tasks table
CREATE TABLE IF NOT EXISTS tasks (
  id INT,
  user_id INT,
  title STRING,
  description STRING,
  status STRING,
  priority STRING,
  due_date DATE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
STORED AS ORC;

-- Task logs for tracking changes
CREATE TABLE IF NOT EXISTS task_logs (
  id INT,
  task_id INT,
  user_id INT,
  action STRING,
  old_values STRING,
  new_values STRING,
  created_at TIMESTAMP
)
STORED AS ORC;

-- Note: Hive does not support AUTO_INCREMENT, PRIMARY KEY, UNIQUE, or FOREIGN KEY constraints.
--       Indexes are not supported in the same way as MySQL.
--       ENUM is replaced with STRING.
--       JSON columns are stored as STRING.
--       Sample data insertion is not included as Hive is not optimized for row-level DML.

-- Hive INSERT syntax:
-- INSERT INTO TABLE table_name [PARTITION (partcol1=val1, ...)] VALUES (value1, value2, ...);

INSERT INTO TABLE users (id, username, email, password_hash) VALUES 
(1,'admin', 'admin@example.com', '$2y$10$XMhXC/FGLHRg3IAS2.zDiOmkGS4sRsueuzOOnbcfssIkzEJnaJq9q'), -- password: Naveen@1234
(2, 'demo', 'demo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');   -- password: password


INSERT INTO TABLE tasks (id , user_id, title, description, status, priority, due_date) VALUES 
(1, 1, 'Setup ZealPHP Project', 'Initialize the ZealPHP framework with OpenSwoole', 'completed', 'high', '2024-01-15'),
(2, 1, 'Implement Authentication', 'Create user login and registration system', 'in_progress', 'high', '2024-01-20'),
(3, 1, 'Build Task Management', 'Create CRUD operations for tasks', 'pending', 'medium', '2024-01-25'),
(4, 2, 'Test the Application', 'Perform comprehensive testing', 'pending', 'low', '2024-01-30');

