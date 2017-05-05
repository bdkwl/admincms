<?php
namespace Admin\Model;
use Think\Model;
class ConfigModel extends Model {
    protected $tableName = 'web_config';

    // 插入配置表数据
    public function inConfig($data){
        //dump($data);

        foreach ($data as $k=>$val){
            //dump(strtoupper($k) .'-'. $val);
            $insertArray = [
                'title' => $k,
                'name' => strtoupper($k),
                'value' => $val,
                'tips' => '',
                'created_at' => time(),
                'updated_at' => time(),
                'stats' => 1
            ];
            $this->data($insertArray)->add();
        }
        echo "suucess";
    }

    // 获取配置表数据
    public function getConfList($group){
        $field = ['title', 'created_at', 'updated_at', 'group', 'stats'];
        $list = $this->field($field, true)->where( ['group' => $group, 'stats' => 1] )->select();
        $res = [];
        foreach ($list as $val) {
            $title = strtolower($val['name']);
            $res[$title] = [ $val['value'], $val['tips'] ];
        }

        return $res;
    }

    // 更新配置表数据
    public function saveConf($data){
        $flag = false;
        foreach ($data as $k => $val) {
            $name = strtoupper($k);
            $this->where([ 'group' => $data['group'], 'name' => $name ])->setField( [ 'value' => $val, 'updated_at' => time() ]);
            $flag = true;
        }
        if (!$flag) {
            $this->error = '更新失败，请重试';
        }
        return $flag;
    }
}