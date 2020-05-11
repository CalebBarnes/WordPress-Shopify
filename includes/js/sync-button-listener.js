jQuery(document).ready(function ($) {
  $("#trigger-shopify-products-sync").click(function (e) {
    $.ajax({
      type: "post",
      dataType: "json",
      url: myAjax.ajaxurl,
      data: {
        action: "shopify_products_sync",
      },
      success: function (response) {
        if (response.type === "success") {
          console.log({ response });
          alert("something worked: " + response.message);
        } else {
          alert("something went wrong");
        }
      },
      error: function (err) {
        console.log({ err });
      },
    });
  });
}); //end document ready jquery $ wrapper
