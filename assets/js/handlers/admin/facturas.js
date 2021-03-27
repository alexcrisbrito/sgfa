function somar(){

    var x = document.getElementById("Consumo").value;
    var calculo = parseFloat(x) * 70.0;

    if(x <= 6.00){
        calculo = 420.00;
    }

    if (x === undefined){
        calculo = 0.00;
    }
    document.getElementById('resultado').innerHTML = calculo + " MT";
}

document.getElementById('form').addEventListener("submit",function (event) {
    event.preventDefault();

    var formdata = new FormData(document.getElementById('form'));
    var xhr = new XMLHttpRequest();
    xhr.open('POST','http://localhost/SGFA/admin/facturas/emitir',false);
    xhr.send(formdata);

    var response = JSON.parse(xhr.response);

    document.getElementById("callback").className = response.param.type;
    document.getElementById("callback").innerText = response.param.msg;

});
