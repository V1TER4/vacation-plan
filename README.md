# Planejamento de Viagem #

Planeja suas viagens em familia ou com amigos, onde você pode determinar o dia da viagem, local e quem irá junto com você.

### By Victor Castilho ####

# Descrição #

Essa aplicação serve para você planejar suas viagens. Voce poderá definir para onde estaram indo, quando irão e quem irá junto com você nas sua viagem cheia de aventura ou de descanso.

# Instruções de instalação utilizando WSL #
* Instale o php8.3-fpm e suas dependencias
* Clone o repósitorio na pasta /var/www/html/
* Instale as dependencias do projeto utilizando o comando "composer install"
* Instale o MySQL Workbench para que seja possível gravar as informações.
* Acesse a pasta /etc/nginx/sites-avalible e crie o arquivo de configuração:
    server {
        listen 80;
        listen [::]:80;
        root /var/www/html/vacation-plan/public;
        index index.php index.html index.html;
        server_name dev.vacation-plan;
        location / {
            try_files $uri $uri/ /index.php$is_args$args;
        }
        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        }
    }
* Vá para a pasta /etc/nginx/sites-enable e rode o comando sudo:
    ln -s /etc/nginx/sites-available/vacation-plan /etc/nginx/sites-enabled/vacation-plan
* No Windows vá em C:\Windows\System32\drivers\etc , edite o arquivo hosts adicionando esta linha:
    127.0.0.1       dev.vacation-plan
* Inicie os serviços do php, mysql e nginx
* Na pasta do projeto execute os comandos artisan migrate, db:seed e jwt:secret
* Para testar o sistema rode o comando php artisan test

## Linguagens/Tecnologia utilizadas
PHP 8.3
Laravel 8.83
MySQL

## Documentation
https://documenter.getpostman.com/view/19647757/2sA3s3GqfW
