let getCheckboxWipe = document.getElementById("server_wipe");

let getWipeDateContainer = document.getElementById('wipeDate')

getWipeDateContainer.classList.add("displayNone");


getCheckboxWipe.addEventListener("click", () => {
  getWipeDateContainer.classList.toggle("displayNone");
});
