<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookPage extends Model
{
    protected $fillable = ['date', 'weather', 'content'];
    protected $pagelimit = 100;

    public function getEachPageByBook($userid, $bookid, $page)
    {
        $offset = intval($page - 3);
        return $this->where('user_id', '=', $userid)
                    ->where('book_id', '=', $bookid)
                    ->orderBy('date','asc')
                    ->offset($offset)
                    ->limit(1)
                    ->first();
    }

    public function createPage($userid, $bookid, $data, $book)
    {
        $book_exists = $book->getBookById($userid, $bookid);
        if(!$book_exists){
            return [
                'success' => -1,
                'msg' => 'Book does not exist!',
            ];
        }

        if($data['id']>0){
            $book_page = $this->find($data['id']);
            $book_page->fill($data);
        }else{
            $this->fill($data);
            $this->user_id = $userid;
            $this->book_id = $bookid;

            $book_page = $this;
        }

        if($book_page->save()){
            return [
                'success' => 1,
                'msg' => 'Success!',
            ];
        }

        return [
            'success' => -1,
            'msg'=> 'Save data exception!',
        ];
    }
}
