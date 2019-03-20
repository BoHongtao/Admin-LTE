function fbwindow(title, url, size) {
    $('#myModal').remove();
    if (size === 'l') {
        size = 'modal-lg';
    } else if (size === 's') {
        size = 'modal-sm';
    } else {
        size = '';
    }
    var _window = '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
        + '<div class="modal-dialog ' + size + '">'
        + '<div class="modal-content-wrap" >'
        + '<div class="modal-content">'
        + '<div class="modal-header">'
        + '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="closeModal()">&times;</button>'
        + '<h4 class="modal-title">' + title + '</h4>'
        + '</div>'
        + '<div class="modal-body" id="modalUrl" style="max-height: 500px;overflow-x: hidden;margin: 20px;padding: 0px;">'
        + '<iframe id="furl" name="furl" src="' + url + '"width="100%"  scrolling="auto" frameborder="0" marginheight="0" marginwidth="0" onLoad="reinitIframeEND();">'
        + '</div>'
        + '</div>'
        + '</div>'
        + '</div>'
        + '</div>';
    $('body').append(_window);
    $('#modalUrl').load(url);
    $('#myModal').modal();
}

function motai(title, url, size) {
    $('#myModal').remove();
    if (size === 'l') {
        size = 'modal-lg';
    } else if (size === 's') {
        size = 'modal-sm';
    } else {
        size = '';
    }
    var _window = '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">'
        + '<div class="modal-dialog ' + size + '">'
        + '<div class="modal-content-wrap" >'
        + '<div class="modal-content">'
        + '<div class="modal-header">'
        + '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="closeModal()">&times;</button>'
        + '<h4 class="modal-title">' + title + '</h4>'
        + '</div>'
        + '<div class="modal-body" id="modalUrl">'
        + '<iframe id="furl" name="furl" src="' + url + '"width="100%"  scrolling="auto" frameborder="0" marginheight="0" marginwidth="0" onLoad="reinitIframeEND();">'
        + '</div>'
        + '</div>'
        + '</div>'
        + '</div>'
        + '</div>';
    $('body').append(_window);
    $('#modalUrl').load(url);
    $('#myModal').modal();
}
function mobilePreview(title, url, size) {
    $('#myModal').remove();
    if (size === 'l') {
        size = 'modal-lg';
    } else if (size === 's') {
        size = 'modal-sm';
    } else {
        size = '';
    }
    var _window = '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
        + '<div class="modal-dialog ' + size + '"  style="width: 350px; padding-bottom: 0;">'
        + '<div class="modal-content-wrap" >'
        + '<div class="yl_box" id="modalUrl">'
        + '<iframe id="furl" class="iframe_box" name="furl" src="' + url + '" width="100%"  scrolling="auto" frameborder="0" marginheight="0" marginwidth="0" onLoad="reinitIframeEND();">'
        + '</div>'
        + '</div>'
        + '</div>'
        + '</div>';
    $('body').append(_window);
    //$('#modalUrl').load(url);
    $('#myModal').modal();
}

function closeModal() {
    $('#myModal').modal('hide');
}

// iframe高度自适应
function reinitIframe() {
    var iframe = document.getElementById("furl");
    try {
        var bHeight = iframe.contentWindow.document.body.scrollHeight;
        var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
        var height = Math.max(bHeight, dHeight);
        iframe.height = height;
    } catch (ex) {
    }
}

var timer1 = window.setInterval("reinitIframe()", 500); //定时开始
function reinitIframeEND() {
    var iframe = document.getElementById("furl");
    try {
        var bHeight = iframe.contentWindow.document.body.scrollHeight;
        var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
        var height = Math.max(bHeight, dHeight);
        // console.log('scrollHeight=='+bHeight+'dHeight'+dHeight);
        iframe.height = height;
    } catch (ex) {
    }
    // 停止定时
    window.clearInterval(timer1);
}