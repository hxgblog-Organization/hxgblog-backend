<?php


namespace App\Model;


class LeaveMessage extends BaseModel
{
    protected $table = 'leave_message';

    /**
     * 添加 留言/回复
     * @param $message_data
     * @param $status
     * @return mixed
     */
    public static function addLeaveMessage($message_data, $status = 2)
    {
        ($status == 1) ? $msg = ['留言成功', '留言失败'] : $msg = ['回复成功', '回复失败'];
        try{
            $msg_id = LeaveMessage::insertGetId($message_data);
            return responseState(0,$msg[0], $msg_id);
        }catch (\Exception $e){
            return responseState(1,$msg[1]);
        }
    }

    /**
     * 查询回复的昵称
     * @param $father_id
     * @return mixed
     */
    public static function selectFatherInformation($father_id)
    {
        return LeaveMessage::select('nick_name','users.phone')->where('msg_father_id', $father_id)
               ->leftJoin('users', 'users.phone', '=', 'leave_message.phone')->first();
    }



}
