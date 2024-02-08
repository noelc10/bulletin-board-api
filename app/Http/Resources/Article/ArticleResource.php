<?php

namespace App\Http\Resources\Article;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'user' => User::where('id', $this->user_id)->first(),
            'upvotes' => new ArticleUpvoteResource($this->whenLoaded('upvotes')),
            'comments' => new ArticleCommentResource($this->whenLoaded('comments')),
        ];
    }
}
