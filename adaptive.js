// assets/js/adaptive.js - helpery (możesz rozszerzyć)
async function getRecommendations() {
  const res = await fetch('/api/get_recommendations.php');
  if (!res.ok) throw new Error('Błąd pobierania');
  return await res.json();
}

// Example render
function renderRecommendations(listEl, items) {
  listEl.innerHTML = '';
  items.forEach(it => {
    const li = document.createElement('div');
    li.className = 'card';
    li.innerHTML = `<h4>${it.title}</h4><div>${it.type}</div>`;
    listEl.appendChild(li);
  });
}