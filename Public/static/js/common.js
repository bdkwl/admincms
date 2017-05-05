// 应用js脚本
;
"use strict";

// 删除确定框
function confirmDel() {
    //var obj = this.getAttribute('url');
    if ( confirm('确定要删除吗?') ) {
        //console.log(obj);
        return true;
    }
    return false;
}