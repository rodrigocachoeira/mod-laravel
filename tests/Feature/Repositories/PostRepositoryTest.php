<?php

namespace App\Feature\Repositories;

use App\Business\Entities\Post;
use App\Business\Repositories\PostRepository;
use App\Filters\TodaysPostFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{

	use RefreshDatabase;

	private $repository;

	public function setUp()
	{
		parent::setUp();
		$this->repository = App()->make(PostRepository::class);
	}

	/** @test */
	public function the_repository_must_store_model()
	{
		$this->assertTrue(is_a($this->repository->model, Model::class));
	}

	/** @test */
	public function repository_should_be_return_all_records()
	{
		factory(Post::class, 50)->create();
		$dbSelect = DB::select('select * from posts');
		$repRecords = $this->repository->all();

		$this->assertEquals(count($dbSelect), $repRecords->count());
	}

	/** @test */
	public function repository_should_be_return_latests_records()
	{
		factory(Post::class, 50)->create();
		$dbSelect = DB::select('select * from posts order by created_at DESC');
		$repRecords = $this->repository->latest();

		$this->assertEquals(count($dbSelect), $repRecords->count());
		$this->assertEquals($dbSelect[0]->id, $repRecords->first()->id);
		$this->assertEquals($dbSelect[count($dbSelect) - 1]->id,
			$repRecords->last()->id);
	}

	/** @test */
	public function it_should_be_possible_to_take_a_record_only_by_index()
	{
		factory(Post::class, 50)->create();
		$found = create(Post::class);

		$dbSelect = DB::select('select * from posts where id = ' . $found->id);
		$repRecord = $this->repository->get($found->id);

		$this->assertEquals($dbSelect[0]->id, $repRecord->id);
	}

	/** @test */
	public function it_should_be_possible_get_first_record_found()
	{
		factory(Post::class, 50)->create();
		$dbSelect = DB::select('select * from posts limit 1 offset 0');
		$repFirst = $this->repository->first();

		$this->assertEquals($dbSelect[0]->id, $repFirst->id);
	}

	/** @test */
	public function it_should_be_possibel_get_all_records_with_order_by()
	{
		factory(Post::class, 50)->create();
		$dbSelect = DB::select('select * from posts order by title DESC');
		$repRecords = $this->repository->ordered('title', 'DESC');

		$this->assertEquals(count($dbSelect), $repRecords->count());
		$this->assertEquals($dbSelect[0]->id, $repRecords->first()->id);
	}

	/** @test */
	public function it_should_be_possible_get_last_record_found()
	{
		factory(Post::class, 50)->create();
		$dbSelect = DB::select('select * from posts order by id desc limit 1 offset 0');
		$repFirst = $this->repository->last();

		$this->assertEquals($dbSelect[0]->id, $repFirst->id);
	}

	/** @test */
	public function it_should_be_possible_to_get_records_by_key_and_value()
	{
		factory(Post::class, 50)->create();
		$post = create(Post::class);
		$dbSelect = DB::select('select * from posts where title = "'.$post->title.'"');
		$repRecord = $this->repository->getWhere('title', $post->title);

		$this->assertEquals($dbSelect[0]->title, $repRecord->first()->title);
	}

	/** @test */
	public function it_should_be_possible_to_get_first_element_by_key_value_search()
	{
		factory(Post::class, 50)->create();
		$post = create(Post::class);
		$dbSelect = DB::select('select * from posts where title = "'.$post->title.'"');
		$repRecord = $this->repository->getWhereFirst('title', $post->title);

		$this->assertEquals($dbSelect[0]->title, $repRecord->title);
	}

	/** @test */
	public function it_should_be_possible_to_get_records_by_multile_key_and_value()
	{
		factory(Post::class, 50)->create();
		$post = create(Post::class);

		$dbSelect = DB::select('select * from posts where title = "'.$post->title.'"
			and id = '.$post->id);
		$repRecord = $this->repository->getWhereAt([
			'title' => $post->title,
			'id' => $post->id
		]);

		$this->assertEquals($dbSelect[0]->id, $repRecord->first()->id);
	}

	/** @test */
	public function it_should_be_possible_to_get_one_record_by_multile_key_and_value()
	{
		factory(Post::class, 50)->create();
		$post = create(Post::class);

		$dbSelect = DB::select('select * from posts where title = "'.$post->title.'"
			and id = '.$post->id);
		$repRecord = $this->repository->getWhereAtFirst([
			'title' => $post->title,
			'id' => $post->id
		]);

		$this->assertEquals($dbSelect[0]->id, $repRecord->id);
	}

	/** @test */
	public function it_should_be_possible_to_paginate_records()
	{
		factory(Post::class, 50)->create();
		$paginate = $this->repository->paginate($perPage = 15);

		$this->assertTrue(is_a($paginate, LengthAwarePaginator::class));
		$this->assertEquals($perPage, $paginate->count());
	}

	/** @test */
	public function it_should_be_possible_to_paginate_records_and_sort_they()
	{
		factory(Post::class, 50)->create();
		$paginate = $this->repository->paginateOrder(10, [
			'title' => 'ASC'
		]);

		$this->assertEquals($this->repository->ordered('title', 'ASC')->first()->id,
			$paginate->get(0)->id);
	}

	/** @test */
	public function it_shoudl_be_possible_to_get_records_with_filter()
	{
		$post = create(Post::class); //Post que deve ser filtrado

		factory(Post::class, 10)
			->create(['created_at' => Carbon::yesterday()]); //Vários Posts aleatórios

		$model = new Post();
		$repRecords = $this->repository
			->withFilter(app()->make(TodaysPostFilter::class));

		$modalRecords = $model->filter(app()->make(TodaysPostFilter::class))
			->get();

		$this->assertEquals($repRecords->count(), $modalRecords->count());
	}

}
