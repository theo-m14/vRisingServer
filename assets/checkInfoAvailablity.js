let getInput = document.querySelector("input");
let requestType;
let url;

let getAvailablityText = document.getElementById("infoAvailablity");
let getSubmitBtn = document.getElementById("submitBtn");

console.log("test");

if (document.getElementById("form_username")) {
  requestType = "username";
  url = "/profile/checkUsernameAvailablity?checkInfo=";
} else {
  requestType = "email";
  url = "/profile/checkEmailAvailablity?checkInfo=";
}

getInput.addEventListener("keyup", (e) => {
  fetch(url + e.target.value)
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      if (data.content) {
        getAvailablityText.innerText = "Disponible";
        getSubmitBtn.removeAttribute("disabled");
      } else {
        getAvailablityText.innerText = "Déjà pris";
        getSubmitBtn.setAttribute("disabled", true);
      }
    })
    .catch((e) => console.log(e));
});
