# **Backend Documentation**

## **Framework**
- **Laravel**: Used for backend logic and API development.

## **Database**
- **MySQL**: Used for data storage.
- **Credentials**:
  - **Username**: `admin@admin.com`
  - **Password**: `brucegwapo`
- **Tables**:
  1. **`users`**:
     - Columns: `id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`.
  2. **`projects`**:
     - Columns: `id`, `name`, `description`, `start_date`, `end_date`, `created_at`, `updated_at`.
  3. **`tasks`**:
     - Columns: `id`, `name`, `description`, `status`, `project_id`, `user_id`, `start_date`, `end_date`, `created_at`, `updated_at`.
  4. **`discussions`**:
     - Columns: `id`, `title`, `location`, `date`, `time`, `description`, `created_at`, `updated_at`.
  5. **`notifications`**:
     - Columns: `id`, `type`, `message`, `user_id`, `created_at`, `updated_at`.

## **Authentication**
- **Laravel’s Built-in Authentication**:
  - Database-based authentication.
  - Role-based access control (Admin, Adviser, User).

## **API Endpoints**
1. **Projects**:
   - `GET /api/projects`: Fetch all projects.
   - `POST /api/projects`: Create a new project.
   - `PUT /api/projects/{id}`: Update a project.
   - `DELETE /api/projects/{id}`: Delete a project.

2. **Tasks**:
   - `GET /api/tasks`: Fetch all tasks.
   - `POST /api/tasks`: Create a new task.
   - `PUT /api/tasks/{id}`: Update a task.
   - `DELETE /api/tasks/{id}`: Delete a task.

3. **Users**:
   - `GET /api/users`: Fetch all users.
   - `POST /api/users`: Create a new user.
   - `PUT /api/users/{id}`: Update a user.
   - `DELETE /api/users/{id}`: Delete a user.

4. **Discussions**:
   - `GET /api/discussions`: Fetch all discussions.
   - `POST /api/discussions`: Create a new discussion.
   - `PUT /api/discussions/{id}`: Update a discussion.
   - `DELETE /api/discussions/{id}`: Delete a discussion.

5. **Notifications**:
   - `GET /api/notifications`: Fetch all notifications.
   - `POST /api/notifications`: Create a new notification.

---

### **Task File Upload**
To support the file upload and download functionality, the backend will need the following updates:
1. **Database**:
   - Add a `files` table to store file details:
     - Columns: `id`, `task_id`, `file_name`, `file_path`, `uploaded_by`, `created_at`, `updated_at`.
2. **API Endpoints**:
   - `POST /api/tasks/{id}/upload`: Upload a file for a specific task.
   - `GET /api/tasks/{id}/download`: Download a file for a specific task.
3. **File Storage**:
   - Use Laravel’s built-in file storage system to store uploaded files.
   - Configure the storage path (e.g., `storage/app/uploads`).


### **Discussion Image Upload**
To support the image upload functionality, the backend will need the following updates:
1. **Database**:
   - Add an `image` column to the `discussions` table:
     - Column: `image` (stores the file path of the uploaded image).
2. **API Endpoints**:
   - `POST /api/discussions`: Create a new agenda with an optional image.
   - `GET /api/discussions/{id}`: Fetch agenda details, including the image.
3. **File Storage**:
   - Use Laravel’s built-in file storage system to store uploaded images.
   - Configure the storage path (e.g., `storage/app/discussion_images`).

# **Backend Documentation**

## **Notification System**
1. **Database**:
   - Add a `notifications` table (already provided by Laravel).
   - Columns: `id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`.

2. **Notification Class**:
   - Create a custom notification class for each type of notification (e.g., `TaskAssigned`, `ProjectUpdated`).
   - Example: `php artisan make:notification TaskAssigned`.

3. **Sending Notifications**:
   - Use Laravel’s `Notification` facade to send notifications.
   - Example:
     ```php
     use App\Notifications\TaskAssigned;
     $user->notify(new TaskAssigned($task));
     ```

4. **API Endpoints**:
   - `GET /notifications`: Fetch all notifications for the authenticated user.
   - `POST /notifications/{id}/mark-as-read`: Mark a specific notification as read.
   - `POST /notifications/mark-all-as-read`: Mark all notifications as read.