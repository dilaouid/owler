const xhr = new XMLHttpRequest();
const req = ["api/database", "api/platform", "api/admin", "api/install"];
const title = ["Connexion à la base de donnée", "Information sur la plateforme", "Compte administrateur", "Conditions"];
var step = 1; // The current step of the form, 1 by default

/* Set the loading spinner in the next button */
const setLoading = (active) => {
  $('.fieldErrorInputInstallForm').remove();
  $(active ? "#loading" : step < 4 ?? "#next-button-icon").removeClass('d-none');
  $(active ? step < 4 ?? "#next-button-icon" : "#loading").addClass('d-none');
  active ? $("#next").prop("disabled", true) : $("#next").removeAttr("disabled");
};

/* Generate the error label after the wrong input field */
const generateErrorLabel = (parent, message) => {
  const label = document.createElement("label");
  label.classList.add("form-label", "text-danger", "fieldErrorInputInstallForm");

  const icon = document.createElement("i");
  icon.classList.add("fas", "fa-exclamation-triangle");

  const span = document.createElement("span");
  span.innerHTML = ' ' + message;
  label.append(icon, span);

  parent.append(label);
};

/* Convert the input data into query string to send to the api */
const convertToQueryString = (props) => {
    const objQueryString = { ...props };
    for (const key in objQueryString) {
      if (!key)
        delete objQueryString[key];
    }
    const params = JSON.stringify(objQueryString);
    const qs = params
      .replace(/[/''""{}]/g, '')
      .replace(/[:]/g, '=')
      .replace(/[,]/g, '&');
    return (qs);
};

/* Fill the data object to send to the api according to the current step */
const fillInputObj = () => {
    var data;
    if (isNaN(step) || step < 1 || step > 4) return;
    const childInput = $(`#${step !== 4 ? `step-${step}-form` : 'form-col'} :input`);
    childInput.map( (i, el) => {
        data = { ...data, [el.id]: ~el.id.indexOf('condition') ? el.checked : el.value };
        if (data[el.id] === false) delete data[el.id];
    });
    return (data);
};

/* Set a border danger to the wrong input field */
const setBorderDanger = (input, message, stepToChange) => {
  const childInput = $(`#${stepToChange !== 4 ? `step-${stepToChange}-form` : 'form-col'} :input`);
  childInput.map( (i, el) => {
    el.classList.remove('border-danger');
  });
  input.map( (el, i) => {
    const isObject = el instanceof Object;
    const prefix = `#${isObject ? Object.keys(el) : el}`;
    $(prefix).addClass('border-danger');
    generateErrorLabel($(`${prefix}_group`), isObject ? Object.values(el) : message);
  });
};

/* Change the button, make it enabled or disabled */
const changeButton = (element, active) => {
  const classes1 = "btn-outline-dark btn-off";
  const classes2 = "btn-outline-primary";
  element.prop("disabled", !active);
  element.removeClass(active ? classes1 : classes2).addClass(active ? classes2 : classes1);
};

/* Go to the next or previous step, changing the buttons attributes */
const goToStep = (next) => {
  $(`#step-${step}-form`).addClass("d-none");
  $(`#step-${step + 1}-form`).removeClass("d-none");
  $(`#step-${step}`).removeClass("text-primary")
                    .addClass("text-black-50");
  step += next;
  $(`#step-${step}`).removeClass("text-black-50")
                    .addClass("text-primary");

  changeButton($(`#prev`), step > 1);
  $('#NextBtnMessage').html(step === 4 ? "Finir l'installation" : '');
  const nextBtnIcon = $(`#next-button-icon`);
  if (step === 4 && !nextBtnIcon.hasClass("d-none")) {
    nextBtnIcon.addClass("d-none");
  } else if (nextBtnIcon.hasClass("d-none") && step < 4) {
    nextBtnIcon.removeClass("d-none");
  }
  $('#title-form-heading').html(title[step - 1]);
};

/* Clicking on the next step button */
$('#next').click( () => {
    setLoading(true);
    const data = fillInputObj();
    const qs = convertToQueryString(data);
    xhr.open('post', req[step - 1], true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            setLoading(false);
            var res = JSON.parse(xhr.response);
            if (xhr.status === 200 && step < 4)
              goToStep(1);
            setBorderDanger(res.data, res.message, res.success ? step - 1 : step);
        }
    };
    xhr.send(qs);
});

/* Clicking on the previous step button */
$('#prev').click( () => {
  if (step === 1) return;
  goToStep(-1);
});