
// side navbar
const navShowBtn = document.querySelector('.navbar-show-btn');
const navHideBtn = document.querySelector('.navbar-hide-btn');
const sideNavbar = document.getElementById('side-navbar');
navShowBtn.addEventListener('click', () => {
    sideNavbar.classList.add('side-navbar-show');
});

navHideBtn.addEventListener('click', () => {
    sideNavbar.classList.remove('side-navbar-show');
});

// category
const categoryItems = document.getElementById('category-list-items');
const categoryTogglerBtn = document.querySelector('.category-toggler-btn');
categoryTogglerBtn.addEventListener('click', () => {
    categoryItems.classList.toggle('show-category-items');

    if(categoryItems.classList.contains('show-category-items')){
        categoryTogglerBtn.querySelector('i').className = "fas fa-circle-arrow-up";
    } else{
        categoryTogglerBtn.querySelector('i').className = "fas fa-circle-arrow-down";
    }
});

// feedback
const feedbackItems = document.querySelectorAll('.feedback-item');
const feedbackBtns = document.querySelectorAll('.feedback-btn');
const feedbackDisplay = document.querySelector('#feedback-display');

let activeId = 1;
changeFeedback(activeId);
function changeFeedback(id){
    feedbackItems.forEach((item) => {
        if(id == item.dataset.id){
            // swapping data id
            [feedbackDisplay.dataset.id, item.dataset.id] = [item.dataset.id, feedbackDisplay.dataset.id];
            // swapping the innder content
            [feedbackDisplay.innerHTML, item.innerHTML] = [item.innerHTML, feedbackDisplay.innerHTML];
            // swapping quote image
            [feedbackDisplay.querySelector('img').src, item.querySelector('img').src] = [item.querySelector('img').src, feedbackDisplay.querySelector('img').src];
        }
    });
}

feedbackBtns.forEach((btn, index) =>{
    btn.addEventListener('click', () => {
        activeId = index + 1;
        feedbackBtnReset();
        btn.classList.add('feedback-active-btn');
        changeFeedback(activeId);
    });
});

function feedbackBtnReset(){
    feedbackBtns.forEach((btn) => {
        btn.classList.remove('feedback-active-btn');
    });
}



