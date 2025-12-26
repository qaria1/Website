"use strict";
$(".search-product").on("keyup", function () {
    let name = $(this).val();
    if (name.length > 0) {
        $.get(window.searchProductRoute, { searchValue: name }, function (response) {
            $(".search-result-box").html(response.result);
        });
    }
});
let selectProductSearch = $(".select-product-search");
selectProductSearch.on("click", ".select-product-item", function () {
    let productName = $(this).find(".product-name").text();
    let productId = $(this).find(".product-id").text();
    selectProductSearch.find("button.dropdown-toggle").text(productName);
    $(".product_id").val(productId);
});
 