<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class CommentController extends Controller
{
    use AuthorizesRequests;

    public function index(Post $post)
    {
        return response()->json($post->comments()->with('user')->get(), 200);
    }

    public function store(Request $request, Post $post)
    {
        $request->validate(['content' => 'required|string']);

        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($comment, 201);
    }

    public function show(Comment $comment)
    {
        return response()->json($comment->load('user'), 200);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate(['content' => 'required|string']);

        $comment->update(['content' => $request->content]);

        return response()->json($comment, 200);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(null, 204);
    }
}

