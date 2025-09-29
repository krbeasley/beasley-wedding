// public/js/index.js
//
// Handles interactions with the index page

// "the Beasleys" fade
const theBeasleys = document.getElementById('the-beasleys');

window.addEventListener('DOMContentLoaded', () => {
    for (let i = 0; i < theBeasleys.childElementCount; i++) {
        theBeasleys.children.item(i).classList.replace('opacity-0', 'opacity-100');
    }
})