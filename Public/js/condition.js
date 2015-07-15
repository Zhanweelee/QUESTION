$(document).delegate(".option-condition-checked", "change", function() {
	var sid = $(".survey-question-option-form")[0].id.split("-")[1];
	var jump = $(this)[0].value;
	var oid = $(this)[0].id.split("-")[2];
	if (jump == "") {
		return;
	}

	var option = $(this).parent();
	while(!option.hasClass("question-option-condition")) {
		option = option.parent();
	}

	// 修改状态
	var status = option.find(".option-status");
	status.html("<span class='glyphicon glyphicon-refresh'></span>");

	$.ajax({
		type: 'post',
		url: '/Admin/UpdateCondition',
		data: {
			oid: oid,
			jump: jump,
			sid: sid,
		},
		dataType: 'json'
	}).done(function (res) {
		if (res['status']) {
			status.html("<span class='glyphicon glyphicon-ok'></span>");
		} else {
			status.html("<span class='glyphicon glyphicon-remove'></span>");

			$(".myNotification").find(".modal-body").html("<p>" + res['msg'] + "</p>")
	        $(".myNotification").modal('show');
	        setTimeout(function(){
	            $(".myNotification").modal('hide');
	        }, 2000);
		}
	})
})