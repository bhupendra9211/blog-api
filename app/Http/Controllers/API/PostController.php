<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = Post::with('user')->latest()->get();
            $result = array('status' => true, 'message' => 'Post listed Sucessfully', 'data' => $posts);
            return response()->json($result, 200); 
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An API failed while listing the post.',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png',
            ]);
    
            $data = $request->only(['title', 'content']);
            $data['user_id'] = $request->user()->id;
    
            if ($request->hasFile('image')) {
                $data['image_path'] = $request->file('image')->store('images', 'public');
            }
    
            $post = Post::create($data);
            $result = array('status' => true, 'message' => 'Post Created Sucessfully', 'data' => $post);
            return response()->json($result, 201); 

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An API failed while creating the post.',
            ], 500);
        }
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        try {
            return response()->json($post->load('user', 'comments'), 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An API failed while displaying the post.',
            ], 500);
        }
    
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        try {
            $request->validate([
                'title' => 'string|max:255',
                'content' => 'string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg',
            ]);
    
            if ($request->hasFile('image')) {
                Storage::disk('public')->delete($post->image_path);
                $post->image_path = $request->file('image')->store('images', 'public');
            }
    
            $post->update($request->only(['title', 'content']));
            $result = array('status' => true, 'message' => 'Post Updated Sucessfully', 'data' => $post);
            return response()->json($result, 200); 

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An API failed while updating the post.',
            ], 500);
        }
    


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        try {    
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
    
            $post->delete();
    
            $result = array('status' => true, 'message' => 'Post Deleted Sucessfully');
            return response()->json($result, 204); 
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An API failed while deleting the post.',
            ], 500);
        }
    }

    public function uploadImage(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png|max:2048', 
            ]);
        
            $path = $request->file('image')->store('post-images', 'public');
        
            $post->update(['image_path' => $path]);
        
            return response()->json([
                'status' => true,
                'message' => 'Image uploaded successfully.',
                'data' => $post,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An API failed while uploading the image.',
            ], 500);
        }

    }
public function deleteImage(Post $post)
    {
        $this->authorize('update', $post);
        try {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
    
                $post->update(['image_path' => null]);
    
                return response()->json([
                    'status' => true,
                    'message' => 'Image deleted successfully.',
                ], 200);
            }
    
            return response()->json([
                'status' => false,
                'message' => 'No image to delete.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An API failed while deleting the image.',
            ], 500);
        }
    }


}
