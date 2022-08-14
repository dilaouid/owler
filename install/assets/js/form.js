const xhr = new XMLHttpRequest();
const req = ["api/database.php", "api/platform.php", "api/admin.php", "api/"];
var step = 1;

const setLoading = (active) => {
  $('.fieldErrorInputInstallForm').remove();
  $(active ? "#loading" : "#next-button-icon").removeClass('d-none');
  $(active ? "#next-button-icon" : "#loading").addClass('d-none');
  active ? $("#next").prop("disabled", true) : $("#next").removeAttr("disabled");
};


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

const fillInputObj = () => {
    var data;
    if (isNaN(step) || step < 1 || step > 4) return;
    const childInput = $(`#${step !== 4 ? `step-${step}-form` : 'form-col'} :input`);
    childInput.map( (i, el) => {
        data = { ...data, [el.id]: ~el.id.indexOf('formCheck') ? el.checked : el.value };
    });
    return (data);
};

const setBorderDanger = (input, active, message) => {
  if (!active) {
    const childInput = $(`#${step !== 4 ? `step-${step}-form` : 'form-col'} :input`);
    childInput.map( (i, el) => {
      el.classList.remove('border-danger');
    });
  } else {
    input.map( (el, i) => {
      $('#' + el).addClass('border-danger');
      generateErrorLabel($(`#${el}_group`), message);
    })
  }
};

const changeButton = (element, active) => {
  const classes1 = "btn-outline-dark btn-off";
  const classes2 = "btn-outline-primary";
  element.prop("disabled", !active);
  element.removeClass(active ? classes1 : classes2).addClass(active ? classes2 : classes1);
};

const goToStep = (next) => {
  $(`#step-${step}-form`).addClass("d-none");
  $(`#step-${step + next}-form`).removeClass("d-none");
  $(`#step-${step}`).removeClass("text-primary")
                    .addClass("text-black-50");
  step += next;
  $(`#step-${step}`).removeClass("text-black-50")
                    .addClass("text-primary");

  changeButton($(`#prev`), step > 1);
  changeButton($(`#next`), step < 4);

};

$('#next').click( () => {
    setLoading(true);
    setBorderDanger([], false);
    const data = fillInputObj();
    const qs = convertToQueryString(data);
    xhr.open('post', req[step - 1], true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            setLoading(false);
            var res = JSON.parse(xhr.response);
            if (res.success == false)
              setBorderDanger(res.data, true, res.message);
            else
              goToStep(1);
        }
    };
    xhr.send(qs);
});

$('#prev').click( () => {
  if (step === 1) return;
  goToStep(-1);
})