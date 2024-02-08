<?php

namespace App\Actions\Articles\Upvotes;

use App\Models\Article;
use App\Models\ArticleUpvote;

class ToggleArticleUpvote
{
    public function execute(Article $article)
    {
        $articleUpvote = ArticleUpvote::where('article_id', $article->id)->where('user_id', auth()->user()->id);

        if ($articleUpvote->isAuthUserUpvoted()) {
            return $articleUpvote->delete();
        } else {
            return ArticleUpvote::create([
                'article_id' => $article->id,
                'user_id' => auth()->user()->id,
            ])->save();
        }
    }
}
