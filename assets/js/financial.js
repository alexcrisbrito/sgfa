axios = axios.create({baseURL: location.protocol +'//' + location.host + "/financial", responseType: 'json'})
const expenses_table = document.getElementById('dataTable')

async function post_expense(ev) {
    ev.preventDefault()
    display_loader(1)

    const formdata = new FormData(ev.target)
    await axios.post('/expense/add', formdata).then(res => {

        if (res.data.result === true) {
            display_alert('success', 'Adicionou uma nova despesa com sucesso')
            add_expense_on_table(formdata)
            return
        }

        display_alert('error', res.data.message)

    }).catch(() => {
        display_alert('error', 'Erro ao processar o pedido, tente novamente !')
    })

    display_loader(0)
}

function add_expense_on_table(data) {
    last_expense_id += 1
    let account = document.getElementById('account_id')
    let row = expenses_table.insertRow(1)
    row.insertCell(0).innerHTML = data.get('name')
    row.insertCell(1).innerHTML = Intl.NumberFormat('de-DE', { style: 'currency', currency:'MZN' }).format(data.get('amount'))
    row.insertCell(2).innerHTML = account.options[account.selectedIndex].text
    row.insertCell(3).innerHTML = moment().format('DD-MM-YYYY')
    row.insertCell(4).innerHTML =
        '<button onclick="delete_expense()" class="btn btn-danger">\n' +
        '    <i class="fas fa-trash"></i>\n' +
        '</button>'
    row.cells[4].classList.add('text-center');
    account.parentElement.parentElement.reset()
}

async function post_account(ev) {
    ev.preventDefault()
    display_loader(1)

    const formdata = new FormData(ev.target);
    await axios.post('/account/add', formdata).then(res => {

        if (res.data.result === true) {
            display_alert('success', 'Adicionou uma nova conta com sucesso')
            labelspie.push(formdata.get('short_name'))
            dataPie.push(formdata.get('balance'))
            ev.target.reset()
            return
        }

        display_alert('error', res.data.message)

    }).catch(() => {
        display_alert('error', 'Erro ao processar o pedido, tente novamente !')
    })

    display_loader(0)
}

async function delete_expense(id) {
    const prompt = await display_prompt('Deseja realmente apagar esta despesa?');

    if (prompt.isConfirmed) {
        display_loader(1)
        await axios.get(`expense/delete/${id}`).then(res => {
            if(res.data.result === true) {
                display_alert('success', 'Apagou a despesa com sucesso, o valor foi creditado à conta de origem.')
                return;
            }

            display_alert('error', 'Erro ao apagar a despesa, tente novamente !')
        }).catch(() => {
            display_alert('error', 'Erro ao processar o pedido, tente novamente !')
        })
    }

    display_loader(0)
}