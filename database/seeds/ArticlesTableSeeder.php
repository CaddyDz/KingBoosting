<?php

use App\Article;
use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
	private $images = [
		'Champion-Balance-blog.png',
		'Mistakes-blog.jpg',
		'Tarzaned-The-booster-blog.png',
	];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Delete old seeded files
		Storage::disk('public')->deleteDirectory('articles');
		foreach ($this->images as $image) {
			$this->copyImage($image);
		}
		factory(Article::class, 20)->create();
	}

	public function copyImage($image)
	{
		// Store image from public path
		$from = 'public/img/blogPosts/' . $image;
		$to = 'storage/app/public/articles/' . $image;
		Storage::disk('seed')->copy($from, $to);
	}
}
