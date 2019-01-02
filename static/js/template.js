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
        path = "/" + name;
        $.cookie("file_tmp",path);
        location = "/editor?libs=" + currentLibs + "&template=" + currentTemplate;
    };
    xhr.open("POST","/Upload",true);
    xhr.send(formData);
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