axios = axios.create({baseURL: location.protocol +'//' + location.host + "/client", responseType: 'json'})
const client_table = document.getElementById('dataTable');
let user_row = null;

async function post_client(ev) {
    ev.preventDefault();
    display_loader(1);

    const formdata = new FormData(ev.target);
    await axios.post('/add', formdata).then(res => {

        if (res.data.result === true) {
            display_alert('success', res.data.message)
            add_client_on_table(formdata)
            ev.target.reset();
            return;
        }

        display_alert('error', res.data.message)

    }).catch(() => {
        display_alert('error', 'Erro ao processar o pedido, tente novamente !')
    })

    display_loader(0)
}

function add_client_on_table(data) {

    last_client_id += 1
    let row = client_table.insertRow()
    row.insertCell(0).innerHTML = last_client_id
    row.insertCell(1).innerHTML = `${data.get('name')} ${data.get('surname')}`;
    row.insertCell(2).innerHTML = data.get('phone');
    row.insertCell(3).innerHTML = data.get('address');
    row.insertCell(4).innerHTML = data.get('counter_number');
    row.insertCell(5).innerHTML = "";
    row.insertCell(6).innerHTML = moment().format("DD-MM-YYYY");
    row.insertCell(7).innerHTML = 'Activo';
    row.insertCell(8).innerHTML =
        '<button title="Editar informações" onclick="open_edit_modal('+last_client_id+')" class="btn btn-dark">\n' +
        '    <i class="fas fa-user-edit"></i>\n' +
        '</button>\n' +
        '<button title="Modificar estado" onclick="status_of_client('+last_client_id+', 1)" class="btn btn-danger">\n' +
        '    <i class="fas fa-times-circle"></i>\n' +
        '</button>'+
        '<a title="Relatorio" href="/client/historic/'+last_client_id+'" class="btn btn-info">\n' +
        '    <i class="fas fa-file-prescription"></i>\n' +
        '</a>';
}

async function status_of_client(id, what) {
    const prompt = await display_prompt(`Deseja realmente ${what === 1 ? 'desactivar' : 'reactivar'} este cliente?`)

    if (prompt.isConfirmed) {
        display_loader(1)

        await axios.get(`/status/${id}/${what}`).then(res => {

            if (res.data.result === true) {
                display_alert('success', `${what === 1 ? 'Desactivou' : 'Reactivou'} o cliente com sucesso.`)
                user_row = document.getElementById('cl'+id);
                user_row.cells[6].innerHTML = (what === 1 ? 'Inactivo' : 'Activo');
                user_row.cells[7].innerHTML =
                    "<button title=\"Editar informações\" onclick=\"open_edit_modal("+id+")\" class=\"btn btn-dark\">\n" +
                    "<i class=\"fas fa-user-edit\"></i>\n" +
                    "</button>\n" +
                    "<button title=\"Modificar estado\" onclick=\"status_of_client("+id+","+ (what === 1 ? 0 : 1) +")\" " +
                    "class=\"btn btn-"+(what === 1 ? 'danger' : 'success')+"\">\n" +
                    '<i class=\"fas fa-'+(what === 1 ? 'times' : 'check')+'-circle\"></i>\n' +
                    "</button>";
                return;
            }

            display_alert('error', `Erro a ${what === 1 ? 'desactivar' : 'reactivar'} o cliente, tente novamente !`)

        }).catch(() => {
            display_alert('error', 'Erro ao processar pedido, tente novamente !')
        })

        display_loader(0)
    }

}

function open_edit_modal(id) {
    $('#edit-modal').modal('show');
    user_row = document.getElementById('cl'+id)
    document.getElementById('edit_id').value = id
    document.getElementById('edit_name').value = user_row.cells[1].innerText.split(" ")[0]
    document.getElementById('edit_surname').value = user_row.cells[1].innerText.split(" ")[1]
    document.getElementById('edit_phone').value = user_row.cells[2].innerHTML
    document.getElementById('edit_address').value = user_row.cells[3].innerHTML
}

async function post_edit_client(ev) {
    ev.preventDefault();
    $('#edit-modal').modal('hide')
    display_loader(1)

    const formdata = new FormData(ev.target)
    await axios.post('/update', formdata).then(res => {
        if (res.data.result === true) {
            display_alert('success', 'Atualizou os dados do cliente com sucesso')
            user_row.cells[1].innerText = `${formdata.get('name')} ${formdata.get('surname')}`
            user_row.cells[2].innerText = formdata.get('phone')
            user_row.cells[3].innerText = formdata.get('address')
            return
        }

        display_alert('error', res.data.message)

    }).catch(() => {
        display_alert('error', 'Erro ao processar pedido, tente novamente !')
    })

    display_loader(0)
}