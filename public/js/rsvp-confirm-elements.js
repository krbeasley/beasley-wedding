
const formPanel = () => {
  const e = document.createElement('div');
  "w-11/12 max-w-180 flex flex-col justify-start items-start gap-y-8"
    .split(" ").map(s => {p.classList.add(s)});
  return e;
}

const panelTitle = (content, isHtml = false) => {
  const p = document.createElement('p');
  isHtml ? p.innerHTML = content : p.innerText = content;
  return p;
}


