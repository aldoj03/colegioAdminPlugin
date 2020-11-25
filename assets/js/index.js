window.onload = () => {
  //crear boton ir a facturacion
  if (document.querySelector("body.wp-admin")) {
    const anchor = document.createElement("a");
    anchor.href =
      "http://localhost/wordpress/wp-admin/edit.php?post_type=shop_order";
    anchor.innerHTML = "Ir a Facturacion";
    anchor.classList.add("go_to_pedidos_btn");

    document.querySelector("body").append(anchor);
  }

  //setear status de pedido por defecto
  if (document.querySelector("#order_status")) {
    // console.log(document.querySelector("#order_status").value);

    // displayOrderActionsButtons(statusMensaje)
    setStatusMessage();
  }
//   console.log(document.querySelector("#order_status").value);

  //ajax para buscar por cedula dentro del pedido
  if (document.querySelector("#search_by_ci_in_order")) {
    let resutlDataContainer = document.querySelector(
      "#co-admin-search-by-ci-result-data"
    );
    let resultContainer = document.querySelector(
      "#co-admin-search-by-ci-result-container"
    );
    let inputText = document.querySelector("#search_by_ci_in_order");
    inputText.style.visibility = "visible";
    let inputSelect = document.querySelector("#customer_user");
    inputText.addEventListener("input", (e) => {
      resutlDataContainer.innerHTML = "";

      resultContainer.querySelector(".spinner").style.visibility = "visible";

      jQuery.ajax({
        type: "post",
        url: wp_ajax_tets_vars.ajaxUrl,
        data: "action=send-user-by-cedula&cedula=" + e.target.value,
        success: function (result) {
          if (result) {
            if (!inputSelect.querySelector(`option[value='${result.id}']`)) {
              const option = document.createElement("option");
              option.value = result.id;
              option.innerHTML = result.name;
              inputSelect.append(option);
            } else {
              inputSelect.value = "";
            }
            document.querySelector(".co-admin-save-order").disabled = false;
            resultContainer.querySelector(".spinner").style.visibility =
              "hidden";
            inputSelect.value = result.id;
            resutlDataContainer.innerHTML = `<p><h3>Nombre: ${result.name} </h3></p><p><h3>Hijos:${result.hijos}</h3></p>`;
          } else {
            document.querySelector(".co-admin-save-order").disabled = true;
            inputSelect.value = "";
          }
        },
      });
    });
  }



  function setStatusMessage() {
    let message;

    const statusInput = document.querySelector("#order_status");
    switch (statusInput.value) {
      case "wc-pending":
        message = "Procesando";
        document.querySelector(
          "#order_data"
        ).innerHTML += `<div class="co-admin-status-order-mensaje ${message}">${message}</div>`;
        document.querySelector(".co-admin-save-order").style.display = "block";
        // document.querySelector(".co-admin-save-order").disabled = true;
        // setTimeout(() => {
        //   statusInput.value = "wc-completed";
          console.log(statusInput.value);
        // }, 300);
        setOrderStatus("wc-completed")

        break;
      case "wc-processing":
        message = "Procesando";
        document.querySelector(
          "#order_data"
        ).innerHTML += `<div class="co-admin-status-order-mensaje ${message}">${message}</div>`;
        document.querySelector(".co-admin-save-order").style.display = "block";
        // document.querySelector(".co-admin-save-order").disabled = true;
        // setTimeout(() => {
        //   statusInput.value = "wc-completed";
        // }, 300);
        setOrderStatus("wc-completed")

        break;
      case "wc-completed":
        message = "Completado";
        document.querySelector(
          "#order_data"
        ).innerHTML += `<div class="co-admin-status-order-mensaje ${message}">${message}</div>`;
        document.querySelector(".co-admin-edit-order").style.display = "block";
        document.querySelector(".co-admin-imprimir-order").style.display =
          "block";
          setOrderStatus("wc-pending")
        //   statusInput.value = "wc-processing";
       
        // console.log(statusInput.value);
        break;

      default:
        break;
    }
  }

 function setOrderStatus(val){
    document.querySelector("#order_status").value = val
  }
};
