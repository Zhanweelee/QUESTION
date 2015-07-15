$(".admin-config").click(function() {
	var isConfirm = confirm("第一次配置之后无法复原，请确认?");
	if (!isConfirm) {
		return;
	}

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
		url: '/Admin/config',
		data: {
			username: username,
			password: password,
		},
		dataType: 'json'
	}).done(function(res) {
		if (res['status']) {
			window.location = res['url'];
		} else {
	        $(".myNotification").find(".modal-body").html("<p>" + res['msg'] + "</p>")
	        $(".myNotification").modal('show');
	        setTimeout(function(){
	            $(".myNotification").modal('hide');
	        }, 2000);
		}
	})
});