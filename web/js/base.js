$(document).ready(function() {
    $('#menu_principal').sidenav();
});


// funcion que muestra un toast
function showToast() {

    //primero eliminamos todos los toast
    M.Toast.dismissAll();

    M.toast({ html: 'En Construcción!' })
}