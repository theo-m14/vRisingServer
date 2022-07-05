let getStarContainer = document.querySelector('#form-review .star-container');

let getAllStar = document.querySelectorAll('#form-review .star-container div');

let getSelect = document.getElementById('review-note');

getAllStar[0].classList.remove('empty-star');
getAllStar[0].classList.add('full-star');

getAllStar.forEach((star) => {
    //OnClick
    star.addEventListener('click', () => {
        if(star.classList.contains('empty-star')){
            getSelect.value = star.getAttribute('star-number');
            for (let i = 0; i < star.getAttribute('star-number'); i++) {
                getAllStar[i].classList.remove('empty-star');
                getAllStar[i].classList.add('full-star');
            }
        }else{
            getSelect.value = star.getAttribute('star-number');
            for(let i = 4; i >= star.getAttribute('star-number');i--){
                getAllStar[i].classList.add('empty-star');
                getAllStar[i].classList.remove('full-star');
            }
        }
    })
})





