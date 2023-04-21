function loadCalendar(eventSources) {
    $("#calendar").fullCalendar({
        monthNames: [
            "Janeiro",
            "Fevereiro",
            "Março",
            "Abril",
            "Maio",
            "Junho",
            "Julho",
            "Agosto",
            "Setembro",
            "Outubro",
            "Novembro",
            "Dezembro",
        ],
        monthNamesShort: [
            "Jan",
            "Fev",
            "Mar",
            "Abr",
            "Mai",
            "Jun",
            "Jul",
            "Aug",
            "Set",
            "Out",
            "Nov",
            "Dez",
        ],
        dayNames: [
            "Domingo",
            "Segunda",
            "Terça",
            "Quarta",
            "Quinta",
            "Sexta",
            "Sábado",
        ],
        dayNamesShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        titleFormat: {
            month: "MMMM yyyy",
            week: "d[ yyyy]{ '...'[ MMM] d, MMM yyyy}",
            day: "dddd, d MMM yyyy",
        },
        buttonText: {
            today: "Hoje",
            month: "Mês",
            week: "Semana",
            day: "Dia",
        },
        header: {
            left: "prev,next today",
            center: "title",
            right: "month,basicWeek,basicDay",
        },
        weekNumberTitle: "S",
        editable: true,
        eventColor: "#0A212C",
        eventSources: eventSources,
        viewRender: function () {
            $("#preloader").hide();
        },
        dayClick: function (date) {
            //...
        },
    });
}
