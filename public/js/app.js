// public/js/app.js
//
// Handles the interaction with the app.js layout as well as global javascript functions like cookies and
// animations.

const fadeElements = document.querySelectorAll('.fade-in');
const returnToTopButton = document.getElementById('return-to-top');

returnToTopButton.addEventListener('click', () => {
    // Only scroll when the button is visible
    if (returnToTopButton.classList.contains('opacity-100')) {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        })
    }
});

window.addEventListener('DOMContentLoaded', () => {
    fadeElements.forEach((e) => {
        resetFadeElement(e);

        // check that none of the elements need to be shown immediately
        tryUnfadeElement(e);
    })

})
window.addEventListener('scroll', () => {
    for (let i = 0; i < fadeElements.length; i++) {
        let hidden = fadeElements[i].ariaHidden === 'true';

        if (hidden) {
            tryUnfadeElement(fadeElements[i]);
        }
    }

    // Show / hide the return to top button
    if (window.scrollY >= 400) {
        returnToTopButton.classList.replace('opacity-0', 'opacity-100');
    } else {
        returnToTopButton.classList.replace('opacity-100', 'opacity-0')
    }
})

function resetFadeElement(element) {
    element.ariaHidden = 'true';
    element.classList.remove('opacity-100');
    element.classList.add('opacity-0', 'transition-all', 'duration-250', 'delay-250');
}

function tryUnfadeElement(element) {
    const revealPadding = 300;
    const scrollY = window.scrollY + window.innerHeight;
    const boundingBox = element.getBoundingClientRect();

    // console.log(`Scroll: ${scrollY} :: BB: ${boundingBox.top}`)

    if (scrollY >= boundingBox.top + revealPadding) {
        // reveal the element
        // console.log(`Revealing fade element: ${element.id ?? "No id"}`);

        element.ariaHidden = 'false';
        element.classList.replace('opacity-0', 'opacity-100');

        return true;
    }

    return false;
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}