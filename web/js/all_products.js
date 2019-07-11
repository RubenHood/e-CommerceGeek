$(document).ready(function() {
    $('.modal').modal();
});


function redirect(id) {

    window.location.href = "/admin/products/delete/" + id;
}