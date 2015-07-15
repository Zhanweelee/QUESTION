$(".manage-login").click(function() {
	var username = $("#inputUsername").val();
	var password = $("#inputPassword").val();
	var rememberMe = $("#remember-me")[0].checked;
	if (username == "" || password == "") {
        $(".myNotification").find(".modal-body").html("<p>用户名或者密码不能为空</p>")
        $(".myNotification").modal('show');
        setTimeout(function(){
            $(".myNotification").modal('hide');
        }, 2000);
        return;
	}

	$.ajax({
		type: 'post',
		url: '/Admin/Authentication',
		data: {
			username: username,
			password: password,
		},
		dataType: 'json'
	}).done(function(msg) {
		if (msg['status']) {
			window.location = msg['url'];
		} else {
	        $(".myNotification").find(".modal-body").html("<p>失败，用户名或者密码错误</p>")
	        $(".myNotification").modal('show');
	        setTimeout(function(){
	            $(".myNotification").modal('hide');
	        }, 2000);
		}
	})
});