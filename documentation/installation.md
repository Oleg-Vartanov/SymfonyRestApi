Installation
------------

### 1) Download a project.

### 2) Install dependencies

Use a [npm](https://www.npmjs.com/) package manager. Run the command in a project's root directory.

```
npm install
```

### 3) Setup docker

Create `/docker/.env` from `/docker/.env-example`

Fill database values there.

### 4) Setup environment variables

Create `.env` file in a root directory, using `.env-example` file.

Fill `APP_SECRET` and database values there.

### 5) Run docker

```
SymfonyRestApi/docker$ docker-compose up -d
```

### 5) Setup JWT keys

Enter a container's terminal
```
SymfonyRestApi/docker$ docker exec -it sra_php bash
```

Create ssl keys
```
php bin/console lexik:jwt:generate-keypair
```

### 5) Run migrations

Enter a container's terminal
```
SymfonyRestApi/docker$ docker exec -it sra_php bash
```

Run migrations
```
php bin/console doctrine:migrations:migrate
```

### 6) Create a default admin user with a command

```
php bin/console app:create-admin your_email your_password
```