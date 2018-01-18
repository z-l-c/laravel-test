<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Book;
use App\Handlers\ImagesHandler;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the Home Page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Book $book)
    {
        $user = Auth::user();
        $data = $book->getBooksByUser($user->id);

        return view('home', compact('data'));
    }

    /**
     * Create a book.
     * Method : Post
     *
     * @return
     */
    public function create(Request $request, Book $book, ImagesHandler $handler)
    {
        $user = Auth::user();

        $postData = $request->all();

        if($postData['cover']){
            $cover_path = $handler->save($postData['cover'], 'cover', 'cover');
            if($cover_path){
                $postData['cover'] = $cover_path;
            } else {
                $postData['cover'] = '';
            }
        }

        $result = $book->createBook($user->id, $postData);

        return redirect()->route('home')->with('status', $result);
    }

    /**
     * Open a book.
     *
     * @return
     */
     public function openBook(Request $request)
     {
         // echo $request->id;

         return view('book');
     }

     /**
      * Delete a book.
      *
      * @return
      */
      public function deleteBook(Request $request, Book $book)
      {
          $user = Auth::user();
          $result = $book->deleteBook($user->id, $request->bookid);

          echo $result;
      }
}
