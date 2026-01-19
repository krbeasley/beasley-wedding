// registry.js
// Handles the behavior of the registry page

let registryData;
const apiBaseURI = `${window.location.protocol}//${window.location.hostname}${(window.location.port) ? `:${window.location.port}` : ""}/api`;

window.addEventListener('load', async (e) => {
  // Load the registry data for interaction
  registryData = await loadRegistryData();
})

async function loadRegistryData() {
  return new Promise((resolve) => {
    // Grab the list of items from the
    fetch(`${apiBaseURI}/registry`)
      .then((res) => {
        if (!res.ok) {
          throw new Error(`Error: ${res.statusText}`);
        }
        return res.json();
      })
      .then(data => resolve(data["Data"]))
      .catch(error => {
        console.error(error);
        resolve(null);
      })
  });
}
