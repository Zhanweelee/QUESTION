// 对选项进行点击效果绑定
$(document).delegate(".option-check-select", "click", function(){
	var questionItem = $(this).parent();
	while (!questionItem.hasClass("question-item")) {
		questionItem = questionItem.parent();
	}
	var type = questionItem[0].id.split("-")[1];
	var qid = questionItem[0].id.split("-")[2];

	if ($(this).hasClass("option-selected")) {
		// 如果是多选，且li已经被选中，再点击则取消选中
		if (type == "checkbox") {
			$(this).find(".inputOption")[0].checked = false;
			$(this).removeClass("option-selected");
		}
	} else {
		// 如果是单选，而且点击的li没有被选中，则取消其他被选中的li
		if (type == "radio") {
			$(this).parent().find("li").each(function() {
				$(this).removeClass("option-selected");
			});
		}

		$(this).find(".inputOption")[0].checked = true;
		$(this).addClass("option-selected")

		// 如果是跳题，则进行隐藏中间的题目
		var parts = $(this)[0].id.split("-");
		var jump =  parts.length > 2 ? parts[3] : 0;
		if (jump) {
			var questionSet = $(".survey-form").find(".question-item");
			for (var i = 0; i < questionSet.length; i ++) {
				var itemId = questionSet[i].id;
				$("#" + itemId).css("display", "block");
				$("#" + itemId).removeClass("question-jumped");
				var itemQid = itemId.split("-")[2];
				if (itemQid > qid && itemQid < jump) {
					$("#" + itemId).css("display", "none");
					$("#" + itemId).addClass("question-jumped");
				}
			}
		}
	}
});

$(".survey-submit").click(function() {
	var sid = $(".survey-form")[0].id.split("-")[2];
	var answerSet = {};
	$(".survey-form").find(".question-item").each(function () {
		if ($(this).hasClass("question-jumped")) {
			return;
		}

		var type = $(this)[0].id.split("-")[1];
		var qid = $(this)[0].id.split("-")[2];
		var question = {};
		question['qid'] = qid;
		question['type'] = type;
		question['answers'] = {};

		// 是否必填

		if (type == "radio" || type == "checkbox") {
			var optionSet = $(this).find(".question-options");
			var inputSet = optionSet.find(".inputOption");
			for (var i = 0; i < inputSet.length; i ++) {
				if (inputSet[i].checked) {
					var oid = inputSet[i].id.split("-")[3];
					var content = $("#question-" + qid + "-text-" + oid).val();
					option = {};
					option['oid'] = oid;
					option['content'] = content;
					question['answers'][oid] = option;
					if (type == "radio") {
						break;
					}
				}
			};
		} else if (type == "text") {
			var inputText = $(this).find("#question-" + qid + "-text").val();
			question['answers'] = inputText;
		}
		answerSet[qid] = question;
	})

	$.ajax({
		type: 'post',
		url: '/Admin/SubmitAnswerSet',
		data: {
			sid: sid,
			answerSet: answerSet,
		},
		dataType: 'json'
	}).done(function(res) {
		if (res['status']) {
			$(".myNotification").find(".modal-body").html("<p>提交成功</p>")
	        $(".myNotification").modal('show');
	        setTimeout(function(){
	            $(".myNotification").modal('hide');
	            window.location = "/survey/share/s/" + sid;
	        }, 2000);
		} else {
			$(".myNotification").find(".modal-body").html("<p>" + res['msg'] + "</p>")
	        $(".myNotification").modal('show');
	        setTimeout(function(){
	            $(".myNotification").modal('hide');
	        }, 2000);
		}
	});
});

$(".survey-save").click(function() {
	$.ajax({
		type: 'post',
		url: "/Admin/SaveSurvey"
	}).done(function() {
		window.location = "/survey/manage";
	});
})