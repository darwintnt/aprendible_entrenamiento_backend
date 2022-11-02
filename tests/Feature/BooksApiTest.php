<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_all_books()
    {
        $books = Book::factory(5)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ])->assertJsonFragment([
            'title' => $books[1]->title
        ]);
    }

    /** @test */
    function can_get_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /** @test */
    function can_create_books()
    {
        $this->postJson(route('books.store', []))
            ->assertJsonValidationErrorFor('title');

        $response = $this->postJson(route('books.store', [
            'title' => 'Nuevo Libro'
        ]));

        $response->assertJsonFragment([
            'title' => 'Nuevo Libro'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Nuevo Libro'
        ]);
    }


    /** @test */
    function can_update_a_book()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $response = $this->patchJson(route('books.update', $book), [
            'title' => 'Nuevo Libro 2 Edited'
        ]);

        $response->assertJsonFragment([
            'title' => 'Nuevo Libro 2 Edited'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Nuevo Libro 2 Edited'
        ]);
    }

    /** @test */
    function can_delete_a_book()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }
}
