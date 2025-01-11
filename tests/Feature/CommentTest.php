<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    use RefreshDatabase;

    public function test_user_can_add_comment_to_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/posts/{$post->id}/comments", ['content' => 'Test Comment'])
            ->assertStatus(201)
            ->assertJson(['content' => 'Test Comment']);
    }

    public function test_user_can_view_comments_on_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        
        Comment::factory()->count(3)->for($post)->create();
    
        $this->actingAs($user, 'sanctum');
    
        $response = $this->getJson("/api/posts/{$post->id}/comments");
    
        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id', 'content', 'user_id', 'post_id', 'created_at', 'updated_at', 'user']
            ]);
    }
    public function test_user_can_update_their_own_comment()
    {
    $user = User::factory()->create();

    $comment = Comment::factory()->for($user)->create();
    $updatedContent = 'Updated comment content.';

    $response = $this->actingAs($user, 'sanctum')
        ->putJson("/api/comments/{$comment->id}", ['content' => $updatedContent]);

    $response->assertStatus(200)
        ->assertJson([
            'id' => $comment->id,
            'content' => $updatedContent,
        ]);

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'content' => $updatedContent,
    ]);
}

public function test_user_cannot_update_others_comment()
{
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $comment = Comment::factory()->for($otherUser)->create();

    $updatedContent = 'Unauthorized updated comment content.';

    $response = $this->actingAs($user, 'sanctum')
        ->putJson("/api/comments/{$comment->id}", ['content' => $updatedContent]);

    $response->assertStatus(403);

    $this->assertDatabaseMissing('comments', [
        'id' => $comment->id,
        'content' => $updatedContent,
    ]);
}
}
