var Invoices = {

    getInvoice: function(id) {
        $.ajax({
            url: 'invoices/get/' + id,
            data: {
                format: 'json'
            },
            type: 'GET',
            error: function() {
                console.log('error');
            },
            success: function(data) {
                console.log(data);
                data = data.data;
                $('#numberShow').html(data.number);
                $('#contractorNameShow').html(data.contractorName);
                $('#contractorAddressShow').html(data.contractorAddress);
                $('#issueDateShow').html(data.issueDate);
                $('#dueByDateShow').html(data.dueByDate);
                $('#totalNetShow').html(data.totalNet);
                $('#totalGrossShow').html(data.totalGross);

                var appendProducts = "";

                for (var i=0; i<data.products.length; i++) {
                    appendProducts += "<tr>";
                    appendProducts += "<td>" + data.products[i].name + "</td>";
                    appendProducts += "<td>" + data.products[i].quantity + "</td>";
                    appendProducts += "<td>" + data.products[i].netPrice + "</td>";
                    appendProducts += "<td>" + data.products[i].vatPerCent + "</td>";
                    var quantity = data.products[i].quantity;
                    var netPrice = parseInt(data.products[i].netPrice);
                    var grossPrice = netPrice * quantity;
                    if (data.products[i].vatPerCent !== "zw") {

                        var vatPercent = parseInt(data.products[i].vatPerCent);
                        grossPrice = (netPrice * (vatPercent / 100) + netPrice) * quantity;
                        grossPrice = grossPrice.toFixed(2);

                    }
                    appendProducts += "<td>" + grossPrice + "</td>";
                    appendProducts += "</tr>";
                }

                $('#productList').append(appendProducts);

            },
        });
    },

    deleteInvoice: function(id) {

        $.ajax({
            url: 'invoices/delete/' + id,
            type: 'DELETE',
            error: function () {
                $('#mainMessage').append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Błąd!</strong> Nie udało się usunąć faktury
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
            },
            success: function () {

                $('#mainMessage').append(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Sukces!</strong> Faktura została usunięta
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
                setTimeout(function(){  window.location.reload(); }, 2000);

            },
        });
    },

    addInvoice:  function() {

        var formData = {
            'contractor': $('#contractorNameAdd').val(),
            'invoiceDueByDate': $('#invoiceDueByDateAdd').val(),
            'invoiceProduct1': $('#invoiceProduct1Add').val(),
            'invoiceProductQuantity1': $('#invoiceProductQuantity1Add').val(),
            'invoiceProduct2': $('#invoiceProduct2Add').val(),
            'invoiceProductQuantity2': $('#invoiceProductQuantity2Add').val(),
        };

        $.ajax({
            url: 'invoices/new',
            data: formData,
            type: 'POST',
            error: function () {
                $('#message').append(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Błąd!</strong> Nie udało się dodać faktury
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  </div>`);
            },
            success: function () {

                $('#message').append(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Sukces!</strong> Faktura została dodana
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