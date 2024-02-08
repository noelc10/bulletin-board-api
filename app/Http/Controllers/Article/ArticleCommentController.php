<?php

namespace App\Http\Controllers\Article;

use App\Actions\Articles\Comments\ManageComment;
use App\Enums\ErrorCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleCommentRequest;
use App\Http\Resources\Article\ArticleCommentResource;
use App\Models\Article;
use App\Models\ArticleComment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ArticleCommentController extends Controller
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
     * @return ArticleCommentResource
     */
    public function index(): ArticleCommentResource
    {
        $comments = ArticleComment::with(['article', 'user'])->get();

        return new ArticleCommentResource($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Article $article, ArticleCommentRequest $request): ArticleCommentResource
    {
        $article = DB::transaction(function () use ($request, $article) {
            return (new ManageComment())->execute(
                article: $article,
                user: auth()->user(),
                data: $request->all()
            );
        });

        return new ArticleCommentResource($article);
    }

    /**
     * Display the specified resource.
     *
     * @param ArticleComment $request
     *
     * @return ArticleCommentResource
     */
    public function show(Article $article, ArticleComment $comment): ArticleCommentResource
    {
        $data = $comment
            ->with(['article', 'user'])
            ->where('article_id', $article->id)
            ->first();

        return new ArticleCommentResource($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Article $article,
        ArticleCommentRequest $request,
        ArticleComment $comment
    ): ArticleCommentResource
    {
        if (auth()->user()->id !== $comment->user_id) {
            return $this->respondWithError(
                ErrorCodes::InvalidCredentials->value,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $article = DB::transaction(function () use ($request, $comment, $article) {
            return (new ManageComment())->execute(
                comment: $comment,
                article: $article,
                user: auth()->user(),
                data: $request->all()
            );
        });

        return new ArticleCommentResource($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @params ArticleComment $comment
     */
    public function destroy(Article $article, ArticleComment $comment)
    {
        $user = auth()->user();

        if ($comment->user_id !== $user->id) {
            return $this->respondWithError(
                ErrorCodes::Unauthorized->value,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $comment
            ->where([
                ['article_id', $article->id],
                ['user_id', $user->id]
            ])
            ->delete();

        return $this->respondWithEmptyData();
    }
}
