<?php
// 上传
namespace Admin\Controller;
use Think\Controller;
class UploadController extends BaseController {
    public function upload() {
        $res = json_encode( D('Upload')->upload() );
        exit( $res );
    }
}