<?php

namespace Tests\Feature;

use App\File;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Class FilesTest
 * @package Tests\Feature
 */
class FilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function aUserCanUploadAFile()
    {
        Storage::fake('s3');

        $user = $this->signIn();

        $file = UploadedFile::fake()->image('test.jpg');
        $this->post('/files', [
            'name' => 'Test File',
            'file' => $file
        ])->assertJsonFragment(['name' => 'Test File', 'type' => 'image', 'path' => $file->hashName()]);

        Storage::disk('s3')->assertExists($file->hashName());
        $this->assertDatabaseHas('files', [
            'user_id' => $user->id,
            'name' => 'Test File',
            'type' => 'image'
        ]);
    }

    /** @test */
    public function aUserCanUploadAVideo()
    {
        Storage::fake('s3');

        $user = $this->signIn();

        $file = UploadedFile::fake()->create('test.mp4');
        $this->post('/files', [
            'name' => 'Test File',
            'file' => $file
        ])->assertJsonFragment(['name' => 'Test File', 'type' => 'video', 'path' => $file->hashName()]);

        Storage::disk('s3')->assertExists($file->hashName());
        $this->assertDatabaseHas('files', [
            'user_id' => $user->id,
            'name' => 'Test File',
            'type' => 'video'
        ]);
    }

    /** @test */
    public function aUserCanViewTheirFiles()
    {
        Storage::fake('s3');

        $user = $this->signIn();

        $url = Storage::disk('s3')->put('test.jpg', UploadedFile::fake()->image('test.jpg'));

        $file = factory(File::class)->create(
            [
                'user_id' => $user->id,
                'name' => 'Test Image',
                'path' => $url,
                'type' => 'image'
            ]
        );

        $this->get('/files')
            ->assertJson([['id' => $file->id, 'name' => 'Test Image', 'path' => $url, 'type' => 'image']]);
    }

    /** @test */
    public function aUserCanDeleteAFile()
    {
        $this->withoutExceptionHandling();
        Storage::fake('s3');

        $user = $this->signIn();

        $fakeFile = UploadedFile::fake()->image('test.jpg');
        $url = Storage::disk('s3')->put('test.jpg', $fakeFile);

        $file = factory(File::class)->create(
            [
                'user_id' => $user->id,
                'name' => 'Test Image',
                'path' => $url,
                'type' => 'image'
            ]
        );

        $this->delete('/files/' . $file->id)
            ->assertStatus(202)
            ->assertJson(['message' => 'File Deleted']);

        Storage::disk('s3')->assertMissing($fakeFile->hashName());

        $this->assertDatabaseMissing('files', ['id' => $file->id]);
    }

    /** @test */
    public function aUserCannotViewOtherPeoplesFiles()
    {
        $this->signIn();
        $otherUser = factory(User::class)->create();

        $file = factory(File::class)->create(
            [
                'user_id' => $otherUser->id,
            ]
        );

        $this->get('/files/' . $file->id)->assertStatus(403);
    }

    /** @test */
    public function aUserCanViewTheirOwnFile()
    {
        Storage::fake('s3');

        $user = $this->signIn();

        $url = Storage::disk('s3')->put('test.jpg', UploadedFile::fake()->image('test.jpg'));

        $file = factory(File::class)->create(
            [
                'user_id' => $user->id,
                'name' => 'Test Image',
                'path' => $url,
                'type' => 'image'
            ]
        );

        $response = $this->get('/files/' . $file->id);
        $response->assertOk();
    }
}
