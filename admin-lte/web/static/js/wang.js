/**
 * 表单提交后的提示信息
 * @param string type 类型 success:成功 error:失败  warning：警告
 * @param string title 标题
 * @param string content 提示内容
 * @param string timeOut 隐藏时间（ms）
 * @returns 
 */
function showToast(type, title, content,timeOut) {
    toastr.options = {
        "closeButton": true,//是否显示关闭按钮
        "debug": false,//是否使用debug模式
        "progressBar": false,
        "positionClass": "toast-top-center",//弹出窗的位置
        "onclick": null,
        "showDuration": "300",//显示的动画时间
        "hideDuration": "1000",//消失的动画时间
        "timeOut": timeOut,//展现时间
        "extendedTimeOut": "1000",//加长展示时间
        "showEasing": "swing",//显示时的动画缓冲方式
        "hideEasing": "linear",//消失时的动画缓冲方式
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    toastr[type](content, title);
}

/**
 * 设置表单错误信息提示
 * @param string parent 父类的class
 * @param string msg 提示信息
 * @returns 
 */
function setError(parent,msg){
    $('.'+parent).addClass('has-error');
    $('.'+parent+' .help-block-error').html(msg);
}

/**
 * 表单提交后的成功或失败的显示信息
 * @param string id id选择器的id
 * @param string type 类型 success or error
 * @param string $content 内容
 * @returns
 */
function getReturnInfo(id,type,$content){
    var alert = '';
    if(type == 'success'){
        alert = 'alert-success'
    }else if(type == 'error'){
        alert = 'alert-danger';
    }
    var str = '<div class="alert '+alert+' fade in">'+
            '<button data-dismiss="alert" class="close close-sm" type="button">'+
                '<i class="fa fa-times"></i>'+
            '</button>'+
            '<strong>'+$content+'</strong>'+
        '</div>';
    $('#'+id).append(str);
}
/**
 * 验证网址地址是否合法
 * @param {type} str_url
 * @returns {Boolean}
 */
function isUrl(str_url){
    var strRegex = /^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i;
    var re=new RegExp(strRegex);
    //re.test()
    if (re.test(str_url)){
        return (true);
    }else{
        return (false);
    }
}

/**
 * 判断文件是否是图片
 * @returns {undefined}
 */
function isImage(obj){
    filepath=$(obj).val();
    var extStart=filepath.lastIndexOf('.');
    var ext=filepath.substring(extStart,filepath.length).toUpperCase();
    if(ext!=".BMP"&&ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
        return false;
    }else{
        return true;
    }
}

function checkpass(pass){
    var rs = -1;
    $.ajax({
        url: "./index.php?r=site/pass" + "&pass=" + pass,
        type: 'get',
        async:false,
        dataType: 'json',
        //                data: {tel: $("#inp").val()}
        data: {
            pass: pass,
        },
        success: function (data) {
            if (data.code == 0) {
                rs = 0;
            } else {
                rs = data.code;
            }
        },

    });
    return rs;
}