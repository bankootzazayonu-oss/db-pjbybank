<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\FavoriteReview;
use App\Models\Platform;
use App\Models\Review;
use App\Models\ReviewReply;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntityAlignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_entity_char_ids_and_relationships_work_correctly(): void
    {
        $user = User::create([
            'Username' => 'testuser',
            'Email' => 'testuser@example.com',
            'Password' => bcrypt('password'),
            'Role' => 'admin',
        ]);
        $this->assertMatchesRegularExpression('/^U\d{4}$/', $user->User_ID);

        $type = Type::create([
            'Genre_Name' => 'Action Sci-Fi',
        ]);
        $this->assertMatchesRegularExpression('/^G\d{2}$/', $type->Genre_ID);

        $platform = Platform::create([
            'Platform_Name' => 'Netflix',
            'Logo' => 'https://example.com/logo.png',
        ]);
        $this->assertMatchesRegularExpression('/^P\d{3}$/', $platform->Platform_ID);

        $movie = Activity::create([
            'Title' => 'Interstellar Test',
            'Original_Title' => 'Interstellar',
            'Release_Year' => 2014,
            'Duration_Hours' => 2.8,
            'TMDB_ID' => 157336,
            'Genre_ID' => $type->Genre_ID,
            'Submitted_By' => $user->User_ID,
            'Approval_Status' => 'approved',
            'Poster' => 'https://example.com/poster.jpg',
            'Description' => 'A team of explorers travel through a wormhole in space.',
        ]);
        $this->assertMatchesRegularExpression('/^M\d{5}$/', $movie->Movie_ID);

        $movie->platforms()->attach($platform->Platform_ID);
        $this->assertTrue($movie->platforms->contains($platform->Platform_ID));

        $review = Review::create([
            'User_ID' => $user->User_ID,
            'Movie_ID' => $movie->Movie_ID,
            'Review_Text' => 'A masterpiece of sci-fi cinema.',
            'Score' => 9.5,
            'Spoiler' => 0,
        ]);
        $this->assertMatchesRegularExpression('/^REV\d{7}$/', $review->Review_ID);

        $reply = ReviewReply::create([
            'Review_ID' => $review->Review_ID,
            'User_ID' => $user->User_ID,
            'Message' => 'Totally agree with this review!',
        ]);
        $this->assertMatchesRegularExpression('/^REP\d{7}$/', $reply->Reply_ID);

        $collection = Collection::create([
            'User_ID' => $user->User_ID,
            'Tier_Title' => 'Top Sci-Fi Picks',
            'Tier_Labels' => ['S' => 'Masterpiece', 'A' => 'Great'],
        ]);
        $this->assertMatchesRegularExpression('/^T\d{9}$/', $collection->Tier_ID);

        $item = CollectionItem::create([
            'Tier_ID' => $collection->Tier_ID,
            'Movie_ID' => $movie->Movie_ID,
            'Tier_Level' => 'S',
        ]);
        $this->assertEquals($collection->Tier_ID, $item->Tier_ID);
        $this->assertEquals($movie->Movie_ID, $item->Movie_ID);

        FavoriteReview::create([
            'User_ID' => $user->User_ID,
            'Review_ID' => $review->Review_ID,
            'Saved_At' => now(),
        ]);
        $this->assertDatabaseHas('favorite_reviews', [
            'User_ID' => $user->User_ID,
            'Review_ID' => $review->Review_ID,
        ]);

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Interstellar Test');

        $dashResponse = $this->actingAs($user)->get(route('dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Interstellar Test');
        $dashResponse->assertSee('Action Sci-Fi');

        $leaderResponse = $this->get(route('leaderboard'));
        $leaderResponse->assertStatus(200);
        $leaderResponse->assertSee('Interstellar Test');

        $movieResponse = $this->actingAs($user)->get(route('activities.show', $movie->Movie_ID));
        $movieResponse->assertStatus(200);
        $movieResponse->assertSee('Interstellar Test');
        $movieResponse->assertSee('A masterpiece of sci-fi cinema.');

        $colResponse = $this->actingAs($user)->get(route('collections.show', $collection->Tier_ID));
        $colResponse->assertStatus(200);
        $colResponse->assertSee('Top Sci-Fi Picks');
    }

    public function test_admin_does_not_have_submit_movie_option(): void
    {
        $admin = User::create([
            'Username' => 'adminuser',
            'Email' => 'adminuser@example.com',
            'Password' => bcrypt('password'),
            'Role' => 'admin',
        ]);

        $normalUser = User::create([
            'Username' => 'normaluser',
            'Email' => 'normaluser@example.com',
            'Password' => bcrypt('password'),
            'Role' => 'user',
        ]);

        // 1. Admin accessing create page is redirected to TMDB search
        $adminCreate = $this->actingAs($admin)->get(route('activities.create'));
        $adminCreate->assertRedirect(route('admin.movies.search'));

        // 2. Normal user can access movie proposal page
        $userCreate = $this->actingAs($normalUser)->get(route('activities.create'));
        $userCreate->assertStatus(200);

        // 3. Admin dashboard should not show 'เสนอภาพยนตร์ใหม่' but shows 'นำเข้าภาพยนตร์ (TMDB)'
        $adminDash = $this->actingAs($admin)->get(route('dashboard'));
        $adminDash->assertDontSee('เสนอภาพยนตร์ใหม่');
        $adminDash->assertSee('นำเข้าภาพยนตร์ (TMDB)');

        // 4. Normal user dashboard shows 'เสนอภาพยนตร์ใหม่'
        $userDash = $this->actingAs($normalUser)->get(route('dashboard'));
        $userDash->assertSee('เสนอภาพยนตร์ใหม่');

        // 5. Admin accessing my-movies is redirected to admin pending movies
        $adminMyMovies = $this->actingAs($admin)->get(route('my.movies'));
        $adminMyMovies->assertRedirect(route('admin.movies.pending'));

        // 6. Admin profile does not show submitted movies sections
        $adminProfile = $this->actingAs($admin)->get(route('profile.edit'));
        $adminProfile->assertStatus(200);
        $adminProfile->assertDontSee('หนังที่ฉันเสนอเข้าระบบ');
        $adminProfile->assertDontSee('หนังที่เสนอเข้าระบบ');

        // 7. Normal user profile shows submitted movies sections
        $userProfile = $this->actingAs($normalUser)->get(route('profile.edit'));
        $userProfile->assertStatus(200);
        $userProfile->assertSee('หนังที่ฉันเสนอเข้าระบบ');
    }

    public function test_admin_entity_and_model_work_independently(): void
    {
        $admin = \App\Models\Admin::create([
            'Username' => 'superadmin',
            'Email' => 'superadmin@example.com',
            'Password' => bcrypt('password123'),
        ]);

        $this->assertMatchesRegularExpression('/^A\d{4}$/', $admin->Admin_ID);
        $this->assertDatabaseHas('admins', [
            'Admin_ID' => $admin->Admin_ID,
            'Email' => 'superadmin@example.com',
        ]);

        // Login as newly created Admin via Login form
        $response = $this->post(route('login'), [
            'email' => 'superadmin@example.com',
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }
}
