<?php

namespace Exceedone\Exment\Controllers;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Config;

trait ExmentControllerTrait
{
    protected $title;
    protected $header;
    protected $description;
    
    protected function setPageInfo($title = null, $header = null, $description = null){
        if(isset($header)){ $this->header = $header; }
        if(isset($description)){ $this->description = $description; }
        if(isset($title)){ $this->title = $title; }

        // set admin.config
        if(isset($this->title)){
            Config::set('admin.title', $this->title);
        }
    }

    protected function AdminContent($callback = null){
        return Admin::content(function (Content $content) use ($callback){
            if(isset($this->header)){
                $content->header($this->header);
            }
            if (isset($this->description)) {
                $content->description($this->description);
            }

            if ($callback) {
                $callback($content);
            }
        });
    }
}
