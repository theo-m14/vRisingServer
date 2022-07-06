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
    .then(console.log("lets go"));
};
