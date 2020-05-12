jQuery(document).ready(function ($) {
  $("#trigger-shopify-products-sync").click(function (e) {
    $("#trigger-shopify-products-sync").text("Loading...");
    $.ajax({
      type: "post",
      dataType: "json",
      url: myAjax.ajaxurl,
      data: {
        action: "sync_shopify_products",
      },
      success: function (response) {
        if (response.type === "success") {
          console.log({ response });
        } else {
          alert("something went wrong");
        }
        $("#trigger-shopify-products-sync").text("Sync Products");
      },
      error: function (err) {
        console.log({ err });
        $("#trigger-shopify-products-sync").text("Sync Products");
      },
    });
  });
}); //end document ready jquery $ wrapper
