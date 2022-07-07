let getServerReviews = document.querySelector(".server-reviews");

let getServerId = getServerReviews.getAttribute("server-id");

let getDateFilter = document.querySelector(".review-filter .date-filter");

let getNoteFilter = document.querySelector(".review-filter .note-filter");

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

/*With filter*/

getDateFilter.addEventListener("click", () => {
  getDateFilter.classList.add("active");
  getNoteFilter.classList.remove("active");
  if (getDateFilter.innerText.includes("+")) {
    getDateFilter.innerText = getDateFilter.innerText.replace("+", "-");
    orderByCriteria("date", "desc");
  } else {
    getDateFilter.innerText = getDateFilter.innerText.replace("-", "+");
    orderByCriteria("date", "asc");
  }
});

getNoteFilter.addEventListener("click", () => {
  getNoteFilter.classList.add("active");
  getDateFilter.classList.remove("active");
  if (getNoteFilter.innerText.includes("↑")) {
    getNoteFilter.innerText = getNoteFilter.innerText.replace("↑", "↓");
    orderByCriteria("note", "desc");
  } else {
    getNoteFilter.innerText = getNoteFilter.innerText.replace("↓", "↑");
    orderByCriteria("note", "asc");
  }
});

const orderByCriteria = (criteria, order) => {
  fetch("/serveur/" + getServerId + "/avis" + "?" + criteria + "=" + order)
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
