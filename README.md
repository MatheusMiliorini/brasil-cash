
# Teste Back-End Brasil Cash

Este projeto criado em Laravel tem como objetivo o desenvolvimento de uma API de pagamentos.


## Instalação

Para rodar o projeto, clone o repositório e instale as dependências com ```composer install```.

Após isso, copie o arquivo ```.env.example``` para ```.env```. Feito isto, rode o comando ```php artisan key:generate``` para preencher a chave no arquivo ambiente.

O projeto depende de uma conexão com o banco de dados para funcionar, então realize a configuração no bloco abaixo, dentro do arquivo ```.env```.

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=brasil_cash
DB_USERNAME=postgres
DB_PASSWORD=<senha>
```

No meu caso, utilizei o banco PostgreSQL, porém é possível utilizar MySQL sem alteração no código.

Com o banco configurado, rode o comando ```php artisan migrate``` para criar as tabelas do banco.

Para o uso de filas é necessária a instalação do Redis, e também a configuração no ```.env```. A seguinte configuração deve ser alterada:
```
QUEUE_CONNECTION=redis
```

Com isso, deve ser possível rodar o projeto utilizando o comando ```php artisan serve```.
## Rodando os testes

Para rodar os testes, rode o seguinte comando

```
php artisan test
```

Para obter o coverage do projeto, basta utilizar a flag ```--coverage```:
```
php artisan test --coverage
```
Obs: Talvez seja necessário alterar a configuração do Xdebug para utilizar a função de coverage.