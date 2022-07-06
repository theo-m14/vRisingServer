let getBurger = document.getElementById("burger");

let getMenu = document.querySelector(".menu");

getBurger.addEventListener("click", () => {
  getMenu.classList.toggle("active");
  getBurger.classList.toggle("active");
});
