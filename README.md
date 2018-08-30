# Mod Laravel

Laravel com algumas configurações pré definidas.

  - Commands
  - Filters
  - Repositories

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

Uma das rotinas mais comuns de um desenvolvedor é a criação de condicionais que representam os filtros realizados pelo usuário em formulários, para buscar suas informações. Essa atividade pode se tornar um pouco repetitiva e muitas vezes, acaba deixando o código "feio", quando há muitas condicionais para filtrar.
Para resolver esse problema foi desenvolvido uma classe Filter que por meio de atributos é métodos, e possível realiza os filtros necessários.

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
    * @obesrvation Caso o método com o nome do atributo não seja definido,
    * por padrão será feito um like com o value
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
    * de todos os registros criado hoje, esse método sera invocado em todas
    * as requisições, independente dos valores enviados por requisição
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

**Repositories Pattern**

Para melhor organizaçãod o código fonte, essa versão do laravel está utilizando o padrão de projeto repositórios, o qual de forma abstrata representa a ideia de coleção de registros pertencentes a um model em específico. As funções disponíveis no repositórito configurado são as seguintes:

```php
    /**
    * Retorna todos os registros
    *
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function all();

    /**
    * Retorna todos os registros de forma decrescente
    * com base no created_at
    *
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function latest();

    /**
    * Retorna um registro com base no id
    *
    * @param string $id
    * @return null | Illuminate\Database\Eloquent\Model
    */
    public function get($id);

    /**
    * Retorna o primeiro registro
    * encontrado
    *
    * @return null | Illuminate\Database\Eloquent\Model
    */
    public function first();

    /**
    * Retorna todos os registros ordenados
    *
    * @param $key
    * @param $type
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function ordered($key, $type);

    /**
    * Retorna o último registro
    * encontrado
    *
    * @return null | Illuminate\Database\Eloquent\Model
    */
    public function last();

    /**
    * Retorna todos os registros que atenderem
    * a condição passada como parâmetro
    *
    * @param string $key
    * @param string $value
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function getWhere($key, $value);

    /**
    * Retorna o primeiro registro que for encontrado
    * que atende as condições passadas como parâmetro
    *
    * @param string $key
    * @param string $value
    * @return null | Illuminate\Database\Eloquent\Model
    */
    public function getWhereFirst($key, $value);

    /**
    * Retorna todos os registros que atenderem
    * as condições passadas como parâmetro
    *
    * @param array $where
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function getWhereAt(array $where);

    /**
    * Retorna todos os registros que atenderem
    * as condições passadas como parâmetro
    *
    * @param array $where
    * @return null | Illuminate\Database\Eloquent\Model
    */
    public function getWhereAtFirst(array $where);

    /**
    * Realiza a coleta dos dados de forma paginada
    *
    * @param int $paginate
    * @return Illuminate\Pagination\LengthAwarePaginator
    */
    public function paginate($paginate = 10);

    /**
    * Realiza a paginação e configura a ordenação
    * da coleção a ser retornada
    *
    * @param int $paginate
    * @param array $order
    * @return Illuminate\Pagination\LengthAwarePaginator
    */
    public function paginateOrder($paginate = 10, array $order);

    /**
    * Realiza uma consulta as registros com base
    * em um filtro específico
    *
    * @param App\Filters\Filter $filter
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function withFilter(Filter $filter);
```

Todos os repositórios criados devem extender de:

> App\Business\Repositories\AppRepository

****Exemplo****:

```php
    class PostRepository extends AppRepository
    {

        /**
        * @param Post $post
        */
        public function __construct(Post $post)
        {
            $this->model = $post;
        }

    }

```


**QueryLoggingProvider**

Provedor que preenche um documento com todas as queries realizadas pela aplicação. Configurado para funcionar apenas em ambiente local. O documento é criado dentro de storage/logs no padrão queries-year-mm-dd.log.

> storage/logs/queries-2018-08-30.log
