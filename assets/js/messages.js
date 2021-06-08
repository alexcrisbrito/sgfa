axios = axios.create({baseURL: location.protocol +'//'+ location.host +"/messages", responseType: 'json'})

async function post_message(ev) {
    ev.preventDefault()
    display_loader(1)

    const formdata = new FormData(ev.target)
    await axios.post('/send', formdata).then(res => {
        if (res.data.result === true) {
            display_alert('success', res.data.message)
            return
        }

        display_alert('error', res.data.message)

    }).catch(() => {
        display_alert('error', 'Erro ao processar o pedido, tente novamente !')
    })

    display_loader(0)
}

async function post_request(ev) {
    ev.preventDefault()
    display_loader(1)

    const formdata = new FormData(ev.target)
    await axios.post('/request_credits', formdata).then(res => {
        if (res.data.result === true) {
            display_alert('success', res.data.message)
            return
        }

        display_alert('error', res.data.message)

    }).catch(() => {
        display_alert('error', 'Erro ao processar o pedido, tente novamente !')
    })

    display_loader(0)
}

$(document).ready(function() {
    $('.js-example-basic-single').select2({
        placeholder: 'Selecione o cliente',
        allowClear: true,
        tags: true
    });

    $('#body').countSms('#sms-counter');
});