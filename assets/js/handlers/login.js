document.getElementById("form").onsubmit = ((e)=>{
    e.preventDefault();
    let formdata = new FormData(document.getElementById("form"));
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {

        if (this.readyState === 1 || this.readyState === 2 || this.readyState === 3) {
            loader("1");
            hide();
        }

        if (this.readyState === 4 && this.status === 200) {
            loader("2");
            let alert = document.getElementById('callback');

            if (this.response.type === "msg"){
                alert.classList.replace("{type}", this.response.pm.type);
                alert.insertAdjacentHTML("beforeend", this.response.pm.msg)
                alert.hidden = false;
            }else if(this.response.type === "redirect"){
                window.location.replace(this.response.pm);
            }
        }
    }

    xhr.open("POST", page);
    xhr.responseType = 'json';
    xhr.send(formdata);
});

function hide() {
    let alert = document.getElementById('callback');
    alert.classList = "alert {type} alert-dismissible fade show";
    alert.hidden = true;
    alert.innerHTML = '<button type="button" class="close" onclick="hide()"><span>&times;</span></button>';
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
