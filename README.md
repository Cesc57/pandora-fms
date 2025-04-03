# Exercise 1 Score Decoder

This project reads a CSV file containing user data, their numeric system, and encoded scores. The `decoder.php` script decodes the scores and sorts them from highest to lowest.

## Project Files

- **decoder.php**: PHP script that decodes the scores.
- **assets/encryptedScore.csv**: CSV file that contains the encoded scores.
- **Dockerfile** (if you are using Docker to run the project).

## Instructions

### Option 1: Running with Docker (Recommended)

1. Clone this repository or download the project files.

2. If you have Docker installed, simply start the container with the following command:

```bash
  docker-compose up -d --build
```
Once the container is running, you have two ways to execute the script:

Option 1: Access via the local server URL
Open a browser and navigate to:
```bash
  http://localhost:8080/decoder.php
```
Option 2: Execute the script from the bash inside the container
First, access the bash inside the container:

```bash
    docker-compose exec web bash
```
Then, run the decoder.php script:

```bash
    php /var/www/html/decoder.php
```

# EASY WAY: Makefile

If you prefer an easier way to manage the containers and execute the script,  
you can use the provided Makefile. Here are the available commands:

Start containers in detached mode:
```bash
    make up
```

Run the decoder.php script:
```bash
  make decoder
```

Stop and remove containers:
```bash
    make down
```

# Exercise 2 Appointment register

```bash
   docker cp www/database/create_tables.sql mysql_db:create_tables.sql
```

```bash
  docker exec -it mysql_db bash
```

```bash
  mysql -u root -p
```

```
rootpass
```

```bash
  source /create_tables.sql;
```

##MAKEFILE

```bash
  make init-db
```

http://localhost:8080/