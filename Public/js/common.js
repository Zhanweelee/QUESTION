$(".check-empty").bind("focusout", function() {
	if ($(this).val() == "") {
		if (!$(this).hasClass("alert-necessary")) {
			$(this).addClass("alert-necessary");
		}
	} else {
		if ($(this).hasClass("alert-necessary")) {
			$(this).removeClass("alert-necessary");
		}
	}
});

$(".goto_home").click(function() {
	window.location = "/";
})

$(".goto_create").click(function(){
	window.location = "/survey/create";
});

$(".goto_manage").click(function(){
	window.location = "/survey/manage";
});

$(".goto_modify").click(function(){
	window.location = "/survey/modify";
});

$(".goto_statics").click(function() {
	var sid = $(this)[0].id.split("-")[2];
	window.location = "/survey/statics/s/" + sid;
})

$(".goto_logout").click(function() {
	window.location = "/survey/logout";
})

$(".goto_share").click(function() {
	var sid = $(this)[0].id.split("-")[2];
	window.location = "/survey/share/s/" + sid;
})

$(".goto_profile").click(function() {
	window.location = "/Admin/profile";
})

$(".goto_back").click(function() {
	history.back();
})

$(".wechat-share-btn").click(function() {
	var sid = $(this)[0].id.split("-")[2];
	var img = "";
	WeiXinShareBtn($(".survey-title").html(), "http://172.18.32.174/survey/view/s/" + sid, "", img);
});

function WeiXinShareBtn(title, url, desc, img) {
	if (typeof WeixinJSBridge == "undefined") {
        $(".myNotification").find(".modal-body").html("<p>请点击浏览器分享按钮，发送到微信朋友圈</p>")
        $(".myNotification").modal('show');
        setTimeout(function(){
            $(".myNotification").modal('hide');
        }, 2000);
	} else {
		WeixinJSBridge.invoke('shareTimeline', {
			"title": title,
			"link": url,
			"desc": desc,
			"img_url": "http://come.im/Public/img/logo.jpg"
		});
	}
};