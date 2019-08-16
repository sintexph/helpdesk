<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LinkPreview\LinkPreview;

class LinkPreviewController extends Controller
{
    public function get_preview(Request $request)
    {
        $linkPreview = new LinkPreview($request['url']);
        $link = $linkPreview->getParsed()["general"];
        
        
        $image=$this->get_picture($link);
        $url=$link->getRealUrl();
        $title=$link->getTitle() ;
        $description=$link->getDescription();
        
        
        return view('layouts.link_preview',[
            'image'=>$image,
            'url'=>$url,
            'title'=>$title,
            'description'=>$description,
        ]);
    }

    function get_picture($link)
    {
        $pictures=$link->getPictures();

        if(count($pictures)==0)
            return empty($link->getImage())?null:$link->getImage();
        else
        {
            if(isset($pictures[0]))
                return $pictures[0];
            else
            {
                return $pictures[rand(1,count($pictures)-1)];
            }
        }
    }
}
