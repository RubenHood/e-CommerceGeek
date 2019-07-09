$(document).ready(function() {
    $('#menu_principal').sidenav();
    $(".dropdown-trigger").dropdown({
        inDuration: 300,
        outDuration: 225,
        belowOrigin: true, // Displays dropdown below the button
        alignment: 'right' // Displays dropdown with edge aligned to the left of button
    });
});


// funcion que muestra un toast
function showToast() {

    //primero eliminamos todos los toast
    M.Toast.dismissAll();

    M.toast({ html: 'En Construcción!' })
}