let token = $("input[name='_token']").val();

let selected = null;
let date = null;
url = window.location.pathname;
let slug = url.trim().split("/");

$("select[name=category_id]").on("change", function () {
    selected = $(this).find(":selected").attr("value");
    $.ajax({
        url: "/prioridade",
        type: "get",
        dataType: "html",
        data: {
            category_id: selected,
            _token: token,
        },
        success: function (response) {
            $("#jobs").html(response);
        },
    });
});

$("select[name=category_id_ag]").on("change", function () {
    selected = $(this).find(":selected").attr("value");

    $.ajax({
        url: "/prioridade/agencia",
        type: "get",
        dataType: "html",
        data: {
            category_id_ag: selected,
            _token: token,
        },
        success: function (response) {
            $("#jobs").html(response);
        },
    });
});


// $("#changeStatusJob").on("change", function () {
//     selected = $(this).find(":selected").attr("value");
//     let id = $(this).attr("data-idJob");
//     let url = "{{route('status')}}";
//     $.ajax({
//         url: "/changeStatus",
//         type: "post",
//         dataType: "html",
//         data: {
//             selected: selected,
//             _token: token,
//             id: id,
//         },
//         success: function (response) {
//             // Swal.fire({
//             //     position: "top-right",
//             //     icon: "success",
//             //     title: "Alterado com sucesso!",
//             //     showConfirmButton: false,
//             //     timer: 1500,
//             // });
//         },
//     });
// });

//pegar comentarios

let idComentary;

$(".idComment").val("");
$(".idCommentResponse").val("");

function getComentary(id) {
    $(".idComment").val(id);
    // var url = "{{ route('getSetores', ':id') }}";
    // url = url.replace(':id', id);
    $.ajax({
        url: "/comentario/editar/" + id,
        type: "GET",
        dataType: "json",
        data: {
            _token: token,
        },
        success: function (response) {
            tinymce.get("modalEl").setContent(response.descricao);
        },
    });
}

function getResponse(id) {
    $(".idCommentResponse").val(id);

    $.ajax({
        url: "/respostas/editar/" + id,
        type: "GET",
        dataType: "json",
        data: {
            _token: token,
        },
        success: function (response) {
            tinymce.get("answerModal").setContent(response.conteudo);
        },
    });
}

$(document).ready(function () {
    //checkbox job

    // $("#customCheck02").on("change", function () {
    //     if ($("#customCheck02").is(":checked")) {
    //         $("#sendCheck").submit();
    //     }
    // });

    $('.select2').select2({
        minimumResultsForSearch: Infinity
    });
    
    $('tr.trLink').click(function() {
        var link = $(this).attr("data-href"); // get the link of the clicked row
        window.location.href = link; // redirect to the URL with the ID appended
    });

    $('.btnDanger').click(function(event) {
        event.stopPropagation(); // impede que o evento de clique na tr seja propagado para os elementos filhos
    });

    $(".deleteBt").click(function (event) {
        event.preventDefault();
        let href = this.getAttribute("href");
        Swal.fire({
            title: "Deseja excluir?",
            icon: "question",
            text: "Por favor, certifique-se e depois confirme!",
            type: "warning",
            showCancelButton: !0,
            cancelButtonText: "Fechar",
            confirmButtonText: "Excluir",
            confirmButtonClass: "red-btn",
            reverseButtons: !0,
        }).then((result) => {
            if (result.value) {
                window.location = href;
            }
        });
    });

    tinymce.init({
        selector: ".elm1",
        language: "pt_BR",
        height: 350,
        browser_spellcheck: true,
        menubar: false,
        plugins: [
            "lists charmap hr anchor pagebreak spellchecker",
            "searchreplace autolink wordcount visualblocks visualchars code fullscreen media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor",
        ],

        toolbar:
            "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | preview fullpage | forecolor backcolor emoticons",
        style_formats: [
            {
                title: "Bold text",
                inline: "b",
            },
            {
                title: "Red text",
                inline: "span",
                styles: {
                    color: "#ff0000",
                },
            },
            {
                title: "Red header",
                block: "h1",
                styles: {
                    color: "#ff0000",
                },
            },
            {
                title: "Example 1",
                inline: "span",
                classes: "example1",
            },
            {
                title: "Example 2",
                inline: "span",
                classes: "example2",
            },
            {
                title: "Table styles",
            },
            {
                title: "Table row 1",
                selector: "tr",
                classes: "tablerow1",
            },
        ],
    });

    // Verifica o tamanho da tela ao carregar a página
    if ($(window).width() <= 1080) {
        $('.simplebar-content-wrapper').css({
            'height': 'auto'
        });
        
        $('.simplebar-placeholder').css({
            'height': '0px',
            'width': '0px'
        });
    }

    if ($(window).width() <= 480) {
        Morris.Donut({
            size: 150 
        });
    }    

    //remover anexo
    $(".deleteArq").on("click", function (e) {
        e.preventDefault();
        var form = $(this).parents("form");
        Swal.fire({
            title: "Deseja excluir?",
            icon: "question",
            text: "Por favor, certifique-se e depois confirme!",
            type: "warning",
            showCancelButton: !0,
            cancelButtonText: "Fechar",
            confirmButtonText: "Excluir",
            confirmButtonClass: "red-btn",
            reverseButtons: !0,
        }).then((result) => {
            if (result.value) {
                form.submit();
            }
        });
    });
    // $(".deleteArq").each(function (index) {
    //     $(this).click(function () {
    //         const idArq = $(this).attr("data-idanexo");
    //         swal.fire({
    //             title: "Deseja excluir?",
    //             icon: "question",
    //             text: "Por favor, certifique-se e depois confirme!",
    //             type: "warning",
    //             showCancelButton: !0,
    //             cancelButtonText: "Fechar",
    //             confirmButtonText: "Excluir",
    //             reverseButtons: !0,
    //         }).then(
    //             function (e) {
    //                 if (e.value === true) {
    //                     $.ajax({
    //                         url: `/imagem/delete/${idArq}`,
    //                         type: "POST",
    //                         dataType: "json",
    //                         data: {
    //                             _token: token,
    //                         },
    //                         success: function (response) {
    //                             Swal.fire({
    //                                 icon: "success",
    //                                 title: "Arquivo excluido com sucesso!",
    //                                 showDenyButton: false,
    //                                 showCancelButton: false,
    //                                 confirmButtonText: "Fechar",
    //                             }).then((result) => {
    //                                 location.reload();
    //                             });
    //                         },
    //                     });
    //                 } else {
    //                     e.dismiss;
    //                 }
    //             },
    //             function (dismiss) {
    //                 return false;
    //             }
    //         );
    //     });
    // });

    $(".submitForm").on("click", function (e) {
        e.preventDefault();
        var form = $(this).parents("form");
        Swal.fire({
            title: "Deseja excluir?",
            icon: "question",
            text: "Por favor, certifique-se e depois confirme!",
            type: "warning",
            showCancelButton: !0,
            cancelButtonText: "Fechar",
            confirmButtonText: "Excluir",
            confirmButtonClass: "red-btn",
            reverseButtons: !0,
        }).then((result) => {
            if (result.value) {
                form.submit();
            }
        });
    });

    $(".submitFinalize").on("click", function (e) {
        e.preventDefault();
        var form = $(this).parents("form");
        Swal.fire({
            title: "Tem certeza de que deseja finalizar este job?",
            icon: "question",
            text: "Por favor, certifique-se e depois confirme!",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Fechar",
            confirmButtonText: "Finalizar",
            confirmButtonClass: "green-btn",
            reverseButtons: true,
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    title: 'Aguarde',
                    html: 'Enviando dados...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
    
                // envia o formulário após um pequeno intervalo de tempo
                setTimeout(() => {
                    form.submit();
                }, 1000);
            }
        });
    });

    $(".submitModal").on("click", function (e) {
        e.preventDefault();
        var form = $(this).parents("form");
        Swal.fire({
            title: 'Aguarde',
            html: 'Enviando dados...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    
        // envia o formulário após um pequeno intervalo de tempo
        setTimeout(() => {
            form.submit();
        }, 1000);
    });


    // $(".submitQuest").on("click", function (e) {
    //     e.preventDefault();
    //     var form = $(this).parents("form");
    //     Swal.fire({
    //         title: "Tem certeza de que deseja realizar esta ação?",
    //         icon: "question",
    //         text: "Por favor, certifique-se e depois confirme!",
    //         type: "warning",
    //         showCancelButton: true,
    //         cancelButtonText: "Fechar",
    //         confirmButtonText: "Confirmar",
    //         confirmButtonClass: "green-btn",
    //         reverseButtons: true,
    //     }).then((result) => {
    //         if (result.value) {
    //             form.submit();
    //         }
    //     });
    // });

    $(".submitQuest").on("click", function (e) {
        e.preventDefault();
        var form = $(this).parents("form");
        Swal.fire({
            title: "Tem certeza de que deseja realizar esta ação?",
            icon: "question",
            text: "Por favor, certifique-se e depois confirme!",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Fechar",
            confirmButtonText: "Confirmar",
            confirmButtonClass: "green-btn",
            reverseButtons: true,
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    title: 'Aguarde',
                    html: 'Enviando dados...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
    
                // envia o formulário após um pequeno intervalo de tempo
                setTimeout(() => {
                    form.submit();
                }, 1000);
            }
        });
    });

    const inputElement = document.querySelector('input[name="file[]"]');
    const fileId = inputElement.getAttribute('id');
    let url = inputElement.getAttribute("data-url");
    const pond = FilePond.create(inputElement);
    const id = document.querySelector("#textbox_id").value;
    url = url.replace(":id", id);

    document.querySelector(
        ".filepond--drop-label.filepond--drop-label label"
    ).innerHTML = "";

    FilePond.setOptions({
        labelIdle: `Arraste e solte seus arquivos aqui!`,
        labelInvalidField: "O campo contém arquivos inválidos",
        labelFileProcessingComplete: "Arquivo anexado",
        labelFileProcessing: "Carregando...",
        server: {
            url: url,
            headers: {
                "X-CSRF-TOKEN": token,
            },
        },

        onprocessfiles: function (file, progress) {
            Swal.fire({
                icon: "success",
                title: "Arquivos anexados com sucesso!",
                showDenyButton: false,
                showCancelButton: false,
                confirmButtonText: "Fechar",
            }).then((result) => {
                if(fileId != 'file-epata-2'){
                    location.reload();
                }
            });
        },
    });
});

$(".carousel").slick({
    dots: false,
    arrows: true,
    infinite: false,
    speed: 500,
    slidesToShow: 6,
    slidesToScroll: 6,
    fade: false,
    cssEase: "linear",
    prevArrow: '<span class="slide-arrow prev-arrow"></span>',
    nextArrow: '<span class="slide-arrow next-arrow"></span>',
    responsive: [
        {
            breakpoint: 1356,
            settings: {
                arrows: true,
                dots: false,
                slidesToShow: 3,
                slidesToScroll: 3,
            },
        },
        {
            breakpoint: 1000,
            settings: {
                dots: false,
                arrows: false,
                slidesToShow: 2,
                slidesToScroll: 2,
            },
        },

        {
            breakpoint: 600,
            settings: {
                dots: false,
                arrows: false,
                slidesToShow: 1,
                slidesToScroll: 1,
            },
        },
    ],
});

$(function() {
    $('input.filter-daterangepicker').daterangepicker({
      locale: {
        format: 'DD/MM/YYYY',
        separator: ' - ',
        applyLabel: 'Aplicar',
        cancelLabel: 'Cancelar',
        fromLabel: 'De',
        toLabel: 'Até',
        customRangeLabel: 'Período personalizado',
        weekLabel: 'W',
        daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        monthNames: [
          'Janeiro',
          'Fevereiro',
          'Março',
          'Abril',
          'Maio',
          'Junho',
          'Julho',
          'Agosto',
          'Setembro',
          'Outubro',
          'Novembro',
          'Dezembro'
        ],
        firstDay: 0
      },
      isInvalidDate: function(date) {
        return (date.day() === 0 || date.day() === 6); // retorna true para sábados e domingos
      }
    });
    
  });

  