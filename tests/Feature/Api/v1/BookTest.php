<?php

namespace Tests\Feature\Api\v1;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class BookTest extends TestCase
{
    // use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function createBook()
    {
        $data = [
            'name' => 'paul',
            'isbn' => '123-234-ael',
            'authors' => 'james okoli, jufit amose',
            'number_of_pages' => 25,
            'publisher' => 'james@gmail.com',
            'country' => 'Nigeria',
            'release_date' => '2022-06-09',

        ];

        $response = $this->postJson('/api/v1/books', $data);

        // dd($response);
        $response->assertStatus(201)
            ->assertJson(["status_code" => JsonResponse::HTTP_CREATED]);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function gettingAllBooks()
    {

        $response = $this->getJson('/api/v1/books');
        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson(["status_code" => JsonResponse::HTTP_OK]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function it_returns_success_when_getting_filtered_books()
    {

        $response = $this->getJson('/api/v1/books?name=Asefon');
        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson(["status_code" => JsonResponse::HTTP_OK]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function gettingBooksWithError()
    {

        $response = $this->getJson('/api/v1/books?wrongKey=paul');
        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson(["error" => "invalid search key supplied"]);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
 

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function book_was_updated_with_error_if_any()
    {
        $data = [
            'name' => 'paul',
            'isbn' => '123-234-ael',
            'authors' => 'james okoli, jufit amose',
            'number_of_pages' => 25,
            'publisher' => 'james@gmail.com',
            'country' => 'Nigeria',
            'release_date' => '2022-06-09',

        ];

        $response = $this->patchJson('/api/v1/books/' . 40, $data);
        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson(["error" => "Book to be updated not found"]);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
   
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function return_error_when_data_is_wrong()
    {

        $response = $this->getJson('/api/v1/external-books/?name=' . "Wrong title of Kings");
        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function it_returns_correct_from_External_api()
    {
        $query = "A Clash of Kings";
        $response = $this->getJson("/api/v1/external-books/?name=$query");
        $response->assertStatus(JsonResponse::HTTP_OK);

    }


}
