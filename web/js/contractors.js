var Contractors = {
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
            },
        });
    },

    setModalTitle: function (mode) {
        if (mode === 'addContractor') {
            $('.modal-title').text('Dodaj kontrahenta');
            $('#contractorName').attr("value", '');
            $('#contractorNip').attr("value", '');
            $('#contractorAddress').attr("value", '');
        }

        if (mode === 'editContractor') {
            $('.modal-title').text('Edytuj kontrahenta')
        }
    }
};