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

### Important Packages

Para melhorar o desenvolvimento do programador, alguns pacotes auxiliares foram
desenvolvidos para que a experiência de desenvolvimento com problemas reais
fossem resolvidas com mais facilidade.

**Filters**

Uma das rotinas mais comuns de um desenvolvedor é a criação de condicionais que representam os filtros realizadas pelo usuário em formulários, para buscar suas informações. Essa atividade pode se tornar um pouco repetitiva e muitas vezes, deixando o código "feio", quando há muitas condicionais param serem filtradas.
Para que resolver esse problema foi desenvolvido uma classe Filter que por meio de atributos é métodos, é possível realizar os filtros necessários.

```php
namespace App\Filters;

use Carbon\Carbon;

/**
* Classe de configuração de um filtro aplicável ao model
* de Posts
*
* @obs Classe inspirada em uma série de vídeos disponibilizada pelo
* site https://laracasts.com/
*
* @author Rodrigo Cachoeira
* @package App\Filters
* @version 2.0
*/
class PostFilter extends Filter
{

    /**
    * keys da requisição (request()->all()) para filtrar
    *
    * @var array
    */
    protected $filters = [
        'title'
    ];

    /**
    * filtros que serão aplicados independente
    * do que aconteça
    *
    * @var array
    */
    protected $fixed = [
        'today'
    ];

    /**
    * Ordenação dos registros (não precisam ser configurados)
    *
    * @var array
    */
    protected $orders = [
        'title' => 'ASC'
    ];

    /**
    * Colunas que serão retornadas na consulta (não precisam ser configurados)
    *
    * @var array
    */
    protected columns = [
        'title', 'body'
    ];

    /**
    * Todo atributo definido em $filters, deve
    * ser definido como método para realizar
    * as condicionais
    *
    * @obesrvation Caso o método com o nome do atributo não seja definido,      * por padrão será feito um like com o value
    *
    * @param string $value Valor enviado por requisição da key title
    * @return void
    */
    public function title($value)
    {
        $this->builder->where('title', $value);
    }

    /**
    * Método definido para o atributo $fixed que indica a coleta
    * de todos os registros criado hoje, esse método sera invocado em todas     * as requisições, independente dos valores enviados por requisição
    *
    * @return void
    */
    public function today()
    {
        $this->builder->whereDate('created_at',
            Carbon::today()->toDateString());
    }

}
```
