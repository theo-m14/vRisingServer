let searchForm = document.querySelector(".search-container form");

let getSearchBtn = document.getElementById("searchBtn");

let getServerContainer = document.querySelector(".server-container");

let getSearchError = document.querySelector(".search-error");

let getMainContent = document.querySelector('.main-content');

getSearchBtn.addEventListener("click", (e) => {
  e.preventDefault();
  e.stopPropagation();
  let formData = new FormData(searchForm);

  url = "/server-search?";

  Array.from(formData).forEach((element) => {
    url += element[0] + "=" + element[1] + "&";
  });

  url += "page=1";

  fetch(url)
    .then((response) => {
      if (response.status == 400) {
        throw new Error("Veuillez saisir des paramètres valides");
      }
      return response.json();
    })
    .then((data) => {
      getServerContainer.innerHTML = data.content;
    })
    .then(() => paginationListener())
    .catch((e) => (getSearchError.innerText = e.message));
});

const paginationListener = () => {
  getPagination = document.querySelectorAll(".pagination span a");
  getPagination.forEach((page) => {
    page.addEventListener("click", (e) => {
      e.preventDefault();
      fetch(e.target.href)
        .then((response) => {
          return response.json();
        })
        .then((data) => {
          getServerContainer.innerHTML = data.content;
        })
        .then(() => {
          paginationListener();
          console.log('test')
          getMainContent.scrollIntoView({behavior:"smooth"})
        })
        .catch((e) => console.log(e));
    });
  });
};
