$(".manage-update-password").click(function() {
	var oldPassword = $("#oldPassword").val();
	var newPassword = $("#newPassword").val();
	var newPassword_ = $("#newPassword_").val();

	if (oldPassword == "" || newPassword == "" || newPassword_ == "") {
        $(".myNotification").find(".modal-body").html("<p>原密码或新密码不能为空</p>")
        $(".myNotification").modal('show');
        setTimeout(function(){
            $(".myNotification").modal('hide');
        }, 2000);
		return;
	}

	if (newPassword_ != newPassword) {
        $(".myNotification").find(".modal-body").html("<p>原密码和新密码不一致</p>")
        $(".myNotification").modal('show');
        setTimeout(function(){
            $(".myNotification").modal('hide');
        }, 2000);
		return;
	}

	$.ajax({
		type: 'post',
		url: '/Admin/updatePassword',
		data: {
			oldPassword: oldPassword,
			newPassword: newPassword,
		},
		dataType: 'json'
	}).done(function(res) {
        $(".myNotification").find(".modal-body").html("<p>" + res['msg'] + "</p>")
        $(".myNotification").modal('show');
        setTimeout(function(){
            $(".myNotification").modal('hide');
            
	        if (res['status']) {
	        	window.location = "/Admin/login";
	        }
        }, 2000);
	})
})