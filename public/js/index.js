// public/js/index.js
//
// Handles interactions with the index page

// "the Beasleys" fade
const theBeasleys = document.getElementById('the-beasleys');

window.addEventListener('DOMContentLoaded', () => {
    // unhide "the Beasleys"
    for (let i = 0; i < theBeasleys.childElementCount; i++) {
        theBeasleys.children.item(i).classList.replace('opacity-0', 'opacity-100');
    }

    // unhide the nav in a dramatic fashion
    document.getElementById('nav').classList.replace('opacity-0', 'opacity-100');
})


const pictureOfUs = document.getElementById('welcome-section');
window.addEventListener('scroll', (e) => {
  let imagePosition = pictureOfUs.getBoundingClientRect();
  if (imagePosition.top / window.innerHeight > 0.8) {
    resetFadeElement(pictureOfUs);
  }
})
