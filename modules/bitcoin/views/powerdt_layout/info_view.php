<h3>Informações</h3>
<div id="info">
</div>
<h3>Recrute</h3>
<div id="recrut-status"></div>
<div id="recrut">
</div>
<button class="btn btn-danger" id="do_recrut">Recrutar</button>

<script>
    $(document).ready(function() {
        $("#do_recrut").click(function () {
            $("#do_recrut").slideUp();
            $.post("<?php echo site_url('site/recrutarNoSync') ?>", function(data){
                $("#recrut-status").html(data.content);
            });
        });
    });

    (function recrut(){
        $.post("<?php echo site_url('site/show_recrut') ?>", function(data){
            $("#recrut").html(data.content);
        });
        setTimeout(recrut, 5*1000);
    })();
    (function info(){
        $.post("<?php echo site_url('site/show_info') ?>", function(data){
            if(data.msg){
                show_msgs(data.msg);
            }
            $("#info").html(data.content);
        });
        setTimeout(info, 15*1000);
    })();
</script>