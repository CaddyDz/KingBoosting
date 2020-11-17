<?php

use App\Champion;
use App\ChampionTag;
use App\ChampionRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ChampionsTableSeeder extends Seeder
{
	use Champions;
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Delete old seeded files
		Storage::disk('public')->deleteDirectory('champions');
		foreach ($this->champions as $champion) {
			$this->copyFile($champion);
			$this->createModel($champion);
		}
	}

	public function copyFile($champion)
	{
		// Store image from public path
		$from = 'public/img/champions/' . $champion['image'];
		$to = 'storage/app/public/champions/' . $champion['image'];
		Storage::disk('seed')->copy($from, $to);
	}

	public function createModel($champion)
	{
		$model = Champion::create([
			'name' => $champion['name'],
			'identifier' => $champion['identifier'],
			'image' => '/champions/' . $champion['image'],
			'key' => $champion['key']
		]);
		$this->attachTags($model, $champion['tags']);
		$this->attachRoles($model, $champion['roles']);
	}

	public function attachTags($model, $tags)
	{
		foreach ($tags as $tag) {
			$tagId = ChampionTag::where('name', $tag)->first();
			$model->tags()->attach($tagId);
		}
	}

	public function attachRoles($model, $roles)
	{
		foreach ($roles as $role) {
			$roleId = ChampionRole::where('name', $role)->first();
			$model->roles()->attach($roleId);
		}
	}
}
