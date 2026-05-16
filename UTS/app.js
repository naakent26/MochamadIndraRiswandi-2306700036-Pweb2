// ============================================================
// KONFIGURASI API
// ============================================================
const API_KEY = '463b01818c5fadfdd11523233adb475b';
const BASE_URL = 'https://api.themoviedb.org/3';
const IMG_BASE = 'https://image.tmdb.org/t/p/w500';
const IMG_ORIGINAL = 'https://image.tmdb.org/t/p/original';

// ============================================================
// STATE APLIKASI
// ============================================================
let currentCategory = 'now_playing';
let currentQuery = '';
let isSearchMode = false;

// ============================================================
// REFERENSI ELEMEN DOM
// ============================================================
const grid        = document.getElementById('grid');
const loading     = document.getElementById('loading');
const errorBox    = document.getElementById('errorBox');
const errorMsg    = document.getElementById('errorMsg');
const statusBar   = document.getElementById('statusBar');
const statusText  = document.getElementById('statusText');
const emptyState  = document.getElementById('emptyState');
const searchInput = document.getElementById('searchInput');
const searchBtn   = document.getElementById('searchBtn');
const clearBtn    = document.getElementById('clearBtn');
const modal       = document.getElementById('modal');
const modalContent= document.getElementById('modalContent');

// ============================================================
// UTILITY: TAMPILKAN / SEMBUNYIKAN ELEMEN
// ============================================================
function showEl(el)  { el.classList.remove('hidden'); }
function hideEl(el)  { el.classList.add('hidden'); }

function setLoading(active) {
  if (active) {
    showEl(loading);
    hideEl(grid);
    hideEl(errorBox);
    hideEl(emptyState);
    hideEl(statusBar);
  } else {
    hideEl(loading);
  }
}

// ============================================================
// FETCH FILM BERDASARKAN KATEGORI
// ============================================================
async function fetchMovies() {
  setLoading(true);

  const url = `${BASE_URL}/movie/${currentCategory}?api_key=${API_KEY}&language=id-ID&page=1`;

  try {
    const response = await fetch(url);

    // Cek apakah response sukses
    if (!response.ok) {
      throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
    }

    const data = await response.json();
    const movies = data.results;

    if (!movies || movies.length === 0) {
      showEmptyState();
      return;
    }

    renderMovies(movies);
    showStatus(`Menampilkan ${movies.length} film`);

  } catch (err) {
    showError(err.message);
  } finally {
    setLoading(false);
  }
}

// ============================================================
// SEARCH FILM
// ============================================================
async function searchMovies(query) {
  if (!query.trim()) return;

  setLoading(true);
  isSearchMode = true;
  clearBtn.classList.add('visible');

  const url = `${BASE_URL}/search/movie?api_key=${API_KEY}&language=id-ID&query=${encodeURIComponent(query)}&page=1`;

  try {
    const response = await fetch(url);

    if (!response.ok) {
      throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
    }

    const data = await response.json();
    const movies = data.results;

    if (!movies || movies.length === 0) {
      showEmptyState();
      showStatus(`Tidak ada hasil untuk "${query}"`);
      return;
    }

    renderMovies(movies);
    showStatus(`Ditemukan ${data.total_results} film untuk "${query}"`);

  } catch (err) {
    showError(err.message);
  } finally {
    setLoading(false);
  }
}

// ============================================================
// RENDER KARTU FILM KE GRID
// ============================================================
function renderMovies(movies) {
  hideEl(errorBox);
  hideEl(emptyState);
  showEl(grid);

  grid.innerHTML = '';

  // Looping pada array of objects dari API
  movies.forEach((movie, index) => {
    const card = createMovieCard(movie, index);
    grid.appendChild(card);
  });
}

// ============================================================
// BUAT ELEMEN KARTU FILM
// ============================================================
function createMovieCard(movie, index) {
  const card = document.createElement('div');
  card.className = 'movie-card';
  card.style.animationDelay = `${index * 0.04}s`;

  const rating = movie.vote_average ? movie.vote_average.toFixed(1) : 'N/A';
  const year   = movie.release_date ? movie.release_date.slice(0, 4) : '—';

  const posterHTML = movie.poster_path
    ? `<img class="card-poster" src="${IMG_BASE}${movie.poster_path}" alt="${movie.title}" loading="lazy">`
    : `<div class="card-no-poster">🎬</div>`;

  card.innerHTML = `
    <div class="card-badge">${year}</div>
    ${posterHTML}
    <div class="card-body">
      <div class="card-title">${movie.title}</div>
      <div class="card-meta">
        <span class="card-rating">⭐ ${rating}</span>
        <span>${movie.original_language?.toUpperCase() || ''}</span>
      </div>
    </div>
  `;

  // Event listener untuk buka modal detail
  card.addEventListener('click', () => openModal(movie.id));

  return card;
}

// ============================================================
// BUKA MODAL DETAIL FILM
// ============================================================
async function openModal(movieId) {
  showEl(modal);
  modalContent.innerHTML = `<div style="padding:60px;text-align:center;color:#888">Memuat detail...</div>`;

  const url = `${BASE_URL}/movie/${movieId}?api_key=${API_KEY}&language=id-ID`;

  try {
    const response = await fetch(url);

    if (!response.ok) throw new Error('Gagal memuat detail film');

    const movie = await response.json();

    const rating   = movie.vote_average ? movie.vote_average.toFixed(1) : 'N/A';
    const year     = movie.release_date ? movie.release_date.slice(0, 4) : '—';
    const runtime  = movie.runtime ? `${movie.runtime} menit` : '—';
    const genres   = movie.genres?.map(g => g.name).join(', ') || '—';
    const votes    = movie.vote_count?.toLocaleString('id-ID') || '0';

    const posterHTML = movie.poster_path
      ? `<img class="modal-poster" src="${IMG_BASE}${movie.poster_path}" alt="${movie.title}">`
      : `<div class="modal-no-poster">🎬</div>`;

    modalContent.innerHTML = `
      <div class="modal-hero">
        ${posterHTML}
        <div class="modal-info">
          <div class="modal-title">${movie.title}</div>
          ${movie.tagline ? `<div class="modal-tagline">"${movie.tagline}"</div>` : ''}
          <div class="modal-stats">
            <div class="modal-stat">⭐ <strong>${rating}</strong> / 10</div>
            <div class="modal-stat">🗳️ <strong>${votes}</strong> suara</div>
            <div class="modal-stat">📅 <strong>${year}</strong></div>
            <div class="modal-stat">⏱️ <strong>${runtime}</strong></div>
          </div>
          <div class="modal-stat" style="margin-bottom:12px">🎭 ${genres}</div>
          ${movie.original_title !== movie.title
            ? `<div class="modal-stat">🌐 Judul Asli: <strong>${movie.original_title}</strong></div>`
            : ''}
        </div>
      </div>
      <div class="modal-overview">
        <h4>Sinopsis</h4>
        <p>${movie.overview || 'Sinopsis tidak tersedia.'}</p>
      </div>
    `;

  } catch (err) {
    modalContent.innerHTML = `
      <div style="padding:40px;text-align:center;color:#ff8888">
        ⚠️ Gagal memuat detail: ${err.message}
      </div>
    `;
  }
}

// ============================================================
// TUTUP MODAL
// ============================================================
function closeModal() {
  hideEl(modal);
  modalContent.innerHTML = '';
}

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closeModal();
});

// ============================================================
// TAMPILKAN STATUS BAR
// ============================================================
function showStatus(text) {
  statusText.textContent = text;
  showEl(statusBar);
}

// ============================================================
// TAMPILKAN ERROR
// ============================================================
function showError(message) {
  errorMsg.textContent = message || 'Terjadi kesalahan tak terduga.';
  showEl(errorBox);
  hideEl(grid);
  hideEl(emptyState);
}

// ============================================================
// TAMPILKAN EMPTY STATE
// ============================================================
function showEmptyState() {
  showEl(emptyState);
  hideEl(grid);
}

// ============================================================
// RESET PENCARIAN - KEMBALI KE MODE KATEGORI
// ============================================================
function resetSearch() {
  isSearchMode = false;
  currentQuery = '';
  searchInput.value = '';
  clearBtn.classList.remove('visible');
  fetchMovies();
}

// ============================================================
// EVENT LISTENERS
// ============================================================

// Klik tab kategori
document.querySelectorAll('.tab').forEach(tab => {
  tab.addEventListener('click', () => {
    // Update tab aktif
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');

    // Update kategori & reset search mode
    currentCategory = tab.dataset.category;
    isSearchMode = false;
    currentQuery = '';
    searchInput.value = '';
    clearBtn.classList.remove('visible');

    fetchMovies();
  });
});

// Klik tombol cari
searchBtn.addEventListener('click', () => {
  const query = searchInput.value.trim();
  if (query) {
    currentQuery = query;
    searchMovies(query);
  }
});

// Enter di input search
searchInput.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') {
    const query = searchInput.value.trim();
    if (query) {
      currentQuery = query;
      searchMovies(query);
    }
  }
});

// Tombol clear
clearBtn.addEventListener('click', resetSearch);

// ============================================================
// INISIALISASI — LOAD DATA SAAT HALAMAN DIBUKA
// ============================================================
fetchMovies();