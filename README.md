# Mod Laravel

Laravel com algumas configurações pré definidas.

  - Commands

### Available Commands

O manual de comandos do Laravel encontra-se em [Artisan Console](https://laravel.com/docs/5.6/artisan).

**Install Database**

```sh
$ php artisan database:up
```

Processos:
  - Criação do Banco de Dados
  - Migração das Tabelas
  - Execução dos Seeders

**Create new User**

```sh
$ php artisan users:create
```

Cria um novo usuário para a aplicação, possibilita a escolha das informações de forma interativa.
