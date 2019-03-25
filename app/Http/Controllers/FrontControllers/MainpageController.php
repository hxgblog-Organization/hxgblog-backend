<?php

namespace App\Http\Controllers\FrontControllers;

use App\Http\Controllers\Controller;
use App\Model\Artical;

class MainpageController extends Controller
{
    //首页
    public function showMainPage()
    {
        $data['new_artical'] = Artical::selectNewArticalData();
        $data['browse_top']  = Artical::selectBrowseTopData();
        return responseToJson(0,"success",$data);
    }


}