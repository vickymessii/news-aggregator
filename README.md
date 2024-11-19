
#  News Aggregator API
## Project Setup

### Requirements
- Docker
- Composer

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/vickymessii/news-aggregator.git
   cd news-aggregator
   ```

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


