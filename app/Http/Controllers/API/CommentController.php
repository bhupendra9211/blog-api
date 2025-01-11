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
        try {
            return response()->json($post->comments()->with('user')->get(), 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An API failed while listing the comments.',
            ], 500);
        }
    }

    public function store(Request $request, Post $post)
    {
        try {
            $request->validate(['content' => 'required|string']);

            $comment = $post->comments()->create([
                'content' => $request->content,
                'user_id' => $request->user()->id,
            ]);

            return response()->json($comment, 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An API failed while creating the comments.',
            ], 500);
        }
    }

    public function show(Comment $comment)
    {
        try {
            return response()->json($comment->load('user'), 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An API failed while displayiny the comments.',
            ], 500);
        }
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        try {
            $request->validate(['content' => 'required|string']);
            $comment->update(['content' => $request->content]);
            return response()->json($comment, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An API failed while updating the comment.',
            ], 500);
        }
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        try {
            $comment->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An API failed while deleting the comment.',
            ], 500);
        }
    }
}

