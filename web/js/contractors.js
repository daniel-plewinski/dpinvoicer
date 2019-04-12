var Contractors = {

    setModalTitle: function (mode) {

        if (mode === 'addContractor') {
            $('.modal-title').text('Dodaj kontrahenta');
        }

        if (mode === 'editContractor') {
            $('.modal-title').text('Edytuj kontrahenta')
        }
    },

    getContractor: function(id) {
        $.ajax({
            url: 'get/' + id,
            data: {
                format: 'json'
            },
            type: 'GET',
            error: function() {
                console.log('error');
            },
            success: function(data) {
                $('#contractorName').attr("value", data.data.name);
                $('#contractorNip').attr("value", data.data.nip);
                $('#contractorAddress').attr("value", data.data.address);
                $('#contractorId').attr("value", data.data.id);
            },
        });
    },

    submit() {

        id = $('#contractorId').val();

        if (id === "") {
            this.addContractor()
        } else {
            id = parseInt(id);
            this.updateContractor(id)
        }
    },

    updateContractor: function(id) {

        var formData = {
            'id': id,
            'name': $('#contractorName').val(),
            'nip': $('#contractorNip').val(),
            'address': $('#contractorAddress').val(),
        };

        $.ajax({
            url: 'update/' + id,
            data: formData,
            type: 'PATCH',
            error: function () {
                $('#message').append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Błąd!</strong> Nie udało się zmienić danych kontrahenta
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
            },
            success: function () {

                $('#message').append(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Sukces!</strong> Kontrahent został zaktualizowany
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
                setTimeout(function(){  window.location.reload(); }, 2000);

            },
        });
    },

    addContractor:  function() {

        var formData = {
            'name': $('#contractorName').val(),
            'nip': $('#contractorNip').val(),
            'address': $('#contractorAddress').val(),
        };

        $.ajax({
            url: 'new',
            data: formData,
            type: 'POST',
            error: function () {
                $('#message').append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Błąd!</strong> Nie udało się dodać kontrahenta
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
            },
            success: function () {

                $('#message').append(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Sukces!</strong> Kontrahent został dodany
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
                window.location.reload();
            },
        });
    },

    formClear: function () {
        $('#contractorName').val('');
        $('#contractorNip').val('');
        $('#contractorAddress').val('');
    }

};

$("#submit").click(function(event) {
    event.preventDefault()
});

$("#close").click(function(event) {
    window.location.reload();
});