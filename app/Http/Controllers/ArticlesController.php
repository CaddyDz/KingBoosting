<?php

namespace App\Http\Controllers;

use App\Article;

class ArticlesController extends Controller
{
    /**
     * Display the specified article.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return $article;
    }

    public function getFirstArticle()
    {
        return Article::select('id', 'title', 'excerpt', 'image')->first();
    }

    public function getArticlesCount()
    {
        return Article::count();
    }
}
