function getAjax(url,data,success){
    var params=typeof data=='string'?data:Object.keys(data).map(function(k){return encodeURIComponent(k)+'='+encodeURIComponent(data[k])}).join('&');
    var xhr=window.XMLHttpRequest?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP");
    xhr.open('GET',url);
    xhr.setRequestHeader('X-CSRF-TOKEN',$('meta[name="_token"]').attr('content'));
    xhr.onreadystatechange=function(){
        if(xhr.readyState>3&&xhr.status==200){
            success(xhr.responseText);
            }
        if(xhr.readyState>3&&xhr.status!==200){
            success(xhr.responseText);
        }
    };
    xhr.setRequestHeader('X-Requested-With','XMLHttpRequest');
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    xhr.send(params);
    return xhr;
}
function postAjax(url,data,success) {
    var params=typeof data=='string'?data:Object.keys(data).map(function(k){
        return encodeURIComponent(k)+'='+encodeURIComponent(data[k])}).join('&');var xhr=window.XMLHttpRequest?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP");
        xhr.open('POST',url);
        xhr.setRequestHeader('X-CSRF-TOKEN',$('meta[name="_token"]').attr('content'));
        xhr.onreadystatechange=function(){
            if(xhr.readyState>3&&xhr.status==200){
            success(xhr.responseText);
            }
            if(xhr.readyState>3&&xhr.status!==200){
                success(xhr.responseText);
            }
        };
        xhr.setRequestHeader('X-Requested-With','XMLHttpRequest');
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        xhr.send(params);return xhr;
}

$(function (){
   if ($("body").hasClass('layout-top-nav')) {

   }
});
