var Products = {

    setModalTitle: function (mode) {

        if (mode === 'addProduct') {
            $('.modal-title').text('Dodaj produkt lub usługę');
        }

        if (mode === 'editProduct') {
            $('.modal-title').text('Edytuj produkt')
        }
    },

    getProduct: function(id) {
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
                $('#productName').attr("value", data.data.name);
                $('#productNetPrice').attr("value", data.data.netPrice);
                $('#productVatPerCent').attr("value", data.data.vatPerCent);
                $('#productId').attr("value", data.data.id);
            },
        });
    },

    submit() {

        id = $('#productId').val();

        if (id === "") {
            this.addProduct()
        } else {
            id = parseInt(id);
            this.updateProduct(id)
        }
    },

    updateProduct: function(id) {

        var formData = {
            'id': id,
            'name': $('#productName').val(),
            'netPrice': $('#productNetPrice').val(),
            'vatPerCent': $('#productVatPerCent').val(),
        };

        $.ajax({
            url: 'update/' + id,
            data: formData,
            type: 'PATCH',
            error: function () {
                $('#message').append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Błąd!</strong> Nie udało się zmienić danych produktu lub usługi
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
            },
            success: function () {

                $('#message').append(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Sukces!</strong> Produkt lub usługa została zaktualizowana
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
                setTimeout(function(){  window.location.reload(); }, 2000);

            },
        });
    },

    deleteProduct: function(id) {

        $.ajax({
            url: 'delete/' + id,
            type: 'DELETE',
            error: function () {
                $('#mainMessage').append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Błąd!</strong> Nie udało się usunąć produktu lub usługi
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
            },
            success: function () {

                $('#mainMessage').append(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Sukces!</strong> Produkt lub usług została usunięta
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
                setTimeout(function(){  window.location.reload(); }, 2000);

            },
        });
    },

    addProduct:  function() {

        var formData = {
            'name': $('#productName').val(),
            'netPrice': $('#productNetPrice').val(),
            'vatPerCent': $('#productVatPerCent').val(),
        };

        $.ajax({
            url: 'new',
            data: formData,
            type: 'POST',
            error: function () {
                $('#message').append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Błąd!</strong> Nie udało się dodać produktu lub usługi
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
            },
            success: function () {

                $('#message').append(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Sukces!</strong> Produkt lub usługa została usunięta
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
                window.location.reload();
            },
        });
    },
};

$("#submit").click(function(event) {
    event.preventDefault()
});

$("#close").click(function(event) {
    window.location.reload();
});