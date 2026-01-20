
const confirmAttendButton = document.getElementById('confirm-attend-button');
const declineAttendButton = document.getElementById('decline-attend-button');
const welcomeSection = document.getElementById('welcome-section');
const confirmSection = document.getElementById('confirm-section');
const declineSection = document.getElementById('decline-section');
const plusOneSection = document.getElementById('plus-one-section');
const submitSection = document.getElementById('submit-section');

let lt;
window.addEventListener('DOMContentLoaded', () => {
  lt = Date.now();
  window.scrollTo({
    top: 0,
    behavior: "smooth"
  });
  document.getElementById('gid').value = new URL(window.location.href).searchParams.get('g');
  document.getElementById('lastname').value = "";
  document.getElementById('plus-one-input').value = "";
  document.getElementById('firstname').value = "";
})

function toggleFormSection(section) {
  const isHidden = section.ariaHidden === "true";

  if (isHidden) {
    section.ariaHidden = "false";
    section.classList.remove('hidden');
    section.classList.replace("opacity-0", "opacity-100");
  } else {
    section.ariaHidden = "true";
    section.classList.replace("opacity-0", "opacity-100");
    setTimeout(() => {section.classList.add('hidden')}, 200);
  }
}

confirmAttendButton.addEventListener('click', () => {
  toggleFormSection(confirmSection);
  confirmAttendButton.disabled = true;
  declineAttendButton.disabled = true;
  let scrollPos = confirmSection.getBoundingClientRect().top;

  if (plusOneSection) {
    toggleFormSection(plusOneSection);
    scrollPos = confirmSection.getBoundingClientRect().bottom - 200;
    document.getElementById('plus-one-input').removeAttribute('inert');
  }

  window.scrollTo({
    top: scrollPos,
    behavior: "smooth"
  });

  setTimeout(() => {
    toggleFormSection(submitSection);
  }, 1500);
});
declineAttendButton.addEventListener('click', () => {
  toggleFormSection(declineSection);
  confirmAttendButton.disabled = true;
  declineAttendButton.disabled = true;

  window.scrollTo({
    top: declineSection.getBoundingClientRect().top,
    behavior: "smooth",
  });

  setTimeout(() => {
    toggleFormSection(submitSection);
  }, 1500);
});

if (plusOneSection) {
  const input = document.getElementById('plus-one-input');
  const text = document.getElementById('plus-one-display')
  input.addEventListener('input', () => {
    if (String(input.value).length > 0) {
      text.innerText = `& ${input.value}`;
      document.getElementById('firstname').value = String(input.value).split(" ")[0].trim();
      document.getElementById('lastname').value = String(input.value).split(" ")[1].trim();
    }
    else {
      text.innerText = "";
      document.getElementById('firstname').value = String(input.value).split(" ")[0].trim();
      document.getElementById('lastname').value = String(input.value).split(" ")[1].trim();
    }
  })
}

document.getElementById('sb').addEventListener('click', () => {
  const et= Date.now();
  if ( et-lt < 3000 ) { document.getElementById('sb').disabled = true;  window.location.reload(); }
  document.getElementById('frm').submit();
})
