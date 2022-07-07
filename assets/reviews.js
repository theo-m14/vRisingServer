let getServerReviews = document.querySelector(".server-reviews");

let getServerId = getServerReviews.getAttribute("server-id");

console.log(getServerId);

window.onload = () => {
  fetch("/serveur/" + getServerId + "/avis")
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      getServerReviews.innerHTML = data.content;
    })
    .then(() => {
      paginationListener();
    });
};

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
          getServerReviews.innerHTML = data.content;
        })
        .then(() => {
          paginationListener();
          document
            .querySelector(".pagination")
            .scrollIntoView({ behavior: "smooth" });
        })
        .catch((e) => console.log(e));
    });
  });
};
