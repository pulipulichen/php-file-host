<?php
// 上傳檔案的目錄
$f3->set("UPLOADS", "uploads/");

// 檔案上傳上限。單位可以是B, KB, MB, GB, TB, PB
$f3->set("UPLOAD.filesize", "2M");

// 可接受的MIME檔案類型。
// 請查對應表 
// - http://www.sitepoint.com/web-foundations/mime-types-complete-list/
// - http://www.freeformatter.com/mime-types-list.html
// 以逗號「,」隔開
$f3->set("UPLOAD.mimetype", array(
     "text/plain"
    ,"text/html"
    ,"image/gif"
    ,"image/jpeg"
    ,"image/png"
    ,"audio/wav|5MB"
    ,"audio/x-wav|5MB"
    ,"audio/mpeg3|5MB"
    ,"audio/mp3|5MB"
    ,"audio/x-mpeg-3|5MB"
    ,"video/mpeg|5MB"
    ,"video/x-mpeg|5MB"
    ,"application/msword|10MB"
    ,"application/mspowerpoint|10MB"
    ,"application/powerpoint|10MB"
    ,"application/vnd.ms-powerpoint|10MB"
    ,"application/x-mspowerpoint|10MB"
    ,"application/vnd.openxmlformats-officedocument.presentationml.presentation|10MB"
    ,"application/vnd.openxmlformats-officedocument.wordprocessingml.document|10MB"
    ,"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|10MB"
    ,"application/excel|5MB"
    ,"application/vnd.ms-excel|5MB"
    ,"application/x-excel|5MB"
    ,"application/x-msexcel|5MB"
    ,"application/pdf|10MB"
    ,"application/x-compressed|10MB"
    ,"application/x-zip-compressed|10MB"
    ,"application/zip|10MB"
    ,"multipart/x-zip|10MB"
    ,"application/x-7z-compressed|10MB"
    ,"application/x-rar-compressed|10MB"
    ,"video/vnd.uvvu.mp4|10MB"
    ,"audio/mp4|5MB"
    ,"video/mp4|10MB"
    ,"application/mp4"
    ,"application/vnd.sun.xml.calc|10MB"
    ,"application/vnd.sun.xml.draw|10MB"
    ,"application/vnd.sun.xml.impress|10MB"
    ,"application/vnd.sun.xml.math|10MB"
    ,"application/vnd.sun.xml.writer|10MB"
    ,"application/vnd.sun.xml.writer.global|10MB"
    ,"image/svg+xml|10MB"
    ,"application/xhtml+xml"
    ,"application/json"
    ,"application/javascript"
));

// 允許上傳的來源，*設定表示任何網址都可以上傳
// 限定http://example.org /^http:\/\/example1.org/
$f3->set("UPLOAD.origin", "*");

// 選項：
// FALSE: 不壓縮
// "ZIP": Zip壓縮
// "BZIP": BZip2壓縮
$f3->set("UPLOAD.compression", "BZIP");

/**
 * 快取的限制檔案大小
 */
$f3->set("UPLOAD.cache_total_size_limit", "10GB");