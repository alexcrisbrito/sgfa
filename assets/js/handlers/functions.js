const domain = "https://aguasaz.online/portal/";

function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}


let d = new Date();
let month = [];
month[0] = "Janeiro";
month[1] = "Fevereiro";
month[2] = "Março";
month[3] = "Abril";
month[4] = "Maio";
month[5] = "Junho";
month[6] = "Julho";
month[7] = "Agosto";
month[8] = "Setembro";
month[9] = "Outubro";
month[10] = "Novembro";
month[11] = "Dezembro";

let select = document.getElementById('Mes');
if(select != null){
    select.children[d.getMonth()].selected = true;
}

if(document.getElementById("Ano") != null){
    document.getElementById("Ano").value = new Date().getFullYear();
}

function loader(action) {

    switch (action) {
        case "1":
            document.getElementById("ajaxloader").hidden = false;
            break;

        case "2":
            document.getElementById("ajaxloader").hidden = true;
            break;

    }
}

form.addEventListener("submit", function (e) {
    e.preventDefault();

    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {

        if (this.readyState === 1 || this.readyState === 2 || this.readyState === 3) {
            loader("1");
            hide();
        }

        if (this.readyState === 4 && this.status === 200) {
            loader("2");
            callback(this.response);
        }
    };
    let formdata = new FormData(form);
    xhr.open("POST", page);
    xhr.responseType = 'json';
    xhr.send(formdata);
});

function hide() {
    let alert = document.getElementById('callback');
    alert.classList = "col-sm-8 alert {type} alert-dismissible fade show";
    alert.hidden = true;
    alert.innerHTML = '<button type="button" class="close" onclick="hide()"><span>&times;</span></button>';
}

function callback(object) {
    let alert = document.getElementById('callback');
    let response = object;

    if(response.type === "redirect"){
        window.location.replace(response.pm);
    }

    if (response.type === "msg"){
        alert.classList.replace("{type}", response.pm.type);
        alert.insertAdjacentHTML("beforeend", response.pm.msg)
        alert.hidden = false;
    }

    let table = document.getElementById("dataTable");
    lastId++;

    if(response.pm.type === "alert-success") {
        switch (window.location.href) {
            case domain+"admin/facturas":
                let row = table.insertRow(1);
                row.id = lastId;
                row.insertCell(0).innerHTML = lastId;
                row.insertCell(1).innerHTML = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
                let nomeCliente1 = form.elements.namedItem("Cliente").options.namedItem("cl" + form.elements.namedItem("Cliente").value).innerText;
                row.insertCell(2).innerHTML = nomeCliente1.split(" - ")[1];
                row.insertCell(3).innerHTML = number_format(parseFloat(form.elements.namedItem("Consumo").value), 2, ".", ",") + " m3";
                row.insertCell(4).innerHTML = number_format(parseFloat(form.elements.namedItem("Consumo").value) * 70.00, 2, ".", ",") + " MT";
                row.insertCell(5).innerHTML = number_format(parseFloat(form.elements.namedItem("Consumo").value) * 70.00, 2, ".", ",") + " MT";
                row.insertCell(6).innerHTML = "Em dívida";
                row.insertCell(7).innerHTML =
                    '<button onclick="cancel_invoice(' + lastId + ')" class="btn btn-info btn-circle btn-sm">' +
                    '   <i class="fas fa-times"></i>' +
                    '</button>\n' +
                    '<a href="'+domain+'admin/facturas/visualizar/' + lastId + '" class="btn btn-success btn-circle btn-sm">' +
                    '   <i class="fas fa-eye"></i>' +
                    '</a>\n' +
                    '<a href="'+domain+'admin/facturas/imprimir/' + lastId + '" class="btn btn-dark btn-circle btn-sm">' +
                    '   <i class="fas fa-print"></i>' +
                    '</a>';
                row.cells[7].classList.add("text-center");
                break;

            case domain+"admin/recibos":
                let row1 = table.insertRow(1);
                row1.id = lastId;
                row1.insertCell(0).innerHTML = lastId;
                row1.insertCell(1).innerHTML = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
                row1.insertCell(2).innerHTML = form.elements.namedItem("Invoice").value;
                row1.insertCell(3).innerHTML = number_format(parseFloat(form.elements.namedItem("Valor").value), 2, ".", ",") + " MT";
                row1.insertCell(4).innerHTML = form.elements.namedItem("Meio").value;
                row1.insertCell(5).innerHTML =
                    '<a href="#" class="btn btn-info btn-circle btn-sm">' +
                    '   <i class="fas fa-eye"></i>\n' +
                    '</a>\n' +
                    '<a href="#" class="btn btn-dark btn-circle btn-sm">\n' +
                    '  <i class="fas fa-print"></i>\n' +
                    '</a>';
                row1.cells[5].classList.add("text-center");
                break;

            case domain+"admin/clientes":
                let row2 = table.insertRow();
                row2.id = lastId;
                row2.insertCell(0).innerHTML = lastId;
                row2.insertCell(1).innerHTML = form.elements.namedItem("Nome").value;
                row2.insertCell(2).innerHTML = form.elements.namedItem("Celular").value;
                row2.insertCell(3).innerHTML = form.elements.namedItem("Morada").value;
                row2.insertCell(4).innerHTML = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
                row2.insertCell(5).innerHTML =
                    '<a href="#" class="btn btn-info btn-circle btn-sm">' +
                    '   <i class="fas fa-pencil"></i>\n' +
                    '</a>\n' +
                    '<button onclick="deactivate_client('+lastId+',1)" class="btn btn-warning btn-circle btn-sm">\n' +
                    '  <i class="fas fa-times-circle"></i>\n' +
                    '</button>';
                row2.cells[5].classList.add("text-center");
                break;

            case domain+"admin/financeiro":
                let row3 = table.insertRow(1);
                row3.id = lastId;
                row3.insertCell(0).innerHTML = form.elements.namedItem("Nome").value;
                row3.insertCell(1).innerHTML = number_format(parseFloat(form.elements.namedItem("Valor").value), 2, ".", ",") + " MT";
                row3.insertCell(2).innerHTML = form.elements.namedItem("Meio").value;
                row3.insertCell(3).innerHTML = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
                row3.insertCell(4).innerHTML =
                    '<a href="delete_expense('+lastId+')" class="btn btn-danger btn-circle btn-sm">\n' +
                    '  <i class="fas fa-trash"></i>' +
                    '</a>';
                row3.cells[4].classList.add("text-center");

                let p0 = labelspie[0];
                let p1 = labelspie[1];
                let p0d = dataPie[0];
                let p1d = dataPie[1];

                labelspie[1] = p0;
                dataPie[1] = p0d;
                labelspie[2] = p1;
                dataPie[2] = p1d;

                dataArea[d.getMonth()] -= parseFloat(form.elements.namedItem("Valor").value);
                labelspie[0] = form.elements.namedItem("Nome").value;
                dataPie[0] = form.elements.namedItem("Valor").value;

                myLineChart.update();
                myPieChart.update();

                break;

            case domain+"func/facturas":
                let row4 = table.insertRow(1);
                row4.id = lastId;
                row4.insertCell(0).innerHTML = lastId;
                row4.insertCell(1).innerHTML = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
                let nomeCliente = form.elements.namedItem("Cliente").options.namedItem("cl" + form.elements.namedItem("Cliente").value).innerText;
                row4.insertCell(2).innerHTML = nomeCliente.split(" - ")[1];
                row4.insertCell(3).innerHTML = number_format(parseFloat(form.elements.namedItem("Consumo").value), 2, ".", ",") + " m3";
                row4.insertCell(4).innerHTML = number_format(parseFloat(form.elements.namedItem("Consumo").value) * 70.00, 2, ".", ",") + " MT";
                row4.insertCell(5).innerHTML = number_format(parseFloat(form.elements.namedItem("Consumo").value) * 70.00, 2, ".", ",") + " MT";
                row4.insertCell(6).innerHTML = "Em dívida";
                row4.insertCell(7).innerHTML =
                    '<a href="'+domain+'admin/facturas/visualizar/' + lastId + '" class="btn btn-success btn-circle btn-sm">' +
                    '   <i class="fas fa-eye"></i>' +
                    '</a>\n' +
                    '<a href="'+domain+'admin/facturas/imprimir/' + lastId + '" class="btn btn-dark btn-circle btn-sm">' +
                    '   <i class="fas fa-print"></i>' +
                    '</a>';
                row4.cells[7].classList.add("text-center");
                break;

            case domain+"func/recibos":
                let row5 = table.insertRow(1);
                row5.id = lastId;
                row5.insertCell(0).innerHTML = lastId;
                row5.insertCell(1).innerHTML = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
                row5.insertCell(2).innerHTML = form.elements.namedItem("Invoice").value;
                row5.insertCell(3).innerHTML = number_format(parseFloat(form.elements.namedItem("Valor").value), 2, ".", ",") + " MT";
                row5.insertCell(4).innerHTML = form.elements.namedItem("Meio").value;
                row5.insertCell(5).innerHTML =
                    '<a href="#" class="btn btn-info btn-circle btn-sm">' +
                    '   <i class="fas fa-eye"></i>\n' +
                    '</a>\n' +
                    '<a href="#" class="btn btn-dark btn-circle btn-sm">\n' +
                    '  <i class="fas fa-print"></i>\n' +
                    '</a>';
                row5.cells[5].classList.add("text-center");
                break;

            case domain+"func/clientes":
                let row6 = table.insertRow();
                row6.id = lastId;
                row6.insertCell(0).innerHTML = lastId;
                row6.insertCell(1).innerHTML = form.elements.namedItem("Nome").value;
                row6.insertCell(2).innerHTML = form.elements.namedItem("Celular").value;
                row6.insertCell(3).innerHTML = form.elements.namedItem("Morada").value;
                row6.insertCell(4).innerHTML = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
                row6.insertCell(5).innerHTML =
                    '<a href="#" class="btn btn-info btn-circle btn-sm">' +
                    '   <i class="fas fa-pencil"></i>\n' +
                    '</a>\n' +
                    '<button onclick="deactivate_client('+lastId+',1)" class="btn btn-warning btn-circle btn-sm">\n' +
                    '  <i class="fas fa-times-circle"></i>\n' +
                    '</button>';
                row6.cells[5].classList.add("text-center");
                break;


        }

        form.reset();
    }
}

function cancel_invoice(number){
    Swal.fire({
        icon:'question',
        title: 'Deseja cancelar a factura '+number+'?',
        showCancelButton: true,
        confirmButtonText: 'SIM <i class="fa fa-check-circle"></i>',
        confirmButtonColor: "#1cc88a",
        cancelButtonColor: "#e74a3b",
        cancelButtonText: 'NÃO <i class="fa fa-times-circle"></i>'
    }).then((result) => {
        if (result.isConfirmed) {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState === 1 || this.readyState === 2 || this.readyState === 3) {
                    loader("1");
                }
                if (this.readyState === 4 && this.status === 200) {
                    loader("2");

                    console.log(this.response.pm);

                    if(this.response.pm.type === "success"){
                        document.getElementById(number).cells[5].innerHTML = "0.00 MT";
                        document.getElementById(number).cells[6].innerHTML = "Cancelada";
                        document.getElementById(number).cells[7].innerHTML =
                            '<button onclick="delete_invoice('+number+' )" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i> </button>';

                        Swal.fire({
                            icon:"success",
                            title:"Cancelou a factura com sucesso !",
                            confirmButtonColor:"#1cc88a",
                            showConfirmButton:true
                        });
                    }else{
                        Swal.fire({
                            icon:"error",
                            title:"Erro ao cancelar a factura, tente novamente !",
                        });
                    }
                }
            };

            xhr.open("POST", pageeditar);
            xhr.responseType = 'json';
            let data = new FormData();
            data.append('id',number);
            xhr.send(data);
        }
    });
}


function clean_fine(number) {
    Swal.fire({
        icon:"question",
        title: 'Deseja cancelar a divida da factura '+number+'?',
        showCancelButton: true,
        confirmButtonText: 'SIM <i class="fa fa-check-circle"></i>',
        confirmButtonColor: "#1cc88a",
        cancelButtonColor: "#e74a3b",
        cancelButtonText: 'NÃO <i class="fa fa-times-circle"></i>'
    }).then((result) => {
        if (result.isConfirmed) {
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState === 1 || this.readyState === 2 || this.readyState === 3) {
                    loader("1");
                }
                if (this.readyState === 4 && this.status === 200) {
                    loader("2");

                    if(this.response.pm.type === "success"){
                        document.getElementById(number).cells[5].innerHTML = number_format(parseFloat(document.getElementById(number).cells[4].innerHTML),2,",","") + " MT";
                        document.getElementById(number).cells[6].innerHTML = "C. Multa";
                        document.getElementById(number).cells[7].innerHTML =
                            '<button onclick="delete_invoice('+number+')" class="btn btn-danger btn-circle btn-sm"><i class="fas fa-trash"></i> </button>\n' +
                            '<a title="Visualizar" href="'+domain+'admin/facturas/visualizar/'+number+'" class="btn btn-success btn-circle btn-sm">\n' +
                            '    <i class="fas fa-eye"></i>\n' +
                            '</a>\n' +
                            '<a title="Baixar" href="'+domain+'admin/facturas/imprimir/'+number+'" class="btn btn-dark btn-circle btn-sm">\n' +
                            '    <i class="fas fa-print"></i>\n' +
                            '</a>'

                        ;

                        Swal.fire({
                            icon:"success",
                            title:"Cancelou a multa da factura com sucesso !",
                            confirmButtonColor:"#1cc88a",
                            showConfirmButton:true
                        });
                    }else if(this.response.pm.type === "error"){
                        Swal.fire({
                            icon:"error",
                            title:"Erro ao cancelar multa da factura, tente novamente !",
                            confirmButtonColor:"#e74a3b",
                            showConfirmButton:true
                        });
                    }
                }
            };

            xhr.open("POST", pagefine);
            xhr.responseType = 'json';
            let data = new FormData();
            data.append('id',number);
            xhr.send(data);
        }
    });
}

function delete_invoice(number){
    Swal.fire({
        icon:"question",
        title: 'Deseja apagar a factura '+number+'?',
        showCancelButton: true,
        confirmButtonText: 'SIM <i class="fa fa-check-circle"></i>',
        confirmButtonColor: "#1cc88a",
        cancelButtonColor: "#e74a3b",
        cancelButtonText: 'NÃO <i class="fa fa-times-circle"></i>'
    }).then((result) => {
        if (result.isConfirmed) {
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState === 1 || this.readyState === 2 || this.readyState === 3) {
                    loader("1");
                }
                if (this.readyState === 4 && this.status === 200) {
                    loader("2");

                    if(this.response.pm.type === "success"){
                        document.getElementById(number).remove();
                        Swal.fire({
                            icon:"success",
                            title:"Apagou a factura com sucesso !",
                            confirmButtonColor:"#1cc88a",
                            showConfirmButton:true
                        });
                    }else if(this.response.pm.type === "error"){
                        Swal.fire({
                            icon:"error",
                            title:"Erro ao apagar a factura, tente novamente !",
                            confirmButtonColor:"#e74a3b",
                            showConfirmButton:true
                        });
                    }
                }
            };

            xhr.open("POST", pagedelete);
            xhr.responseType = 'json';
            let data = new FormData();
            data.append('id',number);
            xhr.send(data);
        }
    });
}

function delete_expense(number){
    Swal.fire({
        icon:"question",
        title: 'Deseja apagar a despesa ?',
        showCancelButton: true,
        confirmButtonText: 'SIM <i class="fa fa-check-circle"></i>',
        confirmButtonColor: "#1cc88a",
        cancelButtonColor: "#e74a3b",
        cancelButtonText: 'NÃO <i class="fa fa-times-circle"></i>'
    }).then((result) => {
        if (result.isConfirmed) {
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (this.readyState === 1 || this.readyState === 2 || this.readyState === 3) {
                    loader("1");
                }
                if (this.readyState === 4 && this.status === 200) {
                    loader("2");

                    if(this.response.pm.type === "success"){

                        document.getElementById(number).remove();
                        Swal.fire({
                            icon:"success",
                            title:"Apagou a despesa com sucesso !",
                            confirmButtonColor:"#1cc88a",
                            showConfirmButton:true
                        });
                    }else if(this.response.pm.type === "error"){
                        Swal.fire({
                            icon:"error",
                            title:"Erro ao apagar a despesa, tente novamente !",
                            confirmButtonColor:"#e74a3b",
                            showConfirmButton:true
                        });
                    }
                }
            };

            xhr.open("POST", pagedeleteex);
            xhr.responseType = 'json';
            let data = new FormData();
            data.append('id',number);
            xhr.send(data);
        }
    });
}

function deactivate_client(number,action) {

    if(action === 1) {
        Swal.fire({
            icon: "question",
            title: 'Deseja desactivar o cliente ?',
            showCancelButton: true,
            confirmButtonText: 'SIM <i class="fa fa-check-circle"></i>',
            confirmButtonColor: "#1cc88a",
            cancelButtonColor: "#e74a3b",
            cancelButtonText: 'NÃO <i class="fa fa-times-circle"></i>'
        }).then((result) => {
            if (result.isConfirmed) {
                let xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (this.readyState === 1 || this.readyState === 2 || this.readyState === 3) {
                        loader("1");
                    }
                    if (this.readyState === 4 && this.status === 200) {
                        loader("2");

                        if (this.response.pm.type === "success") {
                            let btnr = document.getElementById(number).cells[5].childNodes[3];
                            btnr.innerHTML = '<i class="fas fa-check-circle"></i>';
                            btnr.className = 'btn btn-success btn-circle btn-sm';
                            btnr.setAttribute("onclick","deactivate_client("+number+",2)");

                            Swal.fire({
                                icon: "success",
                                title: "Desativou o cliente com sucesso, não será mais possível facturar sobre este cliente !",
                                confirmButtonColor: "#1cc88a",
                                showConfirmButton: true
                            });
                        } else if (this.response.pm.type === "error") {
                            Swal.fire({
                                icon: "error",
                                title: "Erro ao desativar o cliente, tente novamente !",
                                confirmButtonColor: "#e74a3b",
                                showConfirmButton: true
                            });
                        }
                    }
                };

                xhr.open("POST", pagestate);
                xhr.responseType = 'json';
                let data = new FormData();
                data.append('id', number);
                data.append('action',2);
                xhr.send(data);
            }
        });
    }else if(action === 2){
        Swal.fire({
            icon: "question",
            title: 'Deseja reactivar o cliente ?',
            showCancelButton: true,
            confirmButtonText: 'SIM <i class="fa fa-check-circle"></i>',
            confirmButtonColor: "#1cc88a",
            cancelButtonColor: "#e74a3b",
            cancelButtonText: 'NÃO <i class="fa fa-times-circle"></i>'
        }).then((result) => {
            if (result.isConfirmed) {
                let xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (this.readyState === 1 || this.readyState === 2 || this.readyState === 3) {
                        loader("1");
                    }
                    if (this.readyState === 4 && this.status === 200) {
                        loader("2");

                        if (this.response.pm.type === "success") {
                            let btnr = document.getElementById(number).cells[5].childNodes[3];
                            btnr.innerHTML = '<i class="fas fa-times-circle"></i>';
                            btnr.className = 'btn btn-warning btn-circle btn-sm';
                            btnr.setAttribute("onclick","deactivate_client("+number+",1)");
                            Swal.fire({
                                icon: "success",
                                title: "Reactivou o cliente com sucesso, já pode facturar sobre este cliente !",
                                confirmButtonColor: "#1cc88a",
                                showConfirmButton: true
                            });
                        } else if (this.response.pm.type === "error") {
                            Swal.fire({
                                icon: "error",
                                title: "Erro ao activar o cliente, tente novamente !",
                                confirmButtonColor: "#e74a3b",
                                showConfirmButton: true
                            });
                        }
                    }
                };

                xhr.open("POST", pagestate);
                xhr.responseType = 'json';
                let data = new FormData();
                data.append('id', number);
                data.append('action',1);
                xhr.send(data);
            }
        });
    }
}