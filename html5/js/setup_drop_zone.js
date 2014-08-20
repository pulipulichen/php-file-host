$(function () {
    var _drop_zone = $(".intro-header");
    //var _drop_zone = $(document);
    var _drop_zone_overlay = $(".drop-zone-overlay");
    
    _dragenter_callback = function () {
        var _top = _drop_zone.position().top;
        var _height = _drop_zone.height();
        
        var _padding_top = _drop_zone.css("padding-top");
        _padding_top = _padding_top.substr(0, _padding_top.length - 2);
        _padding_top = parseInt(_padding_top, 10);
        
        var _padding_bottom = _drop_zone.css("padding-bottom");
        _padding_bottom = _padding_bottom.substr(0, _padding_bottom.length - 2);
        _padding_bottom = parseInt(_padding_bottom, 10);
        _height = _height + _padding_top + _padding_bottom - 20;
        
        _drop_zone_overlay.css("top", _top + "px");
        _drop_zone_overlay.height(_height);
        if (_drop_zone_overlay.css("display") !== "block") {
            _drop_zone_overlay.fadeIn();
        }
    };
    
    _dragleave_callback = function (_callback) {
        if (_drop_zone_overlay.css("display") === "block") {
            _drop_zone_overlay.fadeOut(_callback);
        }
        else {
            if (_callback !== undefined) {
                _callback();
            }
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
});