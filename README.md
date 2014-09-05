PHP File Host
=============

PHP檔案上傳服務。具備API，可與其他服務介接。

----------

安裝教學：POST & GET
=========

**[php-file-host]**是指本伺服器。

Step 1. 上傳檔案
------------
設定form的action屬性，上傳到 **[php-file-host]/upload**。

上傳檔案有三種方式：

- 使用檔案類型(file)表單欄位，欄位名稱(name)叫做「file」 。
網頁的寫法範例如下：
```html
<form action="[php-file-host]/upload" method="post" enctype="multipart/form-data">
	<input type="file" name="file" />
	<button type="submit">Submit</button>
</form>
```
- 使用Base64的方式上傳檔案。使用文字類型(text)表單欄位，欄位名稱(name)叫做「**file**」，內容必須輸入Base64的資料，例如：「data:audio/mp3;base64,//sQxAAARExPNyMEZkCZCehkkwjgABAIAgAJhdEAQABDsbu8AE8IAJX8+F+icoc+HyHlAQd6w+A1KAFDIdKAmRLqoSVRrmEifQ3Qc8Gj7OvcDaoh...(後面是省略)」。同時搭配欄位名稱叫做「**fname**」的文字類型表單欄位，輸入檔案名稱，例如「audio.mp3」。
網頁的寫法範例如下：
```html
<form action="[php-file-host]/upload" method="post">
	<input type="text" name="file" />
	<input type="text" name="fname" />
	<button type="submit">Submit</button>
</form>
```

以上兩種通常會搭配iframe實作AJAX。網頁寫法的範例如下：
```html
<form action="[php-file-host]/upload" method="post" enctype="multipart/form-data" target="php_file_host_iframe">
	<input type="file" name="file" />
	<button type="submit">Submit</button>
</form>
<iframe name="php_file_host_iframe" onload="get_link()" />
```

get_link()的用處請看下面的說明。

Step 2. 取得網址
--------------

表單遞交之後，以GET方式取得 **[php-file-host]/get_link**。

你可以使用jQuery的方法，例如：

```JavaScript
var get_link = function () {
	jQuery.getJSON("[php-file-host]/get_link?callback=?", function (_link) {
		alert(_link); // 取得檔案
	});
}
```

Step 3. 從該網址下載檔案
--------------

Step 2取得的網址可直接下載使用。

安裝教學：jQuery File Upload
===============

PHP File Host也可以接受使用jQuery File Upload直接上傳。

Step 1. 載入jQuery File Upload
-------------------

- [jQuery File Upload專案網站](http://blueimp.github.io/jQuery-File-Upload/)
- [jQuery File Upload下載](https://github.com/blueimp/jQuery-File-Upload/tags)

載入說明請看[jQuery File Upload的最小化安裝](https://github.com/blueimp/jQuery-File-Upload/wiki/Basic-plugin)。

Step 2. 設定上傳表單
-------------------

HTML表單設定如下：
```html
<form action="[php-file-host]/upload" method="post" enctype="multipart/form-data">
	<input type="file" name="file" />
</form>
```

Step 3. 設定jQuery File Upload
------------------

最小化設定為：
```JavaScript
$('#fileupload').fileupload({
        dataType: 'json',
        postMessage: "[php-file-host]/postmessage",
        add: function (e, data) {
            data.submit();
        },
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                if (typeof(file.error) !== 'string') { 
	                console.log("File uploaded: " + file.name + " / " + file.url);
                }
                else {
	                console.log("File upload error: " + file.error);
                }
            });
        }
    });
```

這樣就可以上傳了。

進階設定
--------------------

如果想要上傳進度條等功能，請看範例檔案的設定：

- HTML表單的部分：[landing_page.intro_header.html](https://github.com/pulipulichen/php-file-host/blob/master/views/landing_page.intro_header.html)
- JavaScript設定jQuery File Upload的部分：[setup_jquery_fileupload.js](https://github.com/pulipulichen/php-file-host/blob/master/html5/js/setup_jquery_fileupload.js#L12)
- JavaScript設定拖曳上傳的部分：[setup_drop_zone.js](https://github.com/pulipulichen/php-file-host/blob/master/html5/js/setup_drop_zone.js)

----------


Update 
======

- 20140903：大幅加入PHP File Host說明。
- 20140903：接受以Base64方式上傳檔案。
你可以用<input type="text" name="file" />保存檔案內容，然後用<input type="text" name="fname" />保存檔案名稱，接著只要以POST遞交檔案即可。