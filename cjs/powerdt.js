function show_msgs(data){
    if(!$.isArray(data)){
        var tmp = data;
        var data = new Object();
        data[0] = tmp;
    }
    var msg = '';
    for (var i in data){
        msg += data[i];
    }
    $("#alerts").html(msg);
    $("#alerts").show();

/*    setTimeout(function(){
        jQuery(".alert").fadeOut("slow", function () {
            jQuery(".alert").remove();
        });
    }, 8000);*/
}