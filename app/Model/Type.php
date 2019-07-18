<?php

namespace App\Model;


class Type extends BaseModel
{
    protected $table = 'type';
    const UPDATED_AT = null;

    //查文章的所有标签
    public static function selectAllTypeData()
    {
        return Type::all();
    }

    /**
     * 增加文章类型对应的类型总数
     * @param $type_id_data
     * @return bool
     */
    public static function increaseArticalTypeNum($type_id_data)
    {
        foreach ($type_id_data as $type_id){
            $type_num = Type::select('type_count')->where('type_id', $type_id)->first()->type_count + 1;
            if(Type::where('type_id', $type_id)->update(['type_count' => $type_num]) == 0) return false;
        }
        return true;
    }

    /**
     * 删除文章，减少文章类型对应的总数
     * @param $type_id_num
     * @return bool
     */
    public static function reduceArticalTypeNum($type_id_num)
    {
        foreach ($type_id_num as $key => $type_count){
            $ori_type_num = Type::select('type_count')->where('type_id', $key)->first()->type_count;
            if(Type::where('type_id', $key)->update(['type_count' => ($ori_type_num - $type_count)]) == 0) return false;
        }
        return true;
    }

}