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
        
        $image=empty($link->getImage())?$link->getPictures()[0]:$link->getImage();
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
}
