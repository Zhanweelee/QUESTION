var optionSaveLock = false;
var questionSaveLock = false;

$(".survey-question-add").click(function(){
	$(".survey-select-type").fadeToggle();
});

// 根据模板，添加单选题
$(".survey-create-radio").click(function() {
	var idArray = $(this)[0].id.split("-");
	var sid = idArray[3];

	//验证sid是否为数字

	var newQuestion = $("#question-radio-sample").clone().css("display", "block");
	newQuestion[0].id = "question-radio-new";
	newQuestion.appendTo($("#survey-question-form-" + sid));

	$(".survey-select-type").fadeToggle();

	scollToFocus(newQuestion);
});

// 根据模板，添加多选题
$(".survey-create-checkbox").click(function() {
	var idArray = $(this)[0].id.split("-");
	var sid = idArray[3];

	//验证sid是否为数字

	var newQuestion = $("#question-checkbox-sample").clone().css("display", "block");
	newQuestion[0].id = "question-checkbox-new";
	newQuestion.appendTo($("#survey-question-form-" + sid));

	$(".survey-select-type").fadeToggle();

	scollToFocus(newQuestion);
});

// 根据模板，添加填空题
$(".survey-create-text").click(function() {
	var idArray = $(this)[0].id.split("-");
	var sid = idArray[3];

	//验证sid是否为数字

	var newQuestion = $("#question-text-sample").clone().css("display", "block");
	newQuestion[0].id = "question-text-new";
	newQuestion.appendTo($("#survey-question-form-" + sid));

	$(".survey-select-type").fadeToggle();

	scollToFocus(newQuestion);
});

// 对题目标题和副标题绑定，内容是否修改
$(document).delegate(".question-check-changed", "change", function(){
	var questionItem = $(this).parent();
	while (!questionItem.hasClass("question-item")) {
		questionItem = questionItem.parent();
	}

	var changed = questionItem.find(".question-status-changed").css("color", "orange");
	changed.find("span").html("未保存");
	changed.show();

	var saved = questionItem.find(".question-status-saved");
	saved.hide();
});

// 对添加选项按钮进行绑定
$(document).delegate(".question-option-add", "click", function(){

	var optionSet = $(this).parent().parent().find(".question-option-set");

	var qid = optionSet[0].id.split("-")[1];

	if (qid == "sample" || qid == "new") {
        $(".myNotification").find(".modal-body").html("<p>正在为您保存新题目...</p>")
        $(".myNotification").modal('show');
        setTimeout(function(){
            $(".myNotification").modal('hide');
        }, 2000);
		$(this).parent().find(".question-update").trigger("click");	
    	return;
	} else {
		optionSet.append("<div class='form-group question-option' id='question-" + qid + "-option-new'>\
                      <div class='input-group'>\
                        <div class='input-group-addon option-status'>\
                          <span class='glyphicon glyphicon-ok'></span>\
                        </div>\
                        <input type='text' class='form-control option-check-changed' id='inputContent' placeholder='选项内容' autocomplete='off'>\
                        <div class='input-group-addon option-delete'>\
                          <span class='glyphicon glyphicon-trash'></span>\
                        </div>\
                        <span class='input-group-addon'><input class='option-check-changed option-mixed' type='checkbox' placeholder='选项内容' autocomplete='off'>复合</span>\
                      </div>\
                    </div>");
	}
});

// 对题目保存进行绑定
$(document).delegate(".question-update", "click", function(){
	var questionItem = $(this).parent();
	while (!questionItem.hasClass("question-item")) {
		questionItem = questionItem.parent();
	}

	var changed = questionItem.find(".question-status-changed").css("color", "gray");
	changed.find("span").html("保存中");
	changed.show();

	var title = questionItem.find("#inputTitle").val();
	var subtitle = questionItem.find("#inputSubTitle").val();
	var type = questionItem[0].id.split("-")[1];
	var qid = questionItem[0].id.split("-")[2];
	var isnecessary = questionItem.find(".question-isnecessary")[0].checked == true ? 1 : 0;
	var isNew = false;
	if (qid == "new") {
		isNew = true;
	}
	var sid = questionItem.parent()[0].id.split("-")[3];

	if (isNew) {
	    $.ajax({
	    	type: 'post',
	    	url: '/Admin/CreateQuestion',
	    	data: {
	    		title: title,
	    		subtitle: subtitle,
	    		type: type,
	    		sid: sid,
	    		isnecessary: isnecessary,
	    	}
	    }).done(function(msg) {
	    	if (msg['status']) {
	    		var changed = questionItem.find(".question-status-changed");
				changed.hide();
				var success = questionItem.find(".question-status-saved").css("color", "green");
				success.show();
				questionItem[0].id = "question-" + type + "-" + msg['data'];
				var optionSet = questionItem.find(".question-option-set");
				optionSet[0].id = "question-" + msg['data'] + "-option-checkbox";
	    	} else {
				var changed = questionItem.find(".question-status-changed").css("color", "orange");
				changed.find("span").html("未保存");
				questionItem.find(".question-isnecessary")[0].checked = false;
	    	}
	    });
	} else {
	    $.ajax({
	    	type: 'post',
	    	url: '/Admin/UpdateQuestion',
	    	data: {
	    		title: title,
	    		subtitle: subtitle,
	    		qid: qid,
	    		isnecessary: isnecessary,
	    	}
	    }).done(function(msg) {
	    	if (msg['status']) {
	    		var changed = questionItem.find(".question-status-changed");
				changed.hide();
				var success = questionItem.find(".question-status-saved").css("color", "green");
				success.show();
	    	} else {
				var changed = questionItem.find(".question-status-changed").css("color", "orange");
				changed.find("span").html("未保存");
		        $(".myNotification").find(".modal-body").html("<p>" + msg['msg'] + "</p>")
		        $(".myNotification").modal('show');
		        setTimeout(function(){
		            $(".myNotification").modal('hide');
		        }, 2000);
		        questionItem.find(".question-isnecessary")[0].checked = false;
		    }
	    });
	}
});

// 对选项修改进行绑定，实时保存
$(document).delegate(".option-check-changed", "change", function(){
	if (optionSaveLock) {
        $(".myNotification").find(".modal-body").html("<p>正在为您保存新选项...</p>")
        $(".myNotification").modal('show');
        setTimeout(function(){
            $(".myNotification").modal('hide');
        }, 2000);

		$(this)[0].checked = false;
		return;
	}

	optionSaveLock = true;

	var option = $(this).parent();
	while (!option.hasClass("question-option")) {
		option = option.parent();
	}

	// 修改状态
	var status = option.find(".option-status");
	status.html("<span class='glyphicon glyphicon-refresh'></span>");

	var optionSet = option;
	while (!optionSet.hasClass("question-option-set")) {
		optionSet = optionSet.parent();
	}

	var isRadio = 0, isCheckbox = 0, isText = 0;
	var type = optionSet[0].id.split("-")[3];
	if (type == "radio") {
		isRadio = 1;
	} else if (type == "checkbox") {
		isCheckbox = 1;
	} else if (type == "text") {
		isText = 1;
	}
	var isMixed = option.find('.option-mixed')[0].checked ? 1 : 0;

	var qid = option[0].id.split("-")[1];
	var oid = option[0].id.split("-")[3];
	var content = option.find("#inputContent").val();
	var isNew = false;
	if (oid == "new") {
		isNew = true;
	}

	if (isNew) {
	    $.ajax({
	    	type: 'post',
	    	url: '/Admin/CreateOption',
	    	data: {
	    		qid: qid,
	    		isRadio: isRadio,
	    		isCheckbox: isCheckbox,
	    		isText: isText,
	    		isMixed: isMixed,
	    		content: content,
	    	}
	    }).done(function(msg) {
	    	if (msg['status']) {
				status.html("<span class='glyphicon glyphicon-ok'></span>");
				option[0].id = "question-" + qid + "-option-" + msg['data'];
				optionSaveLock = false;
	    	} else {
				status.html("<span class='glyphicon glyphicon-remove'></span>");
	    	}
	    });
	} else {
	    $.ajax({
	    	type: 'post',
	    	url: '/Admin/UpdateOption',
	    	data: {
	    		oid: oid,
	    		qid: qid,
	    		isRadio: isRadio,
	    		isCheckbox: isCheckbox,
	    		isText: isText,
	    		isMixed: isMixed,
	    		content: content,
	    	}
	    }).done(function(msg) {
	    	if (msg['status']) {
				status.html("<span class='glyphicon glyphicon-ok'></span>");
				optionSaveLock = false;
	    	} else {
				status.html("<span class='glyphicon glyphicon-remove'></span>");
	    	}
	    });
	}
});


// 对删除按钮进行绑定
$(document).delegate(".question-remove", "click", function(){
	if (optionSaveLock) {
		$(this).checked = false;
		return;
	}

	var isConfirm = confirm("是否要删除这条题目?");
	if (!isConfirm) {
		return;
	}

	var questionItem = $(this).parent();
	while (!questionItem.hasClass("question-item")) {
		questionItem = questionItem.parent();
	}

	var qid = questionItem[0].id.split("-")[2];

	if (qid == "new") {
		questionItem.remove();
		return;
	}

    $.ajax({
    	type: 'post',
    	url: '/Admin/DeleteQuestion',
    	data: {
    		qid: qid,
    	}
    }).done(function(msg) {
    	if (msg['status']) {
			questionItem.remove();
    	} else {
			//
    	}
    });
});

function scollToFocus(ele) {
	var scrollPos = ele.offset().top;
	$(window).scrollTop(scrollPos);
}


// 对选项修改进行绑定，实时保存
$(document).delegate(".option-delete", "click", function(){
	var option = $(this).parent();
	while (!option.hasClass("question-option")) {
		option = option.parent();
	}


	var oid = option[0].id.split("-")[3];
	var isNew = false;
	if (oid == "new") {
		isNew = true;
	}

	if (isNew) {
		option.remove();
		return;
	}

    $.ajax({
    	type: 'post',
    	url: '/Admin/DeleteOption',
    	data: {
    		oid: oid,
    	}
    }).done(function(msg) {
    	if (msg['status']) {
			option.remove();
    	} else {
			//
    	}
    });
});

$(".goto_view").click(function() {
	var sid = $(".survey-question-form")[0].id.split("-")[3];
	window.location = "/survey/view/s/" + sid;
});

$(".survey-title-modify").click(function() {
	var title = $("#survey-title").html();
	var subtitle = $("#survey-subtitle").html();
	var newTitle = prompt("请输入主标题", title);
	var newSubTitle = prompt("请输入副标题", subtitle);
	if (newTitle == null || newSubTitle == null) {
		return;
	}
	$.ajax({
		type: 'post',
		url: "/Admin/UpdateSurveyTitle",
		data: {
			title: newTitle,
			subtitle: newSubTitle,
		}
	}).done(function(msg) {
		if (msg['status']) {
	        $(".myNotification").find(".modal-body").html("<p>更新标题成功</p>")
	        $(".myNotification").modal('show');
			$("#survey-title").html(newTitle);
			$("#survey-subtitle").html(newSubTitle);
	        setTimeout(function(){
	            $(".myNotification").modal('hide');
	        }, 2000);
		} else {
	        $(".myNotification").find(".modal-body").html("<p>更新标题失败</p>")
	        $(".myNotification").modal('show');
			$("#survey-title").html(newTitle);
			$("#survey-subtitle").html(newSubTitle);
	        setTimeout(function(){
	            $(".myNotification").modal('hide');
	        }, 2000);

		}
	});
});

$(document).delegate(".question-isnecessary", "change", function() {
	var questionItem = $(this).parent();
	while (!questionItem.hasClass("question-item")) {
		questionItem = questionItem.parent();
	}

	questionItem.find(".question-update").trigger("click");
});

$(document).delegate(".question-condition", "click", function() {
	var sid = $(this)[0].id.split("-")[1];
	var qid = $(this)[0].id.split("-")[3];
	window.location = "/survey/condition/s/" + sid + "/q/" + qid;
})