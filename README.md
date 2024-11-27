
# PHP-JS Tracker

This is a multi-component application combining a PHP backend and a React frontend for tracking and analyzing user activities.

## Project Structure

```.
├── Dockerfile
├── README.md
├── bin
│   └── consume
├── composer.json
├── composer.lock
├── docker-compose.yml
├── migrations.yaml
├── public
│   ├── index.php
│   ├── js
│   │   └── tracker.js
│   └── router_debug.log
├── react-app
│   ├── README.md
│   ├── eslint.config.js
│   ├── index.html
│   ├── package-lock.json
│   ├── package.json
│   ├── public
│   │   └── vite.svg
│   ├── src
│   │   ├── App.css
│   │   ├── App.jsx
│   │   ├── assets
│   │   │   └── react.svg
│   │   ├── index.css
│   │   └── main.jsx
│   └── vite.config.js
├── simulate_clicks
│   ├── Dockerfile
│   ├── package.json
│   ├── simulate-clicks.js
│   └── site1.html
├── src
│   ├── App
│   │   ├── Core
│   │   │   ├── Factory
│   │   │   │   └── ContainerFactory.php
│   │   │   ├── Interfaces
│   │   │   │   ├── RouteResolverInterface.php
│   │   │   │   └── RouterInterface.php
│   │   │   ├── Middlewares
│   │   │   │   └── CorsMiddleware.php
│   │   │   ├── RouteResolver.php
│   │   │   └── Router.php
│   │   ├── DatabaseConfig
│   │   │   └── DoctrineFactory.php
│   │   ├── Entities
│   │   │   ├── Contracts
│   │   │   │   └── QueryBuilderInterface.php
│   │   │   ├── Doctrine
│   │   │   │   └── Services
│   │   │   │       └── DoctrineQueryBuilder.php
│   │   │   └── ElasticSearch
│   │   │       └── Services
│   │   │           └── QueryParamsBuilder.php
│   │   ├── Factory
│   │   │   └── ElasticsearchClientFactory.php
│   │   ├── Http
│   │   │   ├── Controllers
│   │   │   │   ├── AnalyticsController.php
│   │   │   │   ├── Controller.php
│   │   │   │   ├── TrackerController.php
│   │   │   │   └── Validators
│   │   │   │       └── AnalyticsValidator.php
│   │   │   └── Resources
│   │   │       └── UniqueClickResource.php
│   │   ├── Messages
│   │   │   ├── DTO
│   │   │   │   └── VisitMessage.php
│   │   │   └── Handlers
│   │   │       ├── ElasticsearchHandler.php
│   │   │       └── MySQLHandler.php
│   │   ├── Models
│   │   │   ├── Mappers
│   │   │   │   └── VisitMapper.php
│   │   │   └── Visit.php
│   │   ├── config
│   │   │   ├── app.php
│   │   │   └── services.yaml
│   │   ├── database
│   │   │   ├── config.php
│   │   │   ├── doctrine.yaml
│   │   │   └── migrations
│   │   │       └── Version20241123151951.php
│   │   ├── routes.php
│   │   └── var
│   │       ├── cache
│   │       └── log
│   │           └── dispatch.log
│   ├── bootstrap.php
│   └── var
│       └── cache
│           └── @
├── tests
│   ├── Integration
│   │   └── ElasticsearchConnectionTest.php
│   └── Unit
│       ├── Mappers
│       │   └── VisitMapperTest.php
│       └── MySQLConsumerTest.php
└── var
    ├── cache
    │    └── @
    │     └── Z
    │       └── 1
    │         └── A4OVggQcjlbprU69ZmDg
    └── log
```

## Prerequisites

1. Docker and Docker Compose installed on your machine.
2. PHP 8.3 or higher for local development (if not using Docker).
3. Node.js (18 or higher) and npm for the frontend development.

## Setup Instructions

### Using Docker

1. Copy the `.env.example` file to `.env` and update the environment variables as needed.
2. Build and start the services:
   ```bash
   docker-compose up --build
   ```
3. Run the migrations:
```
   vendor/bin/doctrine-migrations generate -> for generating new table migration
   vendor/bin/doctrine-migrations migrate
```
4.Access the backend at `http://localhost:9999` and the frontend at `http://localhost:5173`.

## Features

- **Backend**: A PHP-based server leveraging Symfony components for routing, message handling, and database interaction.
- **Frontend**: A modern React application powered by Vite for fast development and builds.
- **Messaging**: RabbitMQ is used for queuing messages between the backend and Elasticsearch.
- **Data Analysis**: Elasticsearch is integrated for advanced analytics.

## Key Scripts

- `bin/consume`: Processes messages from RabbitMQ and updates Elasticsearch.
- `docker-compose.yml`: Defines services for PHP, RabbitMQ, and Elasticsearch.

## Future Improvements

- Add robust input validation for analytics data.
- Implement unit and integration tests for backend and frontend components.
- Extend the frontend for real-time analytics visualization.
