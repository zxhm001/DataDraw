String.prototype.endWith=function(endStr){
    var d = this.length-endStr.length;
    return (d >= 0 && this.lastIndexOf(endStr) == d)
}

String.prototype.trim = function()    
{    
    return this.replace(/(^\s*)|(\s*$)/g, "");    
}    

function getMemory() {
    $.get("/Member/Memory", function(data) {
        var dataObj = eval("(" + data + ")");
        if (dataObj.rate >= 100) {
            $("#memory_bar").css("width", "100%");
            $("#memory_bar").addClass("progress-bar-warning");
            toastr["error"]("您的已用容量已超过容量配额，请尽快删除多余文件或购买容量");

        } else {
            $("#memory_bar").css("width", dataObj.rate + "%");
        }
        $("#used").html(dataObj.used);
        $("#total").html(dataObj.total);
    });
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
    var file = new File(['<mxGraphModel dx="1332" dy="851" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="827" pageHeight="1169"><root><mxCell id="0"/><mxCell id="1" parent="0"/></root></mxGraphModel>'], name, { type: "text/xml" });
    uploader.bind('FileUploaded', function (up, file) {
        toastr["success"]("图表创建完成");
        xhr = null;
        var path = "/" + name;
        $.cookie("file_tmp",path,{ expires: 7, path: '/' });
        $.cookie("path_tmp",'',{ expires: 7, path: '/' });
        location = "/editor?libs=" + currentLibs + "&template=" + currentTemplate;
    });
    uploader.bind('Error', function (up, file) {
        toastr["error"]("图表创建错误");
    });
    uploader.addFile(file);
}

var isvip = false;
var currentLibs = '';
var currentTemplate = '';

$.post('/Vip/isVip',function(response){
    isvip = response;
});

window.onload = function() {
    $.material.init();
    getMemory();
    $("[href^='/Template']").addClass("active");


    $('.thumblink').click(function(e){
        var libs = $(e.currentTarget).data('libs');
        var template = $(e.currentTarget).data('template');
        if(isvip)
        {
            currentLibs = libs;
            currentTemplate = template;
            $('#new-graph-modal').modal('show');
        }
        else
        {
            $('#new-vip-modal').modal('show');
        }
    });
}

//加载上传组件
$.getScript("/static/js/moxie.js", function () {
    $.getScript("/static/js/plupload.dev.js", function () {
        $.getScript("/static/js/qiniu.js", function () {
            $.getScript("/static/js/main.js");
			toastr.clear();
        });
    });
});