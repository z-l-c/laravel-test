<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['name', 'cover', 'brief'];
    protected $booklimit = 20;

    public function getBooksByUser($userid)
    {
        return $this->where('user_id', '=', $userid)
                    ->orderBy('created_at','desc')
                    ->get();
    }

    public function getBookById($userid, $id)
    {
        return $this->where('user_id', '=', $userid)
                    ->find($id);
    }

    public function createBook($userid, $data)
    {
        $this->fill($data);

        if(empty($this->name)){
            return 'Book Name Required!';
        }

        if($this->checkBookNums($userid) >= $this->booklimit){
            return 'Total Limited!';
        }

        if($this->checkBookName($userid, $this->name) > 0){
            return 'Book Name Exists!';
        }

        $this->user_id = $userid;
        return $this->save();
    }

    public function checkBookNums($userid)
    {
        return $this->where('user_id', '=', $userid)
                    ->count();
    }

    public function checkBookName($userid, $bookname)
    {
        return $this->where('user_id', '=', $userid)
                    ->where('name', '=', $bookname)
                    ->count();
    }

    public function deleteBook($userid, $id)
    {
        return $this->where('user_id', '=', $userid)
                    ->where('id', '=', $id)
                    ->delete();
    }

}
