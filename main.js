
// Reveal on scroll. Falls back to visible when IntersectionObserver is missing,
// so cards never stay permanently invisible (CSS also gates hiding on html.js).
function initReveal(){
  const els = document.querySelectorAll('.reveal');
  if(!('IntersectionObserver' in window)){
    els.forEach(el=>el.classList.add('in'));
    return;
  }
  const io = new IntersectionObserver((ents)=>{
    ents.forEach(e=>{
      if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); }
    });
  },{threshold:0.12});
  els.forEach(el=>io.observe(el));
}

// Filtering, Sorting + result count / empty state (client-side, catalog page only)
function applyFilters(){
  const q = (document.getElementById('q')?.value || '').trim().toLowerCase();
  const type = document.getElementById('type')?.value || '';
  const sort = document.getElementById('sort')?.value || 'name';
  const favOnly = document.getElementById('favonly')?.checked || false;
  const grid = document.getElementById('grid');
  if(!grid) return;
  const favs = favOnly ? getFav() : null;
  const cards = Array.from(grid.querySelectorAll('.card'));
  cards.forEach(card => {
    const name = card.dataset.name?.toLowerCase() || '';
    const t = card.dataset.type || '';
    const matchQ = !q || name.includes(q);
    const matchT = !type || t === type;
    const matchF = !favOnly || (favs !== null && favs.includes(+card.dataset.id));
    card.style.display = (matchQ && matchT && matchF) ? '' : 'none';
  });
  const visible = cards.filter(c=>c.style.display !== 'none');
  visible.sort((a,b)=>{
    if (sort === 'price_up') return (+a.dataset.price) - (+b.dataset.price);
    if (sort === 'price_down') return (+b.dataset.price) - (+a.dataset.price);
    return (a.dataset.name || '').localeCompare(b.dataset.name || '', 'en');
  });
  visible.forEach(c=>grid.appendChild(c));
  const count = document.getElementById('count');
  if(count) count.textContent = 'عرض ' + visible.length + ' من ' + cards.length;
  const empty = document.getElementById('empty');
  if(empty) empty.hidden = visible.length > 0;
}

function resetFilters(){
  const q = document.getElementById('q'); if(q) q.value = '';
  const type = document.getElementById('type'); if(type) type.value = '';
  const sort = document.getElementById('sort'); if(sort) sort.value = 'name';
  const favOnly = document.getElementById('favonly'); if(favOnly) favOnly.checked = false;
  applyFilters();
}

// Favorites (stable database ids, stored in localStorage)
function getFav(){
  try{
    const v = JSON.parse(localStorage.getItem('favCars')||'[]');
    return Array.isArray(v) ? v.filter(n=>typeof n === 'number' && Number.isInteger(n)) : [];
  }catch(e){ return []; }
}
function setFav(arr){
  try{ localStorage.setItem('favCars', JSON.stringify(arr)); }catch(e){ /* storage full/blocked: keep in-memory only */ }
}
function normId(id){
  const n = Number(id);
  return Number.isInteger(n) ? n : null;
}
function isFav(id){ return getFav().includes(id); }

function paintFavButton(btn, on){
  btn.classList.toggle('active', on);
  btn.setAttribute('aria-pressed', on ? 'true' : 'false');
  if(btn.id === 'detailFav'){
    btn.textContent = on ? btn.dataset.remove : btn.dataset.add;
  }
}

function toggleFav(ev, id){
  id = normId(id);
  if(id === null) return;
  const arr = getFav();
  const i = arr.indexOf(id);
  if(i === -1) arr.push(id); else arr.splice(i,1);
  setFav(arr);
  const on = arr.includes(id);
  document.querySelectorAll('[data-id="' + id + '"]').forEach(btn=>paintFavButton(btn, on));
  syncDetailFav();
}

function syncDetailFav(){
  const btn = document.getElementById('detailFav');
  if(!btn) return;
  paintFavButton(btn, isFav(+btn.getAttribute('data-id')));
}

window.addEventListener('DOMContentLoaded', ()=>{
  initReveal();
  document.querySelectorAll('.fav').forEach(btn=>{
    paintFavButton(btn, isFav(+btn.getAttribute('data-id')));
  });
  syncDetailFav();
  applyFilters();
});
