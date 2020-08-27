class algo {
  constructor() {
    this.btnNuevo = document.getElementById("btnNuevo");
    this.$modal = $("#modalNuevo");
    this.btnNewquestions = document.getElementById("btnNewquestions");
    this.ContentQuestions = document.querySelector("#ContentQuestions");
    this.btnSave = document.getElementById("btnSave");
  }
  _openModal() {
    this.btnNuevo.onclick = (e) => {
      e.preventDefault();
      this.$modal.modal("show");
      this.saveTask();
    };
  }

  _addTask() {
    let indx = 0;
    this.btnNewquestions.onclick = (e) => {
      e.preventDefault();
      indx++;
      let div = document.createElement("div");
      let lbl = document.createElement("label");
      let inpt = document.createElement("input");
      let btn = document.createElement("button");
      let select = document.createElement("select");
      let option = document.createElement("option");

      inpt.type = "text";
      inpt.className = "form-control input-sm";
      inpt.name = "questions[]";

      div.id = `idQuestion_${indx}`;
      div.className =
        "form-group col-lg-12 col-md-10 d-flex justify-content-between contenidox";
      lbl.textContent = `N° ${indx}`;
      lbl.className = "ml-2";
      btn.id = `btnDelQuestion_${indx}`;
      btn.className = "btn btn-danger btn-sm removex";
      btn.textContent = "X";

      div.appendChild(lbl);
      div.appendChild(inpt);

      div.innerHTML += `
                <select name="types[]" id="" class="form-control select-sm mx-2" >
                  <option value="1">Si - No</option>
                  <option value="2">1 - 5</option>
                </select>
      `;
      div.appendChild(btn);
      this.ContentQuestions.appendChild(div);
      this._delQuestion();
    };
  }

  _delQuestion() {
    ContentQuestions.onclick = (e) => {
      e.preventDefault();
      if (e.target.classList.contains("removex")) {
        e.target.parentElement.remove();
      }
      return false;
    };
  }

  _eliminar() {
    let del = document.querySelector("#tblEncuestas table");

    del.onclick = (e) => {
      if (e.target.classList.contains("borrar")) {
        let id = e.target.dataset.id;
        let url = solicitudAjax.url;
        let datos = {
          action: "peticionEliminar",
          nonce: solicitudAjax.seguridad,
          id: id,
        };

        $.ajax({
          type: "POST",
          url: url,
          data: {
            action: "peticionEliminar",
            nonce: solicitudAjax.seguridad,
            id: id,
          },
          //dataType: "dataType",
          success: function (response) {
            location.reload();
          },
        });
      }
    };
  }

  saveTask(){
    this.btnSave.onclick = (e)=>{
        e.preventDefault();
        let datos = new FormData(document.querySelector('#FrmTask'));
        datos.append('action','peticionGuardar');
        datos.append('nonce',solicitudAjax_Guardar.seguridad);
        let url = solicitudAjax_Guardar.url;
        // console.log(datos.get('action'));
        // console.log(datos.get('action2'));
        // console.log(datos.get('action3'));
        $.ajax({
          type: "POST",
          url: url,
          data: datos,
          processData: false,
          contentType:false,
          //dataType: "dataType",
          success: function (response) {
            console.log(response);
            alert('Dato guardado con exito');
            location.reload();
          },
        });
    }
  }

  init() {
    console.log("estoy aqui");
    this._openModal();
    this._addTask();
    this._eliminar();
    console.log(solicitudAjax);
  }
}

$(() => {
  new algo().init();
});
