Vue.filter('upercase',function(value){
	return '暂时没有方法将字母变成大写'
})
Vue.filter('json',function(value){
	return value.join(',')	
})
 Vue.component('todo-item',{
		props:['todo'],
		template:'<li>{{ todo.title }}</li>'
     })
var MyComponent = Vue.extend({
	//扩展
})
var MyComponentInstance = new MyComponent();

var vm = new Vue({
    el:'#app',
    data: {//数据
        message:'第一次尝试',
        selected:['A'],
        test:'aaa',
        url:'#',
        todos:[
        {
        	title:'首页'
        },
        {
        	title:'分类'
        },
        {
        	title:'目录'
        }
        ]
    },
    methods:{//方法
    	addaList:function(){
	    		this.todos.push ({title:'新增的栏目'})
    	},
    	delaList:function(){
    			this.todos.pop();
    	},
    	destoryIt:function(){
    		//销毁
    		this.$destroy();
    	}
    },
    created:function(){//创建后执行
    	console.dir(this.$data)
    },
    updated:function(){//改变
    	console.log('你在'+new Date()+'的时候执行了操作')
    },
    mounted:function(){//安装后
    	console.log('程序已安装');
    },
    destroyed:function(){
    	alert(1)
    }
    // component:{//标签
    // 	'todo-item':{
    // 		props:['todo'],
    // 		template:'<li>{{ todo.title }}</li>'
    // 	}
    // }
});
// console.dir(vm.$data);
// console.dir(vm.$el);
