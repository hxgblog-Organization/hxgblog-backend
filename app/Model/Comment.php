<?php

namespace App\Model;


class Comment extends BaseModel
{
    protected $table = 'comment';


    //查某一篇文章的所有顶级评论
    public static function selectTopLevelComment($art_id)
    {
        return Comment::select('come_id', 'come_content', 'comment.created_at', 'user_name')
                      ->leftJoin('users', 'comment.user_phone', '=', 'users.user_phone')
                      ->where([['come_father_id', 0],['arti_id', $art_id]])->get();



    }

}