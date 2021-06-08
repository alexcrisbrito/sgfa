function display_alert(type, message) {
    Swal.fire({
        icon: type,
        title: message,
        confirmButtonText: 'OK',
        confirmButtonColor: "#1cc88a",
    })
}

function display_prompt(text) {
    return Swal.fire({
        icon: 'question',
        title: text,
        showCancelButton: true,
        confirmButtonText: 'SIM <i class="fa fa-check-circle"></i>',
        confirmButtonColor: "#1cc88a",
        cancelButtonColor: "#e74a3b",
        cancelButtonText: 'NÃO <i class="fa fa-times-circle"></i>'
    }).then(res => {
        return res;
    })
}

function display_loader(state) {
    document.getElementById("ajaxloader").hidden = state === 0;
}

function enter_fullscreen(ev) {
    ev.preventDefault();

    if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullscreenElement
        || document.msFullscreenElement) {
        ev.target.innerHTML =
            '<i class="fa fa-expand-arrows mr-2"></i>\n' +
            'Ecrã cheio';
        document.exitFullscreen();
    }else {
        ev.target.innerHTML =
            '<i class="fa fa-compress-arrows-alt mr-2"></i>\n' +
            'Ecrã normal';
        document.documentElement.requestFullscreen().catch(() => {
            display_alert('error', "Modo cheio não suportado pelo dispositivo")
        })
    }
}


