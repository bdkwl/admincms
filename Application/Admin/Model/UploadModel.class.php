<?php
namespace Admin\Model;
use Think\Model;
class UploadModel extends Model {
    protected $tableName = 'media';

    /** 获取媒体文件路径
     * @param null $id mediaid
     * @param null $type
     * @return null|string 文件路径
     */
    public function getCover( $id=null, $type=null ) {
        if ($id) {
            if ( strpos( $id, "http" ) === 0 ) {
                return $id;
            }
            $media_info = $this->find($id);
            $url = __ROOT__ .'/'. $media_info['realpath'];
        }
        if ( !isset( $url ) ) {
            switch ( $type ) {
                case 'avatar': // 默认头像
                    $url = __ROOT__ . '/Public/default/avatar5.png';
                    break;
                default:
                    $url = '';
                    break;
            }
        }
        return $url;
    }

    /** 上传文件并保存media数据
     * @param $files $_FILES 上传文件数据
     * @return array 返回结果
     */
    public function upload( $files ) {
        $FILES = $files ? $files : $_FILES; // 获取上传文件信息
        $dir   = I('request.d') ? I('request.d') : 'image'; // 上传文件类型image|flash|media|file
        $usage  = I('request.u') ? I('request.u') : 'system'; // 上传文件用途avatar(头像)|system(系统)|upload(用户上传)|qrcode(二维码)
        $return = [ 'error' => 0 , 'success' => 1, 'status' => 1 ]; //标准返回数据

        if ( !in_array( $dir, [ 'image', 'flash', 'media', 'file' ] ) ) {
            $return = [
                'error' => 1,
                'success' => 0,
                'status' => 0,
                'message' => '缺少上传参数'
            ];
            return $return;
        }

        // 允许上传的文件后缀
        $extArr = [
            'image' => [ 'gif', 'jpg', 'jpeg', 'png', 'bmp'],
            'flash' => [ 'swf', 'flv' ],
            'media' => [ 'swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb', 'mp4' ],
            'file'  => [ 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'wps', 'txt', 'zip', 'rar', 'gz', 'bz2', '7z', 'ipa', 'apk', 'dmg', 'iso'],
        ];

        // 查看有无已有相同文件上传过
        $reay_file = array_shift($FILES);
        //dump($reay_file);
        $con['md5'] = md5_file($reay_file['tmp_name']);
        $con['sha1'] = sha1_file($reay_file['tmp_name']);
        $con['size'] = $reay_file['size'];
        $hasUpload = $this->where($con)->find();
        if ($hasUpload) {
            // 相同文件直接返回
            $return['id'] = $hasUpload['mediaid'];
            $return['path'] =  __ROOT__ .'/'. $hasUpload['realpath'];
        } else {
            // 实例化上传类
            $upload_config = C('UPLOAD_CONFIG');
            $upload = new \Think\Upload($upload_config);
            $upload->savePath = $dir .'/'. $usage .'/';
            $upload->exts = $extArr[$dir] ? $extArr[$dir] : $extArr['image'];
            $info = $upload->uploadOne($reay_file); // 上传文件

            if ( !$info ) {
                $return = [
                    'error' => 1,
                    'success' => 0,
                    'status' => 0,
                    'message' => '上传出错'. $upload->getError()
                ];
            } else {
                // 保存上传文件数据
                $media_data = [
                    'type' => $usage,
                    'realpath' => 'Public/uploads/'.$info['savepath'].$info['savename'],
                    'ext' => $info['ext'],
                    'size' => $info['size'],
                    'md5' => $info['md5'],
                    'sha1' => $info['sha1'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'stats' => 1
                ];
                $result = $this->create($media_data);
                $result = $this->add($result);
                if ($result) {
                    $return['path'] = __ROOT__ .'/'. $media_data['realpath'];
                    //$return['name'] = $media_data['name'];
                    $return['id']   = $result;
                } else {
                    $return['error']   = 1;
                    $return['success'] = 0;
                    $return['status']  = 0;
                    $return['message'] = '上传出错' . $this->error;
                }
            }
        }
        return $return;
    }
}