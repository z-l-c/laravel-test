<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Book;
use App\BookPage;
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
     *
     * @return
     */
    public function create(Request $request, Book $book, ImagesHandler $handler)
    {
        $user = Auth::user();

        $postData = $request->all();

        if($postData['cover']){
            $save_res = $handler->save($postData['cover'], 'cover', 'cover');
            if($save_res['success']){
                $postData['cover'] = $save_res['file_path'];
                $result = $book->createBook($user->id, $postData);
            } else {
                $postData['cover'] = '';
                $result = $save_res['msg'];
            }
        } else {
            $result = $book->createBook($user->id, $postData);
        }

        return redirect()->route('home')->with('status', $result);
    }

    /**
     * Open a book.
     *
     * @return
     */
    public function openBook(Request $request, Book $book)
    {
        $user = Auth::user();

        $data = $book->getBookById($user->id, $request->id);

        if($data){
            return view('book', compact('data'));
        }else{
            return view('404');
        }
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

    /**
     * Get the pages of a book.
     *
     * @return
     */
    public function bookPages(Request $request, BookPage $bp)
    {
        $user = Auth::user();

        $result = $bp->getEachPageByBook($user->id, $request->bookid, $request->page);

        echo json_encode($result);
    }

    /**
     * Create a page into a book.
     *
     * @return
     */
    public function createPage(Request $request, BookPage $bp, Book $book)
    {
        $user = Auth::user();

        $postData = $request->all();

        $result = $bp->createPage($user->id, $postData['book_id'], $postData, $book);

        echo json_encode($result);
    }

    /**
     * Create a page into a book.
     *
     * @return
     */
    public function uploadImage(Request $request, ImagesHandler $handler)
    {
        $data = [
            'success'   => false,
            'msg'       => 'Failure!',
            'file_path' => ''
        ];

        if($file = $request->upload_file) {
            $save_res = $handler->save($file, 'pic', 'pic', 'raw');
            if($save_res['success']) {
                $data['file_path'] = $save_res['file_path'];
                $data['msg']       = "Success!";
                $data['success']   = true;
            }else{
                $data['msg']       = $save_res['msg'];
            }
        }

        return json_encode($data);
    }
}
