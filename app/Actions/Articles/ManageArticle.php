<?php

namespace App\Actions\Articles;

use App\Http\Requests\Article\ArticleRequest;
use App\Models\Article;
use App\Models\User;

class ManageArticle
{
    public function execute(User $user, $data, Article $article = null): Article
    {
        $article = $this->createOrUpdateArticle($user, $data, $article);

        return $article;
    }

    private function createOrUpdateArticle(User $user, $data, Article $article = null): Article
    {
        $article = Article::updateOrCreate(
            [
                'id' => $article->id ?? null,
                'user_id' => $user->id
            ],
            [
                'user_id' => $user->id,
                ...$data
            ]
        );

        return $article;
    }
}
