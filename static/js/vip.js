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

window.onload = function() {
    $.material.init();
    getMemory();
    $("[href^='/Vip']").addClass("active");


    var clipboard = new ClipboardJS('#btn-copy');
    clipboard.on('success', function(e) {
        toastr["success"]("复制成功！");
    });
    clipboard.on('error', function(e) {
        toastr["error"]("复制失败，请手动复制！");
    });
    
    $('#btn-pay').click(function(){
        var payment = $("input[name='payment']:checked").val();
        if(payment=='alipay')
        {
            window.open("/Pay/Alipay",'_blank');
        }
        else if(payment=='wxpay')
        {

        }
        $('waiting-pay-modal').modal('show');
    });
}
