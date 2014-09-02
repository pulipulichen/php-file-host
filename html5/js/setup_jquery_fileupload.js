/*jslint unparam: true */
/*global window, $ */
$(function () {
    // 開始設定檔案上傳
    var _progress = $('#progress');
    var _progress_bar = $('#progress .progress-bar');
    var _files = $('#files');
    
    var _messages = _files.find(".message");
    var _message_success = _files.find(".message.success");
    _message_success.find("input").click(function () {
       this.select(); 
    });
    var _message_error = _files.find(".message.error");
    
    var _fileinput_button = $(".fileinput-button");
    
    $('#fileupload').fileupload({
        //url: url, // 網址參考 <form>的action屬性
        dataType: 'json',
        //postMessage: "http://example.org/php-file-host/postmessage",
        add: function (e, data) {
            
            _dragleave_callback();
            
            _fileinput_button.addClass("disabled");
            
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
                
                _messages.fadeOut(function () {
                    data.submit();
                });
            }
        
            //clearTimeout(_reset_key);
            
            //_files.empty();
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            _progress_bar.css(
                'width',
                progress + '%'
            );
        },
        done: function (e, data) {
            //console.log([data, typeof(data), data.result, typeof(data.result)]);
            $.each(data.result.files, function (index, file) {
                var _link;
                //alert([typeof(file.url), typeof(file.error),file.error]);
                if (typeof(file.error) !== 'string') { 
                    //var _a = $('<a class="btn btn-success btn-lg" role="button" />').attr("href", file.url).text(file.name)
                    //        .prepend($('<i class="fa fa-file" style="padding-right: 0.5em"></i> '));
                    
                    _message_success.find("a").attr("href", file.url);
                    _message_success.find("a span").html(file.name);
                    _message_success.find("input").val(file.url);
                    _message_success.fadeIn();
                }
                else {
                    //_link = $('<span class="label label-danger" />').text(file.error).addClass('error');
                    _message_error.html(file.error);
                    _message_error.fadeIn();
                }
                //$('<p/>').html(_link).appendTo(_files);
            });
            
            _fileinput_button.removeClass("disabled");
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');    
});