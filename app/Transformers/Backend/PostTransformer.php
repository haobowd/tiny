<?php

namespace App\Transformers\Backend;

use App\Models\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'post_content', 'category'];

    public function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'cover' => $post->cover,
            'cover_url' => image_url($post->cover),
            'category_id' => $post->category_id,
            'status' => $post->status,
            'type' => $post->type,
            'views_count' => $post->views_count,
            'template' => $post->template,
            // 'url' => $post->present()->getUrl(),
            'order' => $post->order,
            'top' => !is_null($post->top),
            'top_time' => is_null($post->top) ? null : $post->top->toDateTimeString(),
            'published_at' => $post->published_at->toDateTimeString(),
            'created_at' => $post->created_at->toDateTimeString(),
            'updated_at' => $post->updated_at->toDateTimeString()
        ];
    }

    public function includeUser(Post $post)
    {
        $user = $post->user;
        if (is_null($user)) {
            return $this->null();
        } else {
            return $this->item($user, new UserTransformer());
        }
    }

    public function includePostContent(Post $post)
    {
        $content = $post->postContent;
        if (is_null($content)) {
            return $this->null();
        } else {
            return $this->item($content, new PostContentTransformer());
        }
    }

    public function includeCategory(Post $post)
    {
        $category = $post->category;
        if (is_null($category)) {
            return $this->null();
        }
        return $this->item($category, new CategoryTransformer());
    }
}
