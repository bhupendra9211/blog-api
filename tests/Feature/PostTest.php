<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;

class PostTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    // public function test_example(): void
    // {
    //     $response = $this->get('/api');

    //     $response->assertStatus(200);
    // }
    public function test_user_can_create_post()
    {
        $user = User::factory()->create();
    
        $this->actingAs($user, 'sanctum')
            ->postJson('/api/posts', [
                'title' => 'Sample Post',
                'content' => 'This is a sample post content.',
            ])
            ->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Post Created Sucessfully',
                'data' => [
                    'title' => 'Sample Post',
                ],
            ]);
    }
    

    public function test_user_can_update_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();
    
        $this->actingAs($user, 'sanctum')
            ->putJson("/api/posts/{$post->id}", ['title' => 'Updated Title'])
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Post Updated Sucessfully',
                'data' => [
                    'title' => 'Updated Title',
                ],
            ]);
    }
    

    public function test_user_cannot_update_others_post()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->for($otherUser)->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/posts/{$post->id}", ['title' => 'Updated Title'])
            ->assertStatus(403);
    }
}
