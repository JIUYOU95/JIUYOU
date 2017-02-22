/*
 *侧栏头部灯罩
 */
$(document).ready(function(){  
	$(".avatar a").mouseover(function(){
		$(".avatar a span").css("margin-top","0px");
	}).mouseout(function(){
		$(".avatar a span").css("margin-top","63px");
	})  
})
/*
 *回到顶部
 */
$(function(){
	$(window).scroll(function(){
		var top=$(window).scrollTop();
		if(top>400){
		$("#back-to-top").fadeIn();
		}else{
		$("#back-to-top").fadeOut();
		}
	});
	//点击返回头部效果
	$("#back-to-top").click(function(){
		$("html,body").animate({scrollTop:0});
	});
});
/*
 *标签云颜色随机
 */
$(function(){
	var obj=$(".tagcloud a");
	function rand(num){
		return parseInt(Math.random()*num+1);
	}
	function randomcolor(){
		var str=Math.ceil(Math.random()*16777215).toString(16);   
		if(str.length<6){   
		 str="0"+str;   
		}   
		return str;
	}
	for(len=obj.length,i=len;i--;){
	//obj[i].style.left=rand(600)+"px";   
	//obj[i].style.top=rand(400)+"px";   
	obj[i].className="color"+rand(5);   
	obj[i].style.zIndex=rand(5);   
	//obj[i].style.fontSize=rand(12)+12+"px";   
	obj[i].style.background="#"+randomcolor();
	}
});

//sidebar

/*$(document).ready(function(){  
	$(".tagcloud a").mouseover(function(){
		$(this).addClass("over");
	}) .mouseout(function(){
		$(this).removeClass("over");
	})  
}); */
$(document).ready(function(){
	$("#showLeftPush").click(function(){
  		$(".left").toggle("fast",function(){
  			$(".right").toggleClass("leftpush");
  		}

  		);
	});
	//评论显示
	$("#comment").click(function(){
	  $(".comment-show").slideDown();
	});

	$(".m-nav .nav li").hover(function(){
		$("ul",this).slideDown();
	},function(){
		$("ul",this).slideUp();
	});
	

})
//页面滚动顶部定位

/*
 *LOGIN
 */
$(function(){
	$("#sign_do").mousemove(function(){
		$(".sign_do_login").show();
	}).mouseout(function(){
		$(".sign_do_login").hide();
	});
})


