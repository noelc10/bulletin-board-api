<?php

namespace App\Actions\Articles\Comments;

use App\Http\Requests\Article\ArticleRequest;
use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\User;

class ManageComment
{
    public function execute(User $user, Article $article, $data, ArticleComment $comment = null): ArticleComment
    {
        $comment = $this->createOrUpdateArticle($user, $article, $data, $comment);

        return $comment;
    }

    private function createOrUpdateArticle(
        User $user,
        Article $article,
        $data,
        ArticleComment $comment = null
    ): ArticleComment
    {
        $comment = ArticleComment::updateOrCreate(
            [
                'id' => $comment->id ?? null,
                'article_id' => $article->id,
                'user_id' => $user->id
            ],
            [
                'article_id' => $article->id,
                'user_id' => $user->id,
                ...$data
            ]
        );

        return $comment;
    }
}
