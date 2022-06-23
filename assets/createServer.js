let getCheckboxWipe = document.getElementById("server_wipe");

let getLabelWipeDate = document.getElementById("wipeDate");
let getInputWipeDate = document.getElementById("server_wipe_date");

getLabelWipeDate.classList.add("displayNone");
getInputWipeDate.classList.add("displayNone");

getCheckboxWipe.addEventListener("click", () => {
  getLabelWipeDate.classList.toggle("displayNone");
  getInputWipeDate.classList.toggle("displayNone");
});
