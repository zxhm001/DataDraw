String.prototype.endWith=function(endStr){
    var d = this.length-endStr.length;
    return (d >= 0 && this.lastIndexOf(endStr) == d)
}

$.material.init();
$(".btn-fab").mouseover(function() {
    $(this).addClass("animated jello");
});
$(".btn-fab").mouseout(function() {
    $(this).removeClass("animated jello");
});
$(window).load(function() {
    $('.file_title_inside').map(function() {
        if (this.offsetWidth < this.scrollWidth) {
            $('.file_title_inside').liMarquee();
        }
    });

});
jQuery.ajaxSetup({
    cache: true
});

function getSize(size) {
    var filetype = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    var i, bit;
    for (i = 0; size >= 1024; i++) {
        size = size / 1024;
    }
    return parseInt(size * 100) / 100 + filetype[i];
}
function formateDate(dateString){
    var bigDate = dateString.split(" ");
    var smallDate = bigDate[0].split("-");
    return smallDate[0]+"年"+smallDate[1]+"月"+smallDate[2]+"日 "+bigDate[1];
}

function createGraph (){
    var name = $('#graph-name-input').val();
    if(name == null || name.trim().length == 0)
    {
        toastr["error"]("请输入文件名");
        return;
    }
    if(name && !name.toLowerCase().endWith('.xml'))
    {
        name = name.trim() + '.xml';
    }
    $('#new-graph-modal').modal('hide')
    $('#graph-name-input').val("");
    var formData = new FormData();
    formData.append("name",name);
    formData.append("chunk",0);
    formData.append("chunks",1);
    formData.append("path",'');
    var file = new File([""],name, {type: "text/xml"});
    formData.append("file",file);
    var xhr = new XMLHttpRequest();
    xhr.onerror = function(e) {
        toastr["error"]("图表创建错误");
    };
    xhr.onloadend = function() {
        toastr["success"]("图表创建完成");
        xhr = null;
        var path = "/" + name;
        $.cookie("file_tmp",path,{ expires: 7, path: '/' });
        $.cookie("path_tmp",'',{ expires: 7, path: '/' });
        location = "/editor?share_key=" + shareInfo.shareId;
    };
    xhr.open("POST","/Upload",true);
    xhr.send(formData);
}

function audioPause() {
    document.getElementById('preview-target').pause();
    dp.pause();
}
$("#size").html(getSize(shareInfo.fileSize));
$("#down_num").html(shareInfo.downloadNum);
$("#view_num").html(shareInfo.ViewNum);
shareTime = formateDate(shareInfo.shareDate);
$("#share_time").html(shareTime);
$("#edit").click(function() {
    $('#new-graph-modal').modal('show');
});