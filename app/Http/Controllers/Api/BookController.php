<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\ResponseHelper;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // a. Return category and authors for each book
        $books = Book::with(['category', 'authors'])->get();
        return ResponseHelper::success(' جميع الكتب', $books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        // b. Validate authors array and existence
        $validated = $request->validate([
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
        ]);

        $book = Book::create($request->all());

        if ($request->hasFile('cover')){
            $file = $request->file('cover');
            $filename = "$request->ISBN." . $file->extension();
            Storage::putFileAs('book-images', $file ,$filename );
            $book->cover = $filename;
            $book->save();
        }

        // Attach authors to the book
        $book->authors()->attach($request->authors);

        return ResponseHelper::success("تمت إضافة الكتاب", $book);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        // D. Return book with its category and authors
        $book->load(['category', 'authors']);
        return ResponseHelper::success('تفاصيل الكتاب', $book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        // C. Validate authors array and existence
        $validated = $request->validate([
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
        ]);

        $book->update($request->all());

        // Handle cover image update
        if ($request->hasFile('cover')) {
            // Delete previous image if exists
            if ($book->cover && Storage::disk('public')->exists('book-images/' . $book->cover)) {
                Storage::disk('public')->delete('book-images/' . $book->cover);
            }
            $file = $request->file('cover');
            $filename = "$request->ISBN." . $file->extension();
            Storage::putFileAs('book-images', $file, $filename);
            $book->cover = $filename;
            $book->save();
        }

        // Sync authors (detach previous and attach new)
        $book->authors()->sync($request->authors);

        return ResponseHelper::success("تمت تعديل الكتاب", $book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // e. Delete book image if exists
        if ($book->cover && Storage::disk('public')->exists('book-images/' . $book->cover)) {
            Storage::disk('public')->delete('book-images/' . $book->cover);
        }
        $book->delete();
        return ResponseHelper::success("تمت حذف الكتاب", $book);
    }
}
