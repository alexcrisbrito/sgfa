axios = axios.create({baseURL: location.protocol +'//' + location.host + "/secure/provider_payment", responseType: 'json'})

async function pay_invoice(ev) {
    ev.preventDefault();
    display_loader(1);

    const formdata = new FormData(ev.target);
    await axios.post(`/m-pesa`, formdata).then(res => {
        if (res.data.result == true) {
            display_alert('success', res.data.message);
            ev.target.reset();
            return;
        }

        display_alert('error', res.data.message)

    }).catch(() => {
        display_alert('error', "Erro ao processar o pedido, tente novamente !");
    })

    display_loader(0);
}