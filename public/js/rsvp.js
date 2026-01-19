
const inputNameSearch = document.getElementById('inputNameSearch');
const hdnNameSearch = document.getElementById('hdnNameSearch');
const btnNameSearch = document.getElementById('btnNameSearch');
const txtNameSearchMessage = document.getElementById('txtNameSearchMessage');
const nameSearchResultsContainer = document.getElementById('nameSearchResultsContainer');
const divNameResults = document.getElementById('divNameResults');
const ringSpinner = document.getElementById('ring-spinner');
const panelConfirmPopup = document.getElementById('panelConfirmPopup');

// Toggle the visibility and disabled status of the search button based on the
// state of the search input
inputNameSearch.addEventListener('input', () => {
  btnNameSearch.classList.remove('hidden');
  const value = inputNameSearch.value;

  if (String(value).trim().length < 4) {
    btnNameSearch.disabled = true;
    btnNameSearch.ariaDisabled = "true";

    if (String(value).trim().length === 0) {
      btnNameSearch.classList.add('hidden');
    }
  } else {
    btnNameSearch.disabled = false;
    btnNameSearch.ariaDisabled = "false";
  }
});

// Event handler for the name search button
btnNameSearch.addEventListener('click', async () => {
  // transfer the search input to the hidden field
  hdnNameSearch.innerText = inputNameSearch.value;
  inputNameSearch.value = "";
  inputNameSearch.setAttribute('placeholder', hdnNameSearch.innerText);

  // disable the search button
  // it is re-enabled the next time the user selects the input field
  btnNameSearch.disabled = true;
  btnNameSearch.ariaDisabled = "true";

  // reset the results container back to its default state
  // then reshow it
  resetNameSearchResultsContainer();
  showNameSearchResultsContainer();

  // get the search value and prep it for URL passage
  let searchValue = String(hdnNameSearch.innerText).trim().replaceAll(' ', "%20");

  // make the request
  fetch(currentUrl()+`/api/search-guest-list?s=${searchValue}`, {
    method: "GET",
    headers: {
      "X-Csrf-Token" : getCsrfToken(),
    }
  })
    .then((res) => {
      if (!res.ok) {throw Error(`${res.status} ${res.statusText}`);}
      return res.json();
    })
    .then((json) => {
      // Get the data and make the search results
      const results = json['data'];
      console.log(json['data']);
      fillNameSearchResults(results);
    })
    .catch((e) => {
      console.log(e);
    });
})

function showNameSearchResultsContainer() {
  nameSearchResultsContainer.classList.replace('hidden', 'flex');
  nameSearchResultsContainer.classList.replace('opacity-0', 'opacity-100');
}

function resetNameSearchResultsContainer() {
  // show that spinner
  ringSpinner.classList.remove('hidden');

  nameSearchResultsContainer.classList.replace('flex', 'hidden');
  nameSearchResultsContainer.classList.replace('opacity-100', 'opacity-0');

  // reset the name results container and delete the cards
  divNameResults.classList.replace('flex', 'hidden');
  document.querySelectorAll('div.rsvp-candidate-card')
    .forEach((node) => {
    node.remove();
  })

  // reset the message text
  txtNameSearchMessage.innerText = "";
}

function fillNameSearchResults(results) {
  // Set timeout to show off that cool ring animation :)
  setTimeout(() => {
    ringSpinner.classList.add('hidden');
    divNameResults.classList.replace('hidden', 'flex');

    // No results found.
    if (results.length === 0) {
      txtNameSearchMessage.innerHTML = "No invitations were found under that name. <em>Make sure enter your name just like it is on your invitation.</em>";
    }
    // Multiple results, have the user select who they are.
    else if (results.length > 1) {
      txtNameSearchMessage.innerHTML = "Please select your name from the list below.";
      buildNameResultCards(results);

      const cancelMessage = document.createElement('p');
      "text-xs text-gray-300 text-center w-full mt-16 mb-8".split(" ").map((s) => { cancelMessage.classList.add(s) });
      cancelMessage.innerHTML = "If you do not see your name on the list, <a href=\"/rsvp\" class='underline'>click here, or refresh the web page.</a> Double check you have entered your name <em>exactly</em> as it appears on your invitation.";
      divNameResults.append(cancelMessage);
    }
    // Just one user found. Pretty sure it's them.
    else {
      resetNameSearchResultsContainer();

      const r = results[0];
      popupConfirmRSVP(r["first_name"], r["last_name"], r["guest_id"], r["party_id"]);
    }
  }, 1500);
}

function buildNameResultCards(results)
{
  // Build the cards and add them to the card container
  results.forEach((r) => {
    const card = document.createElement('div');
    divNameResults.append(card);
    // add the class list to the card
    "rsvp-candidate-card flex items-center justify-start rounded-md bg-gray-700 p-4 w-full mt-8 cursor-pointer"
      .split(" ").map((str) => { card.classList.add(str) });

    // card click handler
    card.addEventListener('click', () => {
      popupConfirmRSVP(r["first_name"], r["last_name"], r["guest_id"], r["party_id"]);
    })

    // add the text to the card
    const text = document.createElement('p');
    text.innerText = `${r["first_name"]} ${r["last_name"]}`;
    text.classList.add('text-text', 'text-3xl', 'text-left');
    card.append(text);
  })
}

function popupConfirmRSVP(firstName, lastName, guestId, partyId)
{
  panelConfirmPopup.classList.replace('hidden', 'flex');
  const panel = document.querySelector('div.confirm-panel');

  // Welcome message
  const title = document.createElement('p');
  title.innerText = `Hi, ${firstName} ${lastName}!`;
  panel.append(title);
  "text-text text-3xl text-center mb-16"
    .split(" ").map((s) => { title.classList.add(s) })

  // Confirm as guest
  const textContent = document.createElement('p');
  "text-text text-center text-2xl w-full"
    .split(" ").map((s) => { textContent.classList.add(s) });
  textContent.innerHTML = "If this is you, please click the <b>Confirm</b> button below to confirm your RSVP.";
  panel.append(textContent);

  const confirmButton = document.createElement('a');
  confirmButton.innerText = "Confirm";
  panel.append(confirmButton);
  "px-8 py-2 bg-gray-700 text-text rounded-sm hover:shadow-xs shadow-gray-800 mb-16"
    .split(" ").map((s) => { confirmButton.classList.add(s) });
  confirmButton.setAttribute('href', `/rsvp/confirm?g=${guestId}&f=${firstName}&l=${lastName}&p=${partyId}`);

  // Cancel and retry
  const notYouText = document.createElement('p');
  "text-gray-300 text-center text-2xl italic".split(" ").map((s) => { notYouText.classList.add(s) });
  panel.append(notYouText);

  const cancelText = document.createElement('p');
  "text-text text-center text-lg w-full".split(" ").map((s) => { cancelText.classList.add(s) });
  cancelText.innerHTML = "No biggie, click the cancel button below or refresh the page and try again. <em>Double check you are entering your name <b>exactly</b> as it appears on your invitation.</em>"
  panel.append(cancelText);

  const cancelButton = document.createElement('a');
  cancelButton.innerText = "Cancel";
  panel.append(cancelButton);
  "px-8 py-2 bg-gray-300 text-bkg rounded-sm hover:shadow-xs shadow-gray-800".split(" ").map((s) => {
    cancelButton.classList.add(s);
  });
  cancelButton.addEventListener('click', () => window.location.reload());
}

function closeConfirmPopup()
{
  panelConfirmPopup.classList.replace('flex', 'hidden');
}
