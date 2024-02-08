<?php

namespace App\Http\Controllers\Article;

use App\Actions\Articles\Upvotes\ToggleArticleUpvote;
use App\Http\Controllers\Controller;
use App\Http\Resources\Article\ArticleUpvoteResource;
use App\Models\Article;
use App\Models\ArticleUpvote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleUpvoteController extends Controller
{
    /**
     * Create a new ArticleUpvoteController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return ArticleUpvoteResource
     */
    public function index(): AnonymousResourceCollection
    {
        $upvotes = ArticleUpvote::all();

        return ArticleUpvoteResource::collection($upvotes);
    }

    /**
     * Toggle selected article upvote data to the database
     *
     * @param  Article               $article
     * @return ArticleUpvoteResource
     */
    public function toggleArticleUpvote(Article $article): JsonResponse
    {
        $articleUpvote = (new ToggleArticleUpvote)->execute($article);

        if (is_bool($articleUpvote)) {
            return response()->json([
                'is_toggled' => true,
                'message' => 'Successfully toggle article upvote!'
            ], 200);
        }

        return response()->json([
            'is_toggled' => false,
            'message' => 'Successfully untoggle article upvote!'
        ], 200);
    }
}
