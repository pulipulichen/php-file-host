/*jslint unparam: true */
/*global window, $ */
$(function () {
    //'use strict';
    // Change this to the location of your server-side upload handler:
    // 設定上傳網址
    //var url = window.location.hostname === 'blueimp.github.io' ?
    //            '//jquery-file-upload.appspot.com/' : 'server/php/';
    //var url = "{{ @BASE }}/upload";
                
    // 開始設定檔案上傳
    var _progress = $('#progress');
    var _progress_bar = $('#progress .progress-bar');
    var _reset_key = undefined;
    var _files = $('#files');
    
    var _messages = _files.find(".message");
    var _message_success = _files.find(".message.success");
    _message_success.find("input").click(function () {
       this.select(); 
    });
    var _message_error = _files.find(".message.error");
    
    var _fileinput_button = $(".fileinput-button");
    
    var _drag_lock = false;
    
    $('#fileupload').fileupload({
        //url: url,
        dataType: 'json',
        add: function (e, data) {
            
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
            _drag_lock = false;
            
            _fileinput_button.removeClass("disabled");
        },
        /*dragover: function (e, data) {
            if (_drag_lock === false) {
                $("body").prepend("<div>!</div>");
                _drag_lock = true;
            }
        },
        */
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

    var _drop_zone = $(".intro-header");
    //var _drop_zone = $(document);
    var _drop_zone_overlay = $(".drag-zone-overlay");
    
    var _dragenter_callback = function () {
        var _top = _drop_zone.position().top;
        var _height = _drop_zone.height();
        
        var _padding_top = _drop_zone.css("padding-top");
        //console.log(_padding_top);
        _padding_top = _padding_top.substr(0, _padding_top.length - 2);
        _padding_top = parseInt(_padding_top, 10);
        
        var _padding_bottom = _drop_zone.css("padding-bottom");
        //console.log(_padding_bottom);
        _padding_bottom = _padding_bottom.substr(0, _padding_bottom.length - 2);
        _padding_bottom = parseInt(_padding_bottom, 10);
        //console.log(_height);
        _height = _height + _padding_top + _padding_bottom - 20;
        
        _drop_zone_overlay.css("top", _top + "px");
        _drop_zone_overlay.height(_height);
        //_drop_zone_overlay.css("display", "block");
        //$("body").prepend(_drop_zone_overlay.css("display") + "!");
        if (_drop_zone_overlay.css("display") !== "block") {
            _drop_zone_overlay.fadeIn();
        }
    };
    
    var _dragleave_callback = function (_callback) {
        //$("body").prepend(_drop_zone_overlay.css("display") + "?");
        if (_drop_zone_overlay.css("display") === "block") {
            _drop_zone_overlay.fadeOut(_callback);
        }
        else {
            _callback();
        }
    };
    
    var _drag_flag = 0;
    var _dragleave_timer;
    
    //var _d = $("<div />").html(_drag_flag).prependTo("body");
    
    $(document).on("dragover" , function () {
        if (_drag_flag === 0) {
            _drag_flag = 1;
            //console.log(_drag_flag);
            _dragenter_callback();
        }
        else if (_drag_flag === 1) {
            clearTimeout(_dragleave_timer);
            _dragleave_timer = setTimeout(function () {
                //console.log(_drag_flag + " timeout");
                if (_drag_flag === 1) {
                    _dragleave_callback(function () {
                        clearTimeout(_dragleave_timer);
                        _drag_flag = 0;
                        //console.log(_drag_flag);
                    });
                }
            }, 500);
            
        }
    });
    
    //_drop_zone_overlay.css("display", "block");
    //alert(_drop_zone_overlay.length);
//    /*
//    _drop_zone.on("dragover", function () {
//        if (_drag_lock === false) {
//            //$("body").prepend("<div>!</div>");
//            
//            // 把事件寫在這裡
//            
//            //_drop_zone_overlay.css("background-color", "red");
//            _drag_lock = "dragover";
//        }
//        else if (_drag_lock === "checkleave") {
//            _drag_lock = "dragover";
//        }
//        return false;
//    });
//    var _dragleave_timer = false;
//    _drop_zone.on("dragleave", function () {
//        if (_drag_lock === "dragover") {
//            //_dragleave_timer = setTimeout(function () {
//                _drag_lock = "checkleave";
//               
//               clearTimeout(_dragleave_timer);
//               _dragleave_timer = setTimeout(function () {
//                   if (_drag_lock === "checkleave") {
//                       
//                       // 結束事件寫在這裡
//                       //_drop_zone_overlay.hide();
//                       _drop_zone_overlay.fadeOut(function () {
//                           _drag_lock = false;
//                            clearTimeout(_dragleave_timer);
//                       });
//                   }
//               }, 1000);
//            //}, 500);
//        }
//        else if (_drag_lock === "checkleave") {
//            _drag_lock = "dragover";
//            /*
//            _dragleave_timer = setTimeout(function () {
//                if (_drag_lock === "wait") {
//
//                    // 結束事件寫在這裡
//                    //_drop_zone_overlay.hide();
//                    _drop_zone_overlay.fadeOut();
//
//                    _drag_lock = false;
//                    _dragleave_timer = false;
//                }
//            }, 500);
//            */
//        }
//        return false;
//    });
//    */
});