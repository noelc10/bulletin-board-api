<?php

namespace App\Http\Controllers\Article;

use App\Actions\Articles\GetArticles;
use App\Actions\Articles\ManageArticle;
use App\Enums\ErrorCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleRequest;
use App\Http\Resources\Article\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Create a new ArticleController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
       return (new GetArticles())->execute();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleRequest  $request
     *
     * @return ArticleResource
     */
    public function store(ArticleRequest $request): ArticleResource
    {
        $article = DB::transaction(function () use ($request) {
            return (new ManageArticle())->execute(
                user: auth()->user(),
                data: $request->all()
            );
        });

        return new ArticleResource($article);
    }

    /**
     * Display the specified resource.
     *
     * @return ArticleResource
     */
    public function show(Article $article): ArticleResource
    {
        $data = $article->with(['upvotes', 'comments'])->first();

        return new ArticleResource($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticleRequest  $request
     * @param Article         $article
     *
     * @return ArticleResource
     */
    public function update(ArticleRequest $request, Article $article): ArticleResource
    {
        if ($article->user_id !== auth()->user()->id) {
            return $this->respondWithError(
                ErrorCodes::Unauthorized->value,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $article = DB::transaction(function () use ($request, $article) {
            return (new ManageArticle())->execute(
                user: auth()->user(),
                data: $request->all(),
                article: $article
            );
        });

        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ArticleRequest $request
     */
    public function destroy(Article $article)
    {
        $user = auth()->user();

        if ($article->user_id !== $user->id) {
            return $this->respondWithError(
                ErrorCodes::Unauthorized->value,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $article
            ->where('user_id', $user->id)
            ->delete();

        return $this->respondWithEmptyData();
    }
}
