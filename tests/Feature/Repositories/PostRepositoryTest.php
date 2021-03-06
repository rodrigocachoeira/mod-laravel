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

	/** @test */
	public function the_respository_should_record_information()
	{
		$currentSize = $this->repository->all()->count();
		$dbCount = count(DB::select('select * from posts'));

		$this->assertEquals($dbCount, $currentSize);

		$this->repository->save($post = make(Post::class)->toArray());

		$dbCount = count(DB::select('select * from posts'));
		$this->assertEquals($dbCount, $this->repository->all()->count());

		$this->assertEquals($post['title'],
			$this->repository->getWhereFirst('title', $post['title'])->title);
	}

	/** @test */
	public function it_should_be_possible_update_records()
	{
		factory(Post::class, 50)->create();
		$records = $this->repository->all();

		$record = $records->get(random_int(0, $records->count() - 1));

		$this->assertEquals($this->repository->getWhereFirst('title', $record->title)->title, $record->title);

		$newTitle = make(Post::class)->title;
		$this->repository->update($record->id, ['title' => $newTitle]);

		$this->assertTrue($this->repository->getWhere('title',
			$record->title)->isEmpty());

		$this->assertEquals($this->repository->getWhereFirst('title', $newTitle)->title, $newTitle);
	}

	/** @test */
	public function it_should_be_possible_delete_records_by_id()
	{
		factory(Post::class, 50)->create();
		$recordsBefore = $this->repository->all();

		$this->repository->delete($recordsBefore->first()->id);
		$recordsAfter = $this->repository->all();

		$this->assertEquals($recordsBefore->count() -1 , $recordsAfter->count());
	}

	/** @test */
	public function it_should_be_possible_to_update_records_by_key_value()
	{
		$oldSubtitle = make(Post::class)->subtitle;
		$newSubtitle = make(Post::class)->subtitle;

		factory(Post::class, 2)->create();
		factory(Post::class, 5)->create(['subtitle' => $oldSubtitle]);

		$this->assertCount(5, $this->repository
				->getWhere('subtitle', $oldSubtitle)->toArray());

		$this->repository->updateAt(['subtitle' => $oldSubtitle],
			['subtitle' => $newSubtitle]);

		$this->assertCount(0, $this->repository
			->getWhere('subtitle', $oldSubtitle)->toArray());

		$this->assertCount(5, $this->repository
			->getWhere('subtitle', $newSubtitle)->toArray());
	}

	/** @test */
	public function it_should_be_possible_to_delete_records_by_key_value()
	{
		$subtitle = make(Post::class)->subtitle;

		factory(Post::class, 2)->create();
		factory(Post::class, 5)->create(['subtitle' => $subtitle]);

		$this->assertCount(5, $this->repository
				->getWhere('subtitle', $subtitle)->toArray());

		$this->repository->deleteAt(['subtitle' => $subtitle]);

		$this->assertCount(0, $this->repository
			->getWhere('subtitle', $subtitle)->toArray());
	}

}
