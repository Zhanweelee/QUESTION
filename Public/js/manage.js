$(document).delegate(".survey-finish", "click", function() {
	var surveyItem = $(this).parent();
	while (!surveyItem.hasClass("survey-item")) {
		surveyItem = surveyItem.parent();
	}

	var sid = surveyItem[0].id.split("-")[2];

	if (!confirm("关闭问卷之后无法重新打开，是否关闭问卷 #" + sid + " ?")) {
		return;
	}

	if (sid) {
		$.ajax({
			type: 'post',
			url: '../Admin/FinishSurvey',
			data: {
				sid: sid,
			},
			dataType: 'json'
		}).done(function(msg) {
			if (msg['status']) {
				window.location.reload();
			} else {
				alert("无法关闭此问卷");
			}
		})
	}
})

$(document).delegate(".survey-remove", "click", function() {
	var surveyItem = $(this).parent();
	while (!surveyItem.hasClass("survey-item")) {
		surveyItem = surveyItem.parent();
	}

	var sid = surveyItem[0].id.split("-")[2];

	if (confirm("请慎重操作，是否删除该问卷 #" + sid + " ?")) {
		if (!confirm("再次确认?")) {
			return;
		}
		
		if (sid) {
			$.ajax({
				type: 'post',
				url: '../Admin/DeleteSurvey',
				data: {
					sid: sid,
				},
				dataType: 'json'
			}).done(function(msg) {
				if (msg['status']) {
					surveyItem.remove();
				} else {
					alert("无法删除");
				}
			})
		}
	}

})

$(".survey-add").click(function() {
	window.location = "/survey/create";
})