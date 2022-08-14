const xhr = new XMLHttpRequest();
const req = ["api/database.php", "api/platform.php", "api/admin.php", "api/"];
var step = 1;

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

$('#next').click( () => {
    const data = fillInputObj();
    const qs = convertToQueryString(data);
    xhr.open('post', req[step - 1], true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var res = JSON.parse(xhr.response);
            console.log(res);
        }
    };
    xhr.send(qs);
});