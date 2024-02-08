<?php

namespace App\Actions\Articles;

use App\Http\Resources\Article\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GetArticles
{
    public function execute(): AnonymousResourceCollection
    {
        $articles = Article
            ::with(['upvotes', 'comments'])
            ->orderBy('created_at')
            ->get();

        return ArticleResource::collection($articles);
    }
}
