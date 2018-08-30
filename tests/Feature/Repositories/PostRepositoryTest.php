<?php

namespace App\Feature\Repositories;

use App\Business\Entities\Post;
use App\Business\Repositories\PostRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

}
