function prenotaOrario(orario) {
  let formDati = document.getElementById("formDati");
  if (checkform(formDati)) {
    let formData = new FormData(formDati);

    console.log("ok");
  }
}

function checkform(form) {
  var inputs = form.getElementsByTagName("input");
  for (var i = 0; i < inputs.length; i++) {
    if (inputs[i].hasAttribute("required")) {
      if (inputs[i].value == "") {
        Swal.fire({
          icon: "error",
          text: "Per favore riempi tutti i campi",
        });
        return false;
      }
    }
  }
  return true;
}
