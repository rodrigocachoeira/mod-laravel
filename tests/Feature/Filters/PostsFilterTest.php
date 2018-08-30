<?php

namespace App\Feature\Filters;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\TodaysPostFilter;
use App\Filters\PostFilter;
use Tests\TestCase;
use Carbon\Carbon;
use App\Post;

class PostsFilterTest extends TestCase
{

	use RefreshDatabase;

	/** @test */
	public function the_post_filter_class_must_have_the_scope_method()
	{
		$postFilter = (new Post())->filter(app()->make(PostFilter::class));
		$this->assertTrue($postFilter instanceof Builder);
	}

	/** @test */
	public function the_title_filter_must_be_configured()
	{
		$post = create(Post::class); //Post que deve ser filtrado

		request()->offsetSet('title', $post->title); //define o filtro na requisição
		factory(Post::class, 10)->create(); //Vários Posts aleatórios

		$model = new Post();
		$records = $model->filter(app()->make(PostFilter::class))
			->get();

		$this->assertCount(1, $records);
	}

	/** @test */
	public function it_should_be_possible_to_define_fixed_filters()
	{
		$post = create(Post::class); //Post que deve ser filtrado

		factory(Post::class, 10)
			->create(['created_at' => Carbon::yesterday()]); //Vários Posts aleatórios

		$model = new Post();
		$records = $model->filter(app()->make(TodaysPostFilter::class))
			->get();

		$this->assertCount(1, $records);
	}

	/** @test */
	public function it_should_be_possible_sort_the_records()
	{
		$second = create(Post::class, ['title' => 'Exemplo 2']);
		$first = create(Post::class, ['title' => 'Exemplo 1']);
		$third = create(Post::class, ['title' => 'Exemplo 3']);

		$model = new Post();
		$records = $model->filter(app()->make(PostFilter::class))
			->get();

		$this->assertEquals($first->title, $records->get(0)->title);
		$this->assertEquals($second->title, $records->get(1)->title);
		$this->assertEquals($third->title, $records->get(2)->title);
	}

	/** @test */
	public function it_should_be_possible_to_define_columns_to_be_returned()
	{
		factory(Post::class, 50)->create();

		$model = new Post();
		$records = $model->filter(app()->make(PostFilter::class))
			->get();

		$this->assertTrue(! is_null($records->first()->title));
		$this->assertTrue(is_null($records->first()->body));
	}
}
