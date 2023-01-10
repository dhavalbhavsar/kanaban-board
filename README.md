<h1 align="center">Jira Kanban Board</h1>

# Description

You need to make a Kanban board similar to Trello (https://trello.com) board but simplified.

# Postman API

https://www.postman.com/dhaval7790/workspace/kanban-board/collection/2552036-b4585e66-a895-4bc0-80e9-7e136e6e3e38?action=share&creator=2552036


# Setup environment in local

- Clone the repository
- composer install
- copy .env.example to .env (Update your database setting)
- php artisan key:generate
- php artisan migrate
- npm install && npm run production