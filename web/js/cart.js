$(document).ready(function() {
    $('.modal').modal();
});


function redirectEliminar(id) {

    window.location.href = "/cart/logged/delete/" + id;
}


function vaciar() {

    window.location.href = "/cart/logged/deleteall";
}