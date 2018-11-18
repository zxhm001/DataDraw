// urlParams is null when used for embedding
window.urlParams = window.urlParams || {};

// Public global variables
window.MAX_REQUEST_SIZE = window.MAX_REQUEST_SIZE  || 10485760;
window.MAX_AREA = window.MAX_AREA || 15000 * 15000;

// URLs for save and export
window.EXPORT_URL = window.EXPORT_URL || '/Graph/Export';
window.EDIT_URL = window.EDIT_URL || '/File/Edit';
window.CONTENT_URL = window.CONTENT_URL||'/File/Content';
window.SAVE_URL = window.SAVE_URL || '/Upload';
//window.SAVE_PATH = window.getCookieByString('path_tmp') || '';
window.FILE_PATH = $.cookie('file_tmp') || '';
window.DIRECTORY = $.cookie('path_tmp') || '';
window.OPEN_URL = window.OPEN_URL || '/File/ListFile';
window.RESOURCES_PATH = window.RESOURCES_PATH || '/static/editor/resources';
window.RESOURCE_BASE = window.RESOURCE_BASE || window.RESOURCES_PATH + '/grapheditor';
window.STENCIL_PATH = window.STENCIL_PATH || '/static/editor/stencils';
window.IMAGE_PATH = window.IMAGE_PATH || '/static/editor/images';
window.STYLE_PATH = window.STYLE_PATH || '/static/editor/styles';
window.CSS_PATH = window.CSS_PATH || '/static/editor/styles';
window.OPEN_FORM = window.OPEN_FORM || '/static/editor/open.html';

// Sets the base path, the UI language via URL param and configures the
// supported languages to avoid 404s. The loading of all core language
// resources is disabled as all required resources are in grapheditor.
// properties. Note that in this example the loading of two resource
// files (the special bundle and the default bundle) is disabled to
// save a GET request. This requires that all resources be present in
// each properties file since only one file is loaded.
window.mxBasePath = window.mxBasePath || '/static/mxgraph/src';
// window.mxLanguage = window.mxLanguage || urlParams['lang'];
// window.mxLanguages = window.mxLanguages || ['de'];
window.mxLanguage = 'zh' || urlParams['lang'];
window.mxLanguages = window.mxLanguages || ['zh'];

String.prototype.endWith=function(endStr){
    var d = this.length-endStr.length;
    return (d >= 0 && this.lastIndexOf(endStr) == d)
}
