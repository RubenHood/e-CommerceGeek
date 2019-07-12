//funcion para añadir un producto a la cesta
function anyadir(id) {

    //obtenemos el valor de select
    var select = document.getElementById('select').value;

    if (select == "") {
        showToast();
    } else {
        window.location.href = "/cart/logged/add/" + id + "/" + select;
    }
}


// funcion que muestra un toast
function showToast() {

    //primero eliminamos todos los toast
    M.Toast.dismissAll();

    M.toast({ html: 'Debe elegir una cantidad!' })
}