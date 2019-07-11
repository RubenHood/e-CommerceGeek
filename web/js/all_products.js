$(document).ready(function() {
    $('.modal').modal();
});


function redirectProducts(id) {

    window.location.href = "/admin/products/delete/" + id;
}

function redirectUsers(id) {

    window.location.href = "/admin/users/delete/" + id;
}