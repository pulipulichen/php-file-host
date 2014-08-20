/*jslint unparam: true */
/*global window, $ */
$(function () {
    //'use strict';
    // Change this to the location of your server-side upload handler:
    // 設定上傳網址
    //var url = window.location.hostname === 'blueimp.github.io' ?
    //            '//jquery-file-upload.appspot.com/' : 'server/php/';
    var url = "{{ @BASE }}/upload";
                
    // 開始設定檔案上傳
    var _progress = $('#progress');
    var _progress_bar = $('#progress .progress-bar');
    var _reset_key = undefined;
    var _files = $('#files');
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        add: function (e, data) {
            
            if (_progress_bar.width() === 0) {
                data.submit();
            }
            else {
                var _temp = _progress.clone();
                _temp.find(".progress-bar").width(0);
                _temp.insertAfter(_progress);
                _progress.remove();
                _progress = $('#progress');
                _progress_bar = $('#progress .progress-bar');
                
                data.submit();
            }
        
            clearTimeout(_reset_key);
            
            _files.empty();
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            _progress_bar.css(
                'width',
                progress + '%'
            );
        },
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                var _link;
                //alert([typeof(file.url), typeof(file.error),file.error]);
                if (typeof(file.error) !== 'string') { 
                    var _a = $('<a class="btn btn-success btn-lg" role="button" />').attr("href", file.url).text(file.name)
                            .prepend($('<i class="fa fa-file" style="padding-right: 0.5em"></i> '));
                    
                }
                else {
                    _link = $('<span class="label label-danger" />').text(file.error).addClass('error');
                }
                $('<p/>').html(_link).appendTo(_files);
            });
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});