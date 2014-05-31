function mark_deletable() {
    
    jQuery('.delete').click(function(event) {
        event.preventDefault();
        url = $(this).attr('href');
        id = $(this).attr('data-id');
        model = $(this).attr('data-model');
        $.fn.dialog2.helpers.confirm("Tem certeza de que quer apagar?", {
            confirm: function() { 
                $.post(url, {id: id}, function(info) {
                    jQuery("#"+model+"_"+id).remove();
                    $("#info").html(info.msg);
                    message()
                }, 
                "json");
            }
        });       
    });
    
}

function show_response() {
    $.post($('#myModal form').attr('action'),$('#myModal form').serialize(), 
        function(data) {
            if (data.status == 'success')
            {
                location.reload();
            }
            else
            {
                $('#myModal').prepend(data.msg);
                message();
            }
        },
        'json'
        );
    return false;
}


//function mark_ajax_modal() {   
//    $('[data-toggle="modal"]').click(function(event) {
//        event.preventDefault();
//        var href = $(this).attr('href');
//        if (href.indexOf('#') == 0) {
//            $(href).modal('open');
//        } else {
//            $('<div/>').dialog2({
//                title: $(this).attr('title'), 
//                content: href, 
//                id: "server-notice",
//                success:       showResponse
//            });
//        }            
//    });    
//}

function mark_ajax_modal() {   
    $('[data-toggle="modal"]').click(function(event) {
        event.preventDefault();
        var href = $(this).attr('href');
        if (href.indexOf('#') == 0) {
            $(href).modal('open');
        } else {
            $.get(href, function(data) {
                var m = $('#myModal');
                m.modal();
                m.html(data.content);
                
                var af = m.find('form');
                af.submit(show_response); 
            }, 'json');
        }            
    });    
}

$(document).delegate('.modal', "dialog2.content-update", function() { 
     // got the dialog as this object. Do something with it!

    var e = $(this);

    var autoclose = e.find("a.auto-close");
    if (autoclose.length > 0) {
        e.dialog("close");

        var href = autoclose.attr('href');
        if (href) {
            window.location.href = href;
        }
    }
});

jQuery(document).ready(function($) {
    jQuery("#birthday, #foundation, #start_date, #end_date").datepicker();
    
    mark_deletable();
    mark_ajax_modal();
    
    $("#phone1").mask("(99) 9999-9999");
    $("#phone2").mask("(99) 9999-9999");
    $("#phone3").mask("(99) 9999-9999");
    $("#cnpj").mask("99.999.999/9999-99");
    $("#cpf").mask("999.999.999-99");
    $("#cep").mask("99999-999");
});


function formatP( ul, item ) {
    var re = new RegExp("^" + this.term) ;
    var label = item.name;
    var t     = label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(this.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong style='color:#DD4B39'>$1</strong>");

    return $( "<li></li>" )
    .data( "item.autocomplete", item )
    .append( '<a>'+
        '<div class="semi">'+
        '<div class="artwork">'+
        '<img src="'+item.profile_image+'">'+	
        '</div>' + t +
        '</div>'+
        "</a>" )
    .appendTo( ul );	
}