# Pomodoro Timer Web Application

A full-stack web application for tracking Pomodoro technique sessions with task management and history tracking.

## Features

- Pomodoro Timer (25 minutes)
- Short Break Timer (5 minutes)
- Long Break Timer (15 minutes)
- Task name input for session tracking
- Session history with filtering options
- Responsive design with Tailwind CSS

## Tech Stack

- **Frontend**: HTML, Tailwind CSS, Vanilla JavaScript
- **Backend**: PHP with MVC architecture
- **Database**: MySQL

## Installation

1. Clone the repository:

```bash
git clone https://github.com/yourusername/pomodoro-timer.git
cd pomodoro-timer
```

2. Create a MySQL database using the provided SQL script:

```bash
mysql -u root -p < database.sql
```

3. Configure your web server (Apache or Nginx) to point to the `public` directory as the document root.

4. Update the database configuration in `config/config.php` with your database credentials.

5. Make sure your web server has mod_rewrite enabled for clean URLs.

## Project Structure

```
/
├── app/                  # Application code
│   ├── controllers/      # Controllers
│   ├── models/           # Models
│   └── views/            # Views
├── core/                 # Core functionality
│   ├── App.php
│   ├── Database.php
│   └── Router.php
├── config/               # Configuration files
├── public/               # Publicly accessible files
│   ├── index.php         # Entry point
│   ├── js/
│   ├── css/
│   └── .htaccess
└── routes/               # Route definitions
```

## Usage

1. Navigate to the application in your browser.
2. Select a timer type (Pomodoro, Short Break, or Long Break).
3. Enter a task name (optional).
4. Click Start to begin the timer.
5. Use Pause to pause the timer and Reset to reset it.
6. When the timer completes, it will automatically save the session.
7. View your session history by clicking on the History tab.

## Future Enhancements

- User authentication
- Dark/light mode toggle
- Analytics dashboard with Chart.js
- Custom timer durations
- Multiple task lists
- Progressive Web App (PWA) capabilities

## License

MIT