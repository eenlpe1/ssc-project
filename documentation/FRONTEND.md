# **Frontend Documentation**

## **UI Framework**
- **Laravel Blade**: Used for templating and rendering views.
- **Tailwind CSS**: Used for styling and responsive design.

## **Navigation Structure**
- **Sidebar**:
  - Links to Home, Projects, Tasks, Leaderboard, Reports, Users, and Discussion Board.
  - Hover effects (`hover:bg-blue-700`) for interactive feedback.

## **Key Components**
1. **Home Dashboard**:
   - Overview stats and quick links.
   - Displays recent activity, notifications, and quick access to other modules.

2. **Project Management**:
   - **Project Creation Form**:
     - Fields: Project Name, Description, Start Date, End Date.
     - Button: "Create Project".
   - **Project List**:
     - Displays project details (name, description, dates).
     - Action buttons: Edit, Delete, Add Task.

3. **Task Management**:
   - **Task Creation Form**:
     - Fields: Task Name, Description, Assigned Member, Start Date, End Date.
     - Button: "Add Task".
   - **Task List**:
     - Displays task details (name, description, status, assigned member, dates).
     - Status: Completed, In Progress, Pending.
     - Action buttons: Edit, Delete, Upload File, Mark as Complete.
   - **File Upload**:
     - Users can upload files before marking a task as complete.
     - Button: "Upload File".
     - File input field: Accepts multiple file types (e.g., `.docx`, `.pdf`, `.zip`).
   - **File Download**:
     - Administrators/Advisers can download files uploaded by users.
     - Button: "Download File" (visible only to Admins/Advisers).

4. **Leaderboard**:
   - **Table**:
     - Columns: Member Name, Stars, Completed Tasks, Completed Projects, Rank.
     - Displays rankings based on achievements.

5. **Discussion Board**:
   - **Agenda Creation Form**:
     - Fields: Title, Location, Date, Time, Description.
     - Button: "Add Agenda".
   - **Chat Window**:
     - Displays messages in real-time (without WebSockets).
     - Action buttons: Start Conversation, End Conversation.

6. **Notifications**:
   - **Notification Bell**:
     - Displays real-time updates (messages, tasks, projects, stars, badges).
     - Click to view details.

7. **Profile**:
   - **Profile Form**:
     - Fields: Name, Email, Role, Personal Information.
     - Button: "Save Changes".
   - **Log Out**:
     - Button: "Log Out".