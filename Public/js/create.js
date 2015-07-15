var clean = false;

$(".survey-create").click(function() {
	var title = $("#inputTitle").val(),
		subtitle = $("#inputSubTitle").val(),
		type = $("#selectType").val(),
		expiresDays = 0,
		encryptCode = $("#encryptCode").val();

	var expiresElements=document.getElementsByName("expires");
    for(var i=0;i<expiresElements.length;i++)
    {
        if(expiresElements[i].checked==true)
        {
            expiresDays = expiresElements[i].value;
        }
    }

    // 数据过滤
    // 数据过滤

    if (title == "") {
        $(".myNotification").find(".modal-body").html("<p>标题不能为空</p>")
        $(".myNotification").modal('show');
        setTimeout(function(){
            $(".myNotification").modal('hide');
        }, 2000);
    	return;
    }

    $.ajax({
    	type: 'post',
    	url: '/Admin/createSurvey',
    	data: {
            clean: clean,
    		title: title,
    		subtitle: subtitle,
    		expires: expiresDays,
    		isEncrypt: 0,
    	}
    }).done(function(msg) {
    	if (msg['status']) {
            clean = false;
            window.location = "/survey/modify/";
        } else {
            var isConfirm = confirm("存在一个未完成问卷，是否继续之前的问卷?");
            if (isConfirm) {
                window.location = "/survey/modify";
            } else {
                clean = true;
                $("#survey-create").trigger("click");
            }
        }
    });
});