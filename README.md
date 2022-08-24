# Lumen Docker Employees
## Uma API desenvolvida com Docker e tudo que o Lumen pode oferecer.

# Imagens do Docker
* Nginx
* PHP 8.*
* MySql

# Recursos utilizados do Lumen
* Migrations
* Factory
* Seeders
* Eloquent
* Validator
* Artisan Commands
* Lumen/Laravel Passport
* TDD com PHPUnit

### Instalação
* Docker (Também recomendo o Docker Desktop 😉)
* Ferramenta de acesso a um banco de dados MySql (Que tal Workbench? 😁)
* Composer Manager Package

### Como instalar?
```sh
git clone https://github.com/luigi-raynel-dev/lumen_docker_employees.git
```

> Vamos ao que interessa... 🏃 
# São apenas 4 passos pra configurarmos nossa api

<br>

### Passo 1 - Precisamos subir nossas imagens por um container do Docker

#### Agora na pasta do projeto vamos subir as imagens com o comando:
```sh
docker compose up --build -d
```

<br>

### Passso 2 - Instalando as dependências do PHP
> Para isso rode o comando...
```sh
composer update
```

<br>

### Passso 3 - Rodando as Migrations
#### Vamos usar as migrations que definem toda nossa modelagem do banco de dados
> Para isso rode o comando...
```sh
docker exec -it php /var/www/html/artisan migrate
```

<br>

### Passso 4 - Semeando nossa base de dados com o Faker PHP

```sh
docker exec -it php /var/www/html/artisan db:seed --class=DatabaseSeeder
```

<br>

> Show! Agora você tem a aplicação rodando e com dados já preparados

### Tudo o que basta fazer para começar a usar o sistema é:
* Gerar uma chave secreta e um client id com o Laravel Passport
* Cadastrar um usuário e fazer um login com ele
* Utilizar o access_token para fazer a requisições para testar toda a aplicação

