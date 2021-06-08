axios = axios.create({baseURL: location.protocol +'//' + location.host + "/invoice", responseType: 'json'})
let client = document.getElementById('client')
const invoice_table = document.getElementById('dataTable')
const date_today = new Date()
let consumption = 0;
let amount = 0;

function calculate () {
    let counter_now = document.getElementById("counter").value;
    let counter_previous = previous_counter_readings[client.value] ?? 0


    consumption = counter_now - counter_previous
    document.getElementById('consumption').innerHTML = `${consumption} m<sup>3</sup>`

   amount = consumption * price_per_m3
    document.getElementById('amount').innerHTML =
        new Intl.NumberFormat('de-DE', { style:'currency', currency:'MZN' }).format(amount);
}

function set_min_counter_value(val) {
    document.getElementById('counter').min = (previous_counter_readings[val] ?? 0) + 1;
    document.getElementById('counter').value = (previous_counter_readings[val] ?? 0) + 1;
    calculate()
}

async function post_invoice(ev) {
    ev.preventDefault();
    display_loader(1)

    const formdata = new FormData(ev.target);
    formdata.append('consumption', consumption)
    await axios.post('/add', formdata).then(res => {
        if (res.data.result === true) {
            display_alert('success', res.data.message)
            add_invoice_on_table(formdata)
            return
        }

        display_alert('error', res.data.message)

    }).catch(() => {
        display_alert('error', 'Erro ao processar o pedido, tente novamente !');
    })

    display_loader(0)
}

function add_invoice_on_table(data) {
    last_invoice_id += 1
    let row = invoice_table.insertRow(1)
    row.insertCell(0).innerHTML = `${last_invoice_id}`
    row.insertCell(1).innerHTML = moment().format('DD-MM-YYYY')
    row.insertCell(2).innerHTML = client.options[client.selectedIndex].text
    row.insertCell(3).innerHTML = Intl.NumberFormat('de-DE').format(data.get('counter')) + " m<sup>3</sup>"
    row.insertCell(4).innerHTML = Intl.NumberFormat('de-DE').format(consumption) + " m<sup>3</sup>"
    row.insertCell(5).innerHTML = Intl.NumberFormat('de-DE', {style:'currency',currency:'MZN'}).format(amount)
    row.insertCell(6).innerHTML = Intl.NumberFormat('de-DE', {style:'currency',currency:'MZN'}).format(amount)
    row.insertCell(7).innerHTML = '0,00 MZN'
    row.insertCell(8).innerHTML = 'Em dívida'
    row.insertCell(9).innerHTML =
        '<button onclick="cancel_invoice(' + last_invoice_id + ')" class="btn btn-danger btn-sm">' +
        '   <i class="fas fa-times-circle"></i>' +
        '</button>\n' +
        '<a href="/invoice/print/' + last_invoice_id + '" class="btn btn-dark btn-sm">' +
        '   <i class="fas fa-print"></i>' +
        '</a>';
    row.cells[9].classList.add('text-center')
    row.id = `inv${last_invoice_id}`
}

async function cancel_invoice(id) {
    const prompt = await display_prompt(`Deseja realmente cancelar a factura ${id}?`);

    if (prompt.isConfirmed) {

        display_loader(1)
        await axios.get(`/cancel/${id}`).then(res => {
            if (res.data.result === true) {
                let row = invoice_table.querySelector(`#inv${id}`);
                row.cells[6].innerHTML = '0,00 MT';
                row.cells[8].innerHTML = 'Cancelada';
                row.cells[9].innerHTML =
                    '<button onclick="reactivate_invoice('+ id +')" class="btn btn-success btn-sm">\n' +
                    '   <i class="fas fa-check-circle"></i>\n' +
                    '</button>\n'+
                    '<button onclick="delete_invoice('+ id +')" class="btn btn-danger btn-sm">\n' +
                    '   <i class="fas fa-trash"></i>\n' +
                    '</button>';
                display_alert('success', 'Cancelou a factura com sucesso.');
                return
            }

            display_alert('error', res.data.message)
        }).catch(() => {
            display_alert('error', 'Erro ao cancelar a factura, tente novamente !')
        })

        display_loader(0)
    }
}

async function reactivate_invoice(id) {
    const prompt = await display_prompt(`Deseja realmente reactivar a factura ${id}?`);

    if (prompt.isConfirmed) {

        display_loader(1)
        await axios.get(`/reactivate/${id}`).then(res => {
            if (res.data.result === true) {
                let row = invoice_table.querySelector(`#inv${id}`);
                row.cells[6].innerHTML = row.cells[5].innerHTML
                row.cells[8].innerHTML = 'Em dívida';
                row.cells[9].innerHTML =
                    '<button onclick="cancel_invoice('+ id +')" class="btn btn-danger btn-sm">\n' +
                    '   <i class="fas fa-times-circle"></i>\n' +
                    '</button>\n' +
                    '<a href="/invoice/print/'+ id +'" class="btn btn-dark btn-sm">\n' +
                    '   <i class="fas fa-print"></i>\n' +
                    '</a>';

                display_alert('success', 'Reactivou a factura com sucesso.')
                return
            }

            display_alert('error', res.data.message)
        }).catch(() => {
            display_alert('error', 'Erro ao reactivar a factura, tente novamente !')
        })

        display_loader(0)
    }
}

async function remove_fine_on_invoice(id) {
    const prompt = await display_prompt(`Deseja realmente retirar a multa da factura ${id}?`);

    if (prompt.isConfirmed) {

        display_loader(1)
        await axios.get(`/clear-fine/${id}`).then(res => {
            if (res.data.result === true) {
                let row = invoice_table.querySelector(`#inv${id}`);
                row.cells[6].innerHTML = '0,00 MT'
                row.cells[7].innerHTML = 'Em dívida'
                row.cells[9].innerHTML =
                    '<button onclick="cancel_invoice(' + last_invoice_id + ')" class="btn btn-info btn-sm">' +
                    '   <i class="fas fa-times"></i>' +
                    '</button>\n' +
                    '<a href="/invoice/print/' + last_invoice_id + '" class="btn btn-dark btn-sm">' +
                    '   <i class="fas fa-print"></i>' +
                    '</a>';
                display_alert('success', 'Retirou a multa da factura com sucesso.')
                return
            }

            display_alert('error', 'Erro ao retirar multa da factura, tente novamente !')
        }).catch(() => {
            display_alert('error', 'Erro ao retirar multa da factura, tente novamente !')
        })

        display_loader(0)

    }
}

async function delete_invoice(id) {
    const prompt = await display_prompt(`Deseja realmente apagar a factura ${id}?`);

    if (prompt.isConfirmed) {

        display_loader(1)
        await axios.get(`/delete/${id}`).then(res => {
            if (res.data.result === true) {
                invoice_table.querySelector(`#inv${id}`).remove();
                display_alert('success', 'Apagou a factura com sucesso.')
                return
            }

            display_alert('error', 'Erro ao apagar a factura, tente novamente !')
        }).catch(() => {
            display_alert('error', 'Erro ao apagar a factura, tente novamente !')
        })

        display_loader(0)
    }
}

$(document).ready(function() {
    $('.js-example-basic-single').select2({
        placeholder: 'Selecione o cliente',
        allowClear: true
    });
});