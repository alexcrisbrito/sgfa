axios = axios.create({baseURL: 'http://' + location.host + "/receipt", responseType: 'json'})
const receipt_table = document.getElementById('dataTable')
const date_today = new Date()
let selected_invoice = ''
let selected_paid_via = ''

async function post_receipt(ev) {
    ev.preventDefault()
    display_loader(1)

    const formdata = new FormData(ev.target);
    await axios.post('/add', formdata).then(res => {
        if (res.data.result === true) {
            display_alert('success', res.data.message)
            add_receipt_on_table(formdata.get('amount'))
            return
        }

        display_alert('error', res.data.message)

    }).catch(() => {
        display_alert('error', 'Erro ao processar o pedido, tente novamente !')
    })

    display_loader(0)
}

function add_receipt_on_table(amount) {
    last_receipt_id += 1
    let row = receipt_table.insertRow(1)
    row.insertCell(0).innerHTML = last_receipt_id
    row.insertCell(1).innerHTML = moment().format('DD-MM-YYYY')
    row.insertCell(2).innerHTML = selected_invoice
    row.insertCell(3).innerHTML = Intl.NumberFormat("de-DE", {style:'currency', currency:'MZN'}).format(amount)
    row.insertCell(4).innerHTML = selected_paid_via
    row.insertCell(5).innerHTML =
        '<a href="/invoice/print/' + last_receipt_id + '" class="btn btn-dark">' +
        '   <i class="fas fa-print"></i>' +
        '</a>';
    row.cells[5].classList.add('text-center')
    row.id = `inv${last_receipt_id}`
}

/* Get the name of selected invoice */
function select_invoice(el) {
    selected_invoice = el.options[el.selectedIndex].text.split(" -> ")[0];
}

/* Get the name of selected payment method */
function select_paid_via(el) {
    selected_paid_via = el.options[el.selectedIndex].text;
}

/* Invoice select element */
$(document).ready(function() {
    $('.js-example-basic-single').select2({
        placeholder: 'Selecione a factura referente',
        allowClear: true
    });
});