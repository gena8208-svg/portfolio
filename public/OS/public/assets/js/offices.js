var table;
var scrollY = $(window).height() - $("#offices-table").offset().top - 200;
$(document).ready(function() {
    table = $("#offices-table").DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/2.1.8/i18n/ru.json",
        },
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "Все"],
        ],


        ordering: true,
        dom: "lfrtip",
        buttons: ["copy", "excel"],
        scrollY: scrollY + 'px',
        paging: false,
        scrollCollapse: true,
        pageLength: 50,
        autoWidth: false,
        ajax: {
            url: "offices_table.php",
            type: "POST",

            dataType: "json",
            dataSrc: "",

            data: function(d) {
                return $.extend({}, d, {});
            }
        },
        columns: [{
                data: "id",
                visible: false
            },

            {
                data: "priznak",
              width: "10%"

            },
            {
                data: "adress",
                width: "40%"
            },
            {
                data: "phone",
                width: "40%"
            },
            {
                data: null,
                defaultContent: `<button class="btn btn-primary btn-sm edit-btn"><i class="bi bi-pencil-square"></i></button>`,
                width: "10%"
              },

        ],

    });
});
$(document).ready(function() {
    $('#addofficeModal').on('show.bs.modal', function() {
        $('#addofficeModalForm')[0].reset();
    });
});
$(document).ready(function() {
    // Обработчик нажатия на кнопку "Сохранить"
    $('#saveofficeBtn').click(function() {
      // Собираем данные из полей формы
      var data = {
        priznak_office: $('#priznak_office').val(),
        adress_office: $('#adress_office').val(),
        phone: $('#phone').val()
      };
  
      // Отправляем данные на сервер через AJAX-запрос
      $.ajax({
        type: 'POST',
        url: 'add_office.php',
        data: data,
         dataType: 'json',
        success: function(response) {
          // Обработка успешного ответа от сервера
          if (response.status === 'success') {
                            $('#addofficeModal').modal('hide')
                              var toastEl = document.getElementById('saveToast');
                              var toast = new bootstrap.Toast(toastEl);
                              toast.show();
                              table.ajax.reload();
            // Обновляем список офисов на странице
            // ...
          } else {
            // Обрабатываем ошибку, если сервер вернул неудачный ответ
            alert('Ошибка: ' + response.message);
          }
        },
   
      });
    });
  });

  //редактирование офиса
  $(document).ready(function() {
    // Обработчик нажатия на кнопку редактирования
    $('#offices-table tbody').on('click', '.edit-btn', function(){
        var id = table.row($(this).parents('tr')).data().id; // Получаем ID офиса из атрибута data-id
        getOfficeData(id);
        $('#editofficeModal').modal('show'); // Открываем модальное окно
    });

    // Функция для получения данных офиса
    function getOfficeData(id) {
        $.ajax({
            url: 'get_office_data.php',
            method: 'POST',
            data: {
                id: id
            },
            success: function(response) {
                // Предполагается, что response - это JSON
                const officeData = response.data; // Извлекаем данные из объекта data
                $('#editofficeModalId').val(officeData.id);
                $('#edit_priznak_office').val(officeData.priznak); // Используем правильные имена полей
                $('#edit_adress_office').val(officeData.adress);
                $('#edit_phone').val(officeData.phone);
                $('#updateofficeBtn').off('click').on('click', function() {
                    const updatedData = {
                        id: $('#editofficeModalId').val(),
                        priznak: $('#edit_priznak_office').val(),
                        adress: $('#edit_adress_office').val(),
                        phone: $('#edit_phone').val(),
                       
                    };
                    $.ajax({
                        url: 'adm_update_office.php',
                        method: 'POST',
                        data: updatedData,
                        success: function(response) {
                            table.ajax.reload();
                            $('#editofficeModal').modal('hide');
                            var toastEl = document.getElementById('updateToast');
                            var toast = new bootstrap.Toast(toastEl);
                            toast.show();
                        },
                        error: function(xhr, status, error) {
                            console.error('Ошибка при обновлении данных:', error);
                        }
                    });
                });
            },
            error: function() {
                alert('Произошла ошибка при получении данных.');
            }
        });
    }
});