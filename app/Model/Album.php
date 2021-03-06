<?php


namespace App\Model;


class Album extends BaseModel
{
    protected $table = 'album';

    /**
     * 添加相册
     * @param $data
     * @return mixed
     */
    public static function addAlbumData($data)
    {
        return Album::insert($data);
    }


    /**
     * 删除相册
     * @param $album_id
     * @return bool
     */
    public static function deleteAlbumData($album_id)
    {
        return (Album::where('alb_id', $album_id)->delete() > 0);

    }


    /**
     * 修改相册名字和介绍
     * @param $data
     * @return bool
     */
    public static function updateAlbumData($data)
    {
        return (Album::where('alb_id', $data['alb_id'])->update($data) > 0);
    }


    /**
     * 修改相册的数量
     * @param $album_id
     * @param $num
     * @param $is_del
     * @return bool
     */
    public static function updateAlbumPhotoNum($album_id, $num, $is_del = false)
    {
        $ori_num = Album::select('photo_num')->where('alb_id', $album_id)->first()->photo_num;
        ($is_del) ? $new_num = $ori_num - $num : $new_num = $ori_num + $num;
        return (Album::where('alb_id', $album_id)
            ->update(['photo_num' => $new_num]) > 0);
    }


    /**
     * 查找所有相册信息
     * @return mixed
     */
    public static function selectAllAlbumData()
    {
        return Album::orderBy('created_at','desc')->get()->toArray();
    }


    /**
     * 判断用户是否回答对相册问题
     * @param $album_id
     * @param $answer
     * @return bool
     */
    public static function judgeQuestionAnswerData($album_id, $answer)
    {
        return Album::where([['alb_id', $album_id], ['alb_answer', $answer]])->count() > 0;
    }





}
