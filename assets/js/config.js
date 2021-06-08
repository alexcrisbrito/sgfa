axios = axios.create({baseURL: location.protocol+"//"+location.host+"/config"})
const admin_table = document.getElementById('dataTable');

async function post_config(ev) {
    ev.preventDefault()
    display_loader(1)

    const formdata = new FormData(ev.target)
    await axios.post('/business/update', formdata).then(res => {
        if (res.data.result === true) {
            display_alert('success', 'Atualizou os dados do negócio com sucesso')
            return
        }

        display_alert('error', res.data.message)

    }).catch(() => {
        display_alert('error', 'Erro ao processar o pedido, tente novamente !')
    })

    display_loader(0)
}

async function post_password(ev) {
    ev.preventDefault()
    display_loader(1)

    const formdata = new FormData(ev.target)
    await axios.post('/account/update-password', formdata).then(res => {
        if (res.data.result === true) {
            ev.target.reset();
            display_alert('success', res.data.message)
            return
        }

        display_alert('error', res.data.message)

    }).catch(() => {
        display_alert('error', 'Erro ao processar o pedido, tente novamente !')
    })

    display_loader(0)
}

async function post_new_user(ev) {
    ev.preventDefault()

    const prompt = await display_prompt('Deseja realmente criar um novo usuario administrativo?');

    if (prompt.isConfirmed) {
        display_loader(1)

        const formdata = new FormData(ev.target)
        await axios.post('/users/new-user', formdata).then(res => {
            if (res.data.result === true) {
                ev.target.reset();
                display_alert('success', res.data.message)
                last_admin_id += 1;
                const row = admin_table.insertRow();
                row.id = `adm${last_admin_id}`
                row.insertCell(0).innerHTML = formdata.get('name')
                row.insertCell(1).innerHTML = formdata.get('surname')
                row.insertCell(2).innerHTML = formdata.get('phone')
                row.insertCell(3).innerHTML = res.data.data.username
                row.insertCell(4).innerHTML = 'Activo'
                row.insertCell(5).innerHTML =
                    '<button title="Editar informações" onclick="open_edit_modal(' + last_admin_id + ')" class="btn btn-dark">\n' +
                    '    <i class="fas fa-user-edit"></i>\n' +
                    '</button>\n' +
                    '<button title="Modificar estado" onclick="status_of_admin(' + last_admin_id + ', 1)" \n' +
                    'class="btn btn-danger">\n' +
                    '    <i class="fas fa-times-circle"></i>\n' +
                    '</button>'
                row.cells[5].classList.add('text-center')
                return
            }

            display_alert('error', res.data.message)

        }).catch(() => {
            display_alert('error', 'Erro ao processar o pedido, tente novamente !')
        })

        display_loader(0)
    }
}

async function switch_sms() {
    display_loader(1)

    await axios.get('/business/switch-auto-sms').then(res => {
        if (res.data.result === true) {
            display_alert('success', res.data.message)
            return;
        }

        display_alert('error', res.data.message)

    }).catch(() => {
        display_alert('error', 'Erro ao processar o pedido, tente novamente !')
    })

    display_loader(0)
}

async function update_admin_info (ev) {
    ev.preventDefault();
    const prompt = await display_prompt('Deseja realmente efectuar esta acção ?');

    if (prompt.isConfirmed) {
        display_loader(1)

        const formdata = new FormData(ev.target)
        await axios.post('/account/update-info', formdata).then(res => {
            if (res.data.result === true) {
                display_alert('success', 'Atualizou as suas informações de conta com sucesso')
                return
            }

            if (res.data.result === 'w') {
                display_alert('warning', res.data.message)
                return
            }

            display_alert('error', res.data.message)

        }).catch(() => {
            display_alert('error', 'Erro ao processar o pedido, tente novamente !')
        })

        $('#edit-modal').modal('hide');
        display_loader(0)
    }
}

function open_edit_modal(id) {
    $('#edit-modal').modal('show');
    user_row = document.getElementById('adm'+id)
    document.getElementById('edit_id').value = id
    document.getElementById('edit_name').value = user_row.cells[0].innerText
    document.getElementById('edit_surname').value = user_row.cells[1].innerText
    document.getElementById('edit_phone').value = user_row.cells[2].innerText
}

async function post_edit_user (ev) {
    ev.preventDefault();
    const prompt = await display_prompt('Deseja realmente efectuar esta acção ?');

    if (prompt.isConfirmed) {
        display_loader(1)
        const formdata = new FormData(ev.target)
        await axios.post('/users/update-info', formdata).then(res => {
            if (res.data.result === true) {
                display_alert('success', "Atualizou com sucesso as informações do administrador");
                let id = formdata.get('id')
                user_row = document.getElementById('adm'+id)
                user_row.cells[0].innerText = formdata.get('name')
                user_row.cells[1].innerText = formdata.get('surname')
                user_row.cells[2].innerText = formdata.get('phone')
                return;
            }

            display_alert('error', res.data.message)

        }).catch(() => {
            display_alert('error', 'Erro ao processar o pedido, tente novamente !')
        })

        display_loader(0)
    }
}

async function status_of_admin(id, what) {
    const prompt = await display_prompt(`Deseja realmente ${what === 1 ? 'desactivar' : 'reactivar'} este administrador?`)

    if (prompt.isConfirmed) {
        display_loader(1)

        await axios.post(`/users/switch-state/${id}/${what}`).then(res => {
            if (res.data.result === true) {
                display_alert('success', `${what === 1 ? 'Desactivou' : 'Reactivou'} o administrador com sucesso.`)
                user_row = document.getElementById('adm'+id)
                user_row.cells[4].innerHTML = what === 1 ? 'Inactivo' : 'Activo'
                user_row.cells[5].innerHTML =
                    '<button title="Editar informações" onclick="open_edit_modal('+id+')" class="btn btn-dark">\n' +
                    '    <i class="fas fa-user-edit"></i>\n' +
                    '</button>\n' +
                    '<button title="Modificar estado" onclick="status_of_admin('+id+','+ (what === 1 ? 0 : 1) +')"' +
                    ' class="btn '+(what === 1 ? 'btn-danger' : 'btn-success')+'"> \n' +
                    '    <i class="fas '+(what === 1 ? 'fa-times-circle' : 'fa-check-circle')+'"></i> \n' +
                    '</button>'
                return;
            }

            display_alert('error', `Erro a ${what === 1 ? 'desactivar' : 'reactivar'} o administrador, tente novamente !`)

        }).catch(() => {
            display_alert('error', 'Erro ao processar pedido, tente novamente !')
        })

        display_loader(0)
    }
}