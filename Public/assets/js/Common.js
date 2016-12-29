/**
公共js
需要提前加载layer
*/
var Common;
Common = {
	init:function(){

	},
	load:function(){
		var $option = {shade:[0.1,'#fff']};//白色透明背景
		return layer.load(1,$option);
	},
    faild:function(msg)
    {
        window.layer.msg(msg,{icon:5,shift: 6})
    }
}