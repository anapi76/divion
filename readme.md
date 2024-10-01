[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com) 
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-4169E1?style=flat&logo=postgresql&logoColor=white)](https://www.postgresql.org) 
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net) 
[![JSON](https://img.shields.io/badge/JSON-000000?style=flat&logo=json&logoColor=white)](https://www.json.org/json-es.html) 
[![Symfony](https://img.shields.io/badge/Symfony-333333?style=flat&logo=symfony&logoColor=white)](https://symfony.com) 
[![Doctrine](https://img.shields.io/badge/Doctrine-3C873A?style=flat&logo=doctrine&logoColor=white)](https://www.doctrine-project.org) 
[![Composer](https://img.shields.io/badge/Composer-885630?style=flat&logo=composer&logoColor=white)](https://getcomposer.org) 
[![Docker](https://img.shields.io/badge/Docker-2496ED?style=flat&logo=docker&logoColor=white)](https://www.docker.com) 
[![Git](https://img.shields.io/badge/Git-F05032?style=flat&logo=git&logoColor=white)](https://git-scm.com) 
[![MVC](https://img.shields.io/badge/MVC-007396?style=flat&logo=java&logoColor=white)](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) 
[![API RESTful](https://img.shields.io/badge/API%20RESTful-007396?style=flat&logo=api&logoColor=white)](https://restfulapi.net) 
[![Swagger](https://img.shields.io/badge/Swagger-85EA2D?style=flat&logo=swagger&logoColor=white)](https://swagger.io) 
[![Open API](https://img.shields.io/badge/Open%20API-85EA2D?style=flat&logo=swagger&logoColor=white)](https://www.openapis.org) 
[![Postman](https://img.shields.io/badge/Postman-FF6C37?style=flat&logo=postman&logoColor=white)](https://www.postman.com) 
[![Visual Studio Code](https://img.shields.io/badge/Visual%20Studio%20Code-007ACC?style=flat&logo=visual-studio-code&logoColor=white)](https://code.visualstudio.com) 
# Divino - Wine Management Application

## Description

Divino is a wine management system that allows users to manage wineries, wines, pairings, scores, and more. It provides a RESTful API for CRUD operations related to wine management.

## Development

- PHP 8.x
- Symfony 6.4
- MariaDB (running in a separate container)
- API Documentation with Swagger (OpenAPI): http://localhost:8000/api/doc
- Postman for testing
- The application backend and database run in Docker containers
- Visual Studio Code
- GitHub repository
  https://github.com/anapi76/divinoBack.git

## Configuration

- **Database**: The application uses a **MariaDB** database, configured in a separate container.
- **Docker Setup**: The application runs on **Docker** containers. The backend is exposed on port `8000`, and the database is on a separate container, accessible within the Docker network.
- **API Base URL**: The base URL for all API requests is `http://localhost:8000/`

## Endpoints

### Winery

- **GET** `/api/bodega` - Get all wineries
- **POST** `/api/bodega` - Create a new winery
- **GET** `/api/bodega/{id}` - Get a specific winery
- **PUT** `/api/bodega/{id}` - Update a winery
- **DELETE** `/api/bodega/{id}` - Delete a winery

### Color Wine

- **GET** `/api/color` - Get all wine colors

### Protected Designation of Origin

- **GET** `/api/denominacion` - Get all denominations
- **POST** `/api/denominacion` - Create a denomination
- **GET** `/api/denominacion/{id}` - Get a specific denomination
- **PUT** `/api/denominacion/{id}` - Update a denomination
- **DELETE** `/api/denominacion/{id}` - Delete a denomination

### Sparkling Wine

- **GET** `/api/espumoso` - Get all sparkling wines

### Pairing

- **GET** `/api/maridaje/{id}` - Get a pairing
- **GET** `/api/maridaje/color/{idColor}` - Get pairings by wine color
- **GET** `/api/maridaje/espumoso/{idEspumoso}` - Get pairings by sparkling wine

### Score

- **GET** `/api/puntuacion` - Get all wine scores

### Rating

- **GET** `/api/valoracion` - Get all ratings
- **POST** `/api/valoracion` - Create a wine rating
- **GET** `/api/valoracion/vino/{idVino}` - Get ratings for a specific wine
- **DELETE** `/api/valoracion/{id}` - Delete a rating

### Flavor

- **GET** `/api/sabor/{idColor}` - Get flavors by wine color

### Wine

- **GET** `/api/vino` - Get all wines
- **POST** `/api/vino` - Create a new wine
- **GET** `/api/vino/ranking` - Get wines ranked by score
- **GET** `/api/vino/color/{colorId}` - Get wines by color
- **GET** `/api/vino/espumoso/{espumosoId}` - Get wines by sparkling type
- **GET** `/api/vino/{id}` - Get a specific wine
- **PUT** `/api/vino/{id}` - Update a wine
- **DELETE** `/api/vino/{id}` - Delete a wine
