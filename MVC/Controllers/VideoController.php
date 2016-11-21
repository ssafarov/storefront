<?php
namespace MVC\Controllers;

use \MVC\View;
use \MVC\Models\Video;

class VideoController extends AbstractController
{

    public function carouselAction()
    {
        $data = [
            'videos' => Video::instance()->getVideos(),
        ];

        return View::factory('video/video', $data)->render();
    }
}