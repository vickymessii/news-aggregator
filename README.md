
# News Aggregator API

The News Aggregator API is a Laravel-based project that allows users to collect, aggregate, and interact with news articles from multiple sources. It is built using Docker to simplify the development and deployment process.

## Project Setup

### Requirements

Before setting up the project, ensure you have the following tools installed:

- **Docker**: For containerizing and running the application.
- **Composer**: For managing PHP dependencies.

### Installation

Follow these steps to get the project up and running locally:

1. **Clone the repository**:
   Clone the project repository to your local machine and navigate to the project directory:
   ```bash
   git clone https://github.com/vickymessii/news-aggregator.git
   cd news-aggregator

2. Copy the `.env.example` file to `.env` and set your environment variables.

3. Run the application in Docker:
   ```bash
   docker-compose up -d
   ```

4. Run migrations:
   ```bash
   docker-compose exec app php artisan migrate
   ```
5. Generate API docs
  ```base 
  docker-compose exec app php artisan l5-swagger:generate
  ```
  ### API Documentation

    http://127.0.0.1:8000/api/documentation


