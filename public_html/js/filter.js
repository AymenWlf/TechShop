$(document).ready(function() {
    $(".filter-dropdown, .text-button").click(function() {
        $(".edit-filter-modal").toggleClass("hidden");

    });
    $(".apply-button").click(function() {
        $(".edit-filter-modal").toggleClass("hidden");
        $(".filter, .filter-remove, .fa-plus, .fa-filter").toggleClass("filter-hidden");
        $(".filter-dropdown-text").text("Add filter");


    });

    $(".filter-remove").click(function() {
        $(".filter, .filter-remove, .fa-plus, .fa-filter").toggleClass("filter-hidden");
        $(".filter-dropdown-text").text("Filter dataset");
    });




});