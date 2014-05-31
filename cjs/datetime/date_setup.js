jQuery(function(){
    jQuery.datepicker.regional['pt-BR'] = {
        clearText: 'Limpar',
        closeText: 'Fechar',
        prevText: '&lt;Anterior',
        nextText: 'Pr&oacute;ximo&gt;',
        currentText: 'Hoje',
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sabado'],
        dayNamesShort:['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
        dayNamesMin:['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
        monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort:['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        dateFormat: 'yy-mm-dd',
        firstDay: 0,
        weekHeader: 'Semana',
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: '',
        changeMonth: true,
        changeYear: true,
        yearRange: "1950:2012"
    };
    jQuery.datepicker.setDefaults(jQuery.datepicker.regional['pt-BR']);    
});