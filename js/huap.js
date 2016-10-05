// JavaScript Document

var date = new Date();
var d = date.getDate();
var m = date.getMonth() + 1;
var y = date.getFullYear();
var n = date.toISOString();
var tam = n.length - 5;
var agora = n.substring(0, tam);

/*
 var items = [];
 alert('la vai');
 $.getJSON("dt.json", function (data) {
 
 
 $.each(data, function (key, val) {
 items.push(val + '<br>');
 });
 
 
 alert('opa');
 });
 $("#demo").html(items);
 alert('fim');
 */

$.getJSON("dt.json", function (result) {

    var $teste = $("#teste");
    $teste.empty();

    for (var i = 0; i < result.length; i++) {
        //options += '<option value="' + result[i].ImageFolderID + '">' + result[i].Name + '</option>';
        $teste.append("<option>" + result.value + "</option>");
        alert(result.length);
    }
    
});

$("#first-choice").change(function () {

    var $dropdown = $(this);
    var items = [];

    $.getJSON("dt.json", function (data) {

        $.each(data, function (key, val) {
            items.push(val + '<br>');
        });

        $.getJSON("data.json", function (data) {

            var key = $dropdown.val();
            var vals = [];

            if (key == 'beverages')
                vals = data.beverages.split(",");
            else if (key == 'snacks')
                vals = data.snacks.split(",");
            else
                vals = ['Please choose from above'];

            var $secondChoice = $("#second-choice");
            $secondChoice.empty();
            $.each(vals, function (index, value) {
                $secondChoice.append("<option>" + value + "</option>");
            });
            $(".Chosen").trigger("chosen:updated");
        });

        $("#demo").html(items);
        //alert('opa');
    });

});

$(document).ready(function () {



    $(".Date").mask("99/99/9999");
    $(".Time").mask("99:99");
    $(".Cpf").mask("999.999.999-99");
    $(".Cep").mask("99999-999");
    $(".TituloEleitor").mask("9999.9999.9999");
    $('.Chosen').chosen({
        disable_search_threshold: 10,
        multiple_text: "Selecione uma ou mais opções",
        single_text: "Selecione uma opção",
        no_results_text: "Nenhum resultado para",
        width: "100%"
    });
    $("button.fc-today-button").click(function () {
//datepickerinline.today(this);
        $('#datepickerinline').datetimepicker({
            today: '2011-01-01',
        });
        alert(date);
    });
    $('.DatePicker').datetimepicker({
        tooltips: {
            today: 'Hoje',
            clear: 'Limpar seleção',
            close: 'Fechar este menu',
            selectMonth: 'Selecione um mês',
            prevMonth: 'Mês anterior',
            nextMonth: 'Próximo mês',
            selectYear: 'Selecione um ano',
            prevYear: 'Ano anterior',
            nextYear: 'Próximo ano',
            selectDecade: 'Selecione uma década',
            prevDecade: 'Década anterior',
            nextDecade: 'Próxima década',
            prevCentury: 'Centenário anterior',
            nextCentury: 'Próximo centenário',
            incrementHour: 'Aumentar hora',
            decrementHour: 'Diminuir hora',
            incrementMinute: 'Aumentar minutos',
            decrementMinute: 'Diminuir minutos',
            incrementSecond: 'Aumentar segundos',
            decrementSecond: 'Diminuir segundos'
        },
        showTodayButton: true,
        showClose: true,
        format: 'DD/MM/YYYY',
        minDate: moment(m + '/' + d + '/' + y),
        locale: 'pt-br'
    });
    $('.TimePicker').datetimepicker({
        tooltips: {
            today: 'Hoje',
            clear: 'Limpar seleção',
            close: 'Fechar este menu',
            selectMonth: 'Selecione um mês',
            prevMonth: 'Mês anterior',
            nextMonth: 'Próximo mês',
            selectYear: 'Selecione um ano',
            prevYear: 'Ano anterior',
            nextYear: 'Próximo ano',
            selectDecade: 'Selecione uma década',
            prevDecade: 'Década anterior',
            nextDecade: 'Próxima década',
            prevCentury: 'Centenário anterior',
            nextCentury: 'Próximo centenário',
            incrementHour: 'Aumentar hora',
            decrementHour: 'Diminuir hora',
            incrementMinute: 'Aumentar minutos',
            decrementMinute: 'Diminuir minutos',
            incrementSecond: 'Aumentar segundos',
            decrementSecond: 'Diminuir segundos',
        },
        showTodayButton: true,
        showClose: true,
        //stepping: 30,
        format: 'HH:mm',
        locale: 'pt-br'
    });
});
$('#datepickerinline').datetimepicker({
    tooltips: {
        today: 'Hoje',
        clear: 'Limpar seleção',
        close: 'Fechar este menu',
        selectMonth: 'Selecione um mês',
        prevMonth: 'Mês anterior',
        nextMonth: 'Próximo mês',
        selectYear: 'Selecione um ano',
        prevYear: 'Ano anterior',
        nextYear: 'Próximo ano',
        selectDecade: 'Selecione uma década',
        prevDecade: 'Década anterior',
        nextDecade: 'Próxima década',
        prevCentury: 'Centenário anterior',
        nextCentury: 'Próximo centenário',
        incrementHour: 'Aumentar hora',
        decrementHour: 'Diminuir hora',
        incrementMinute: 'Aumentar minutos',
        decrementMinute: 'Diminuir minutos',
        incrementSecond: 'Aumentar segundos',
        decrementSecond: 'Diminuir segundos'
    },
    inline: true,
    showTodayButton: true,
    //showClear: true,
    format: 'DD/MM/YYYY',
    //defaultDate: '2015-01-01',
    locale: 'pt-br'
});
$("#datepickerinline").on("dp.change", function (e) {
    var d = new Date(e.date);
    $('#calendar').fullCalendar('gotoDate', d);
});
/*
 * veio junto com o último datetimepicker, não parei pra analisar sua relevância
 * vou deixar aqui por enquanto
 * http://eonasdan.github.io/bootstrap-datetimepicker/
 * */

ko.bindingHandlers.dateTimePicker = {
    init: function (element, valueAccessor, allBindingsAccessor) {
        //initialize datepicker with some optional options
        var options = allBindingsAccessor().dateTimePickerOptions || {};
        $(element).datetimepicker(options);
        //when a user changes the date, update the view model
        ko.utils.registerEventHandler(element, "dp.change", function (event) {
            var value = valueAccessor();
            if (ko.isObservable(value)) {
                if (event.date != null && !(event.date instanceof Date)) {
                    value(event.date.toDate());
                } else {
                    value(event.date);
                }
            }
        });
        ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
            var picker = $(element).data("DateTimePicker");
            if (picker) {
                picker.destroy();
            }
        });
    },
    update: function (element, valueAccessor, allBindings, viewModel, bindingContext) {

        var picker = $(element).data("DateTimePicker");
        //when the view model is updated, update the widget
        if (picker) {
            var koDate = ko.utils.unwrapObservable(valueAccessor());
            //in case return from server datetime i am get in this form for example /Date(93989393)/ then fomat this
            koDate = (typeof (koDate) !== 'object') ? new Date(parseFloat(koDate.replace(/[^0-9]/g, ''))) : koDate;
            picker.date(koDate);
        }
    }
};
function EventModel() {
    this.ScheduledDate = ko.observable('');
}
ko.applyBindings(new EventModel());
/*
 $("#inputDate").mask("99/99/9999");
 $("#inputDate0").mask("99/99/9999");
 $("#inputDate1").mask("99/99/9999");
 $("#inputDate2").mask("99/99/9999");
 $("#inputDate3").mask("99/99/9999");
 $("#Cpf").mask("999.999.999-99");
 $("#Cep").mask("99999-999");
 $("#TituloEleitor").mask("9999.9999.9999");
 */

$('#popoverData').popover();
$('#popoverOption').popover({trigger: "hover"});
var tempo = 5 * 60 * 60 * 1000;
//var tempo = 10 * 1000;
//var date = new Date(new Date().valueOf() + 60 * 60 * 1000);
var date = new Date(new Date().valueOf() + tempo);
$('#clock').countdown(date, function (event) {
    $(this).html(event.strftime('%H:%M:%S'));
});
var branco = tempo - 1200000;
$('#countdowndiv').delay(branco).queue(function () {
    $(this).addClass("btn-warning");
});
$('#submit').on('click', function () {
    var $btn = $(this).button('loading')
})

jQuery(document).ready(function ($) {
    $(".clickable-row").click(function () {
        window.document.location = $(this).data("href");
    });
});
setTimeout(function () {
    $('#hidediv').fadeOut('slow');
}, 3000); // <-- time in milliseconds

setTimeout(function () {
    $('#hidediverro').fadeOut('slow');
}, 10000); // <-- time in milliseconds

$(document).ready(function () {
    $(".js-data-example-ajax").select2({
        ajax: {
            url: "https://api.github.com/search/repositories",
            //url: "http://localhost/sisgef/testebd.php",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, page) {
                // parse the results into the format expected by Select2.
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data
                return {
                    results: data.items
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1
    });
});
$(document).ready(function () {
    $(".js-example-basic-single").select2();
});
//Determina a raiz do site
var pathArray = window.location.pathname.split('/');
var basePath = window.location.protocol + "//" + window.location.host + "/" + pathArray[1];
$("#series").remoteChained({
    parents: "#mark",
    url: basePath + "/api/teste.php"
});
$("#StatusAntigo").remoteChained({
    parents: "#Especialidade",
    url: basePath + "/api/teste.php",
    loading: "Carregando...",
});
$('#Chosen').chosen({
    disable_search_threshold: 10,
    multiple_text: "Selecione uma ou mais opções",
    single_text: "Selecione uma opção",
    no_results_text: "Nenhum resultado para",
    width: "100%"
});
$('#id_Municipio').chosen({
    disable_search_threshold: 10,
    multiple_text: "Selecione uma ou mais opções",
    single_text: "Selecione uma opção",
    no_results_text: "Nenhum resultado para",
    width: "70%"
});
$('#Uf').chosen({
    disable_search_threshold: 10,
    multiple_text: "Selecione uma ou mais opções",
    single_text: "Selecione uma opção",
    no_results_text: "Nenhum resultado para",
    width: "70%"
});
$('#Municipio').chosen({
    disable_search_threshold: 10,
    multiple_text: "Selecione uma ou mais opções",
    single_text: "Selecione uma opção",
    no_results_text: "Nenhum resultado para",
    width: "70%"
});
$('#EstadoCivil').chosen({
    disable_search_threshold: 10,
    multiple_text: "Selecione uma ou mais opções",
    single_text: "Selecione uma opção",
    no_results_text: "Nenhum resultado para",
    width: "70%"
});
$('#Especialidade').chosen({
    disable_search_threshold: 10,
    multiple_text: "Selecione uma ou mais opções",
    single_text: "Selecione uma opção",
    no_results_text: "Nenhum resultado para",
    width: "100%"
});
$('#Cid').chosen({
    disable_search_threshold: 10,
    multiple_text: "Selecione uma ou mais opções",
    single_text: "Selecione uma opção",
    no_results_text: "Nenhum resultado para",
    width: "100%"
});
$('#Prestador').chosen({
    disable_search_threshold: 10,
    multiple_text: "Selecione uma ou mais opções",
    single_text: "Selecione uma opção",
    no_results_text: "Nenhum resultado para",
    width: "100%"
});
$('#Cirurgia').chosen({
    disable_search_threshold: 10,
    multiple_text: "Selecione uma ou mais opções",
    single_text: "Selecione uma opção",
    no_results_text: "Nenhum resultado para",
    width: "100%"
});
$('#Status').chosen({
    disable_search_threshold: 10,
    multiple_text: "Selecione uma ou mais opções",
    single_text: "Selecione uma opção",
    no_results_text: "Nenhum resultado para",
    width: "100%"
});
$('#Posicao').chosen({
    disable_search_threshold: 10,
    multiple_text: "Selecione uma ou mais opções",
    single_text: "Selecione uma opção",
    no_results_text: "Nenhum resultado para",
    width: "100%"
});
$('#calendar').fullCalendar({
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
    },
    eventSources: [{
            url: 'Consulta_json.php', // use the `url` property
        }],
    //allDayDefault: true,
    defaultView: 'agendaWeek',
    //contentHeight: 700,
    height: 'auto',
    //handleWindowResize: false,
    //aspectRatio: 2,

    minTime: '07:00',
    maxTime: '21:00',
    //minTime: '00:00',
    //maxTime: '24:00',
    nowIndicator: true,
    selectable: true,
    //selectHelper: true,
    editable: false,
    timezone: "local",
    lang: 'pt-br',
    eventAfterRender: function (event, element) {

        if (event.Evento == 1)
            var title = "<b>Evento Agendado</b><br><br><b>Obs:</b> " + event.Obs;
        else
            var title = "<b>" + event.title + "</b><br><br><b>Tipo de Consulta:</b> " + event.TipoConsulta + "<br><b>Procedimento:</b> " + event.Procedimento;
        $(element).tooltip({
            title: title,
            container: 'body',
            position: {my: "left bottom-3", at: "center top"},
            placement: 'auto top',
            html: true,
            delay: {"show": 500, "hide": 100},
            template: '<div class="tooltip" role="tooltip" ><div class="tooltip-inner" \n\
                    style="color: #000; border-radius: 4px; text-align: left; border-width: thin; border-style: solid; \n\
                    border-color: #000; background-color: #fff; white-space:pre-wrap;"></div></div>'
        });
    },
    selectConstraint: {
        start: agora,
        end: '2099-12-31T23:59:00'
    },
    select: function (start, end, jsEvent, view) {
        //var re = new RegExp(/^.*\//);
        //window.location = re.exec(window.location.href) + 'paciente/pesquisar?start=' + start + '&end=' + end;

        //alert(start + ' :: ' + end);

        //endtime = $.fullCalendar.formatDate(end, 'HH:mm');
        //starttime = $.fullCalendar.formatDate(start, 'DD/MM/YYYY, HH:mm');
        //var slot = 'start=' + start + '&end=' + end;

        $('#fluxo #start').val(start);
        $('#fluxo #end').val(end);
        //$('#fluxo #slot').text(slot);
        $('#fluxo').modal('show');
    },
});
/*
 * Redireciona o usuário de acordo com a opção escolhida no modal da agenda,
 * que surge ao clicar em algum slot de tempo vazio.
 */
function redirecionar(x) {

    var re = new RegExp(/^.*\//);
    var start = moment($("#start").val());
    var end = moment($("#end").val());
    (x == 1) ? url = 'consulta/cadastrar_evento' : url = 'paciente/pesquisar';
    window.location = re.exec(window.location.href) + url + '?start=' + start + '&end=' + end
}

/*
 * Função para capturar a url com o objetivo de obter a data, após criar/alterar 
 * uma consulta, e assim usar a função gotoDate do Fullcalendar para mostrar a 
 * agenda na data escolhida
 */
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
var gtd = getUrlParameter('gtd');
(gtd) ? $('#calendar').fullCalendar('gotoDate', gtd) : FALSE;