window.onload = () => {
  //crear boton ir a facturacion
  if (document.querySelector("body.wp-admin")) {
    const anchor = document.createElement("a");
    anchor.href =
      "https://" + window.location.hostname + "/wp-admin/edit.php?post_type=shop_order";
    anchor.innerHTML = "Ir a Facturacion";
    anchor.classList.add("go_to_pedidos_btn");

    document.querySelector("body").append(anchor);
  }

  if (document.querySelector("#order_status")) {

    setStatusMessage();
    createTransferenciaInputs();
  }

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
      document.querySelector(".co-admin-save-order").disabled = true;

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
        setOrderStatus("wc-completed");

        break;
      case "wc-processing":
        message = "Procesando";
        document.querySelector(
          "#order_data"
        ).innerHTML += `<div class="co-admin-status-order-mensaje ${message}">${message}</div>`;
        document.querySelector(".co-admin-save-order").style.display = "block";

        setOrderStatus("wc-completed");

        break;
      case "wc-completed":
        message = "Completado";
        document.querySelector(
          "#order_data"
        ).innerHTML += `<div class="co-admin-status-order-mensaje ${message}">${message}</div>`;
        document.querySelector(".co-admin-edit-order").style.display = "block";
        document.querySelector(".co-admin-imprimir-order").style.display =
          "block";
        setOrderStatus("wc-pending");

        // console.log(statusInput.value);
        break;

      default:
        break;
    }
  }

  function setOrderStatus(val) {
    document.querySelector("#order_status").value = val;
  }

  if (document.querySelector(".co-admin-imprimir-order")) {
    document
      .querySelector(".co-admin-imprimir-order")
      .addEventListener("click", (e) => {
        e.preventDefault();
        const id = e.target.dataset.id;
        window.location.search += "&imprimir=true&imprimir_id=" + id;
      });
  }


  //agrega el filtro de cedula en la lista de pedidos
  if (document.querySelector("form#posts-filter") && window.location.href.includes('post_type=shop_order')) {

    document.querySelector('.select2.select2-container.select2-container--default').style.display = 'none'
    document.querySelector('.tablenav.top').style.display = 'flex'
    document.querySelector("form#posts-filter .tablenav ").innerHTML += `<div>
    <input class="select3-search__field" placeholder="buscar por cédula" type="text" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="combobox" id="search_by_ci_in_list" >
    <button id="search_by_ci_in_list_button" class="button" disabled>Filtar</button></div>
`;

    let inputSelect = document.querySelector(".wc-customer-search.select2-hidden-accessible");

    document
      .querySelector("#search_by_ci_in_list")
      .addEventListener("input", e => {
        document.querySelector('#search_by_ci_in_list_button').disabled = true

        jQuery.ajax({
          type: "post",
          url: wp_ajax_tets_vars.ajaxUrl,
          data: "action=send-user-by-cedula&cedula=" + e.target.value,
          success: function (result) {
            if (result) {
              console.log(result)
              if (!inputSelect.querySelector(`option[value='${result.id}']`)) {
                const option = document.createElement("option");
                option.value = result.id;
                option.innerHTML = result.name;
                inputSelect.append(option);
              } else {
                inputSelect.value = "";
              }
              inputSelect.value = result.id;
              document.querySelector('#search_by_ci_in_list_button').disabled = false
            } else {
              inputSelect.value = "";
            }
          },
        });
      });

    document.querySelector('#search_by_ci_in_list_button').addEventListener('click', e => {
      e.preventDefault()
      document.querySelector('#post-query-submit').click()
    })


  }
};

function createTransferenciaInputs() {
  const extraInputs = `
  <div>
  <label for="bancp"> Banco </label>
  <input type="text" name="banco" id="banco" class="regular-text" required /><br />
  <label for="referencia"># Referencia </label>
  <input type="text" name="referencia" id="referencia"  class="regular-text" required /><br />
  </div>
  `;
  if (document.querySelector("#metodo-pago-transferencia")) {
    document
      .querySelector("#metodo-pago-transferencia")
      .addEventListener(
        "input",
        () =>
          (document.querySelector(
            "#extra-transferencia-inputs"
          ).innerHTML = extraInputs)
      );
    document
      .querySelector("#metodo-pago-mixto")
      .addEventListener(
        "input",
        () =>
          (document.querySelector(
            "#extra-transferencia-inputs"
          ).innerHTML = extraInputs)
      );
    document
      .querySelector("#metodo-pago-efectivo")
      .addEventListener("input", (e) => {
        document.querySelector("#extra-transferencia-inputs").innerHTML = "";
      });
  }


  if (document.querySelector('.button.add-order-item')) {
    console.log('if')
    document.querySelector('.button.add-order-item').addEventListener('click', () => {
      console.log('click')
    setTimeout(() => {
      
      document.querySelector('.modal-close.modal-close-link.dashicons.dashicons-no-alt').addEventListener('click', () => {
        console.log('remove')

        document.querySelector('#wc-backbone-modal-dialog').remove()
        document.querySelector('body').style.overflow = 'auto'
    }, 500);
      })
    })
  }

}
