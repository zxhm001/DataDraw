function getCookieByString(cookieName) {
    var start = document.cookie.indexOf(cookieName + '=');
    if (start == -1) return false;
    start = start + cookieName.length + 1;
    var end = document.cookie.indexOf(';', start);
    if (end == -1) end = document.cookie.length;
    return document.cookie.substring(start, end);
}

if (uploadConfig.saveType == "oss" || uploadConfig.saveType == "upyun" || uploadConfig.saveType == "s3") {
    ChunkSize = "0";
} else {
    ChunkSize = "4mb";
}

function newUploader(token_url) {
    var uploader = Qiniu.uploader({
        runtimes: 'html5,flash,html4',
        browse_button: 'pickfiles',
        container: 'container',
        drop_element: 'container',
        max_file_size: uploadConfig.maxSize,
        flash_swf_url: '/bower_components/plupload/js/Moxie.swf',
        dragdrop: true,
        chunk_size: ChunkSize,
        filters: {
            mime_types: uploadConfig.allowedType,
        },
        multi_selection: !(moxie.core.utils.Env.OS.toLowerCase() === "ios"),
        uptoken_url: token_url + "?item=" + getCookieByString("file_tmp"),
        domain: "https://www.myshuju.me/",
        get_new_uptoken: true,
        auto_start: true,
        log_level: 5,
        init: {
            'FilesAdded': function (up, files) {
                $.cookie('path', decodeURI(getCookieByString("path_tmp")));
            },
        }
    });
    return uploader;
}