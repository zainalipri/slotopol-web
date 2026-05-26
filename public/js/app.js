// === State ===
let state = {
  token: null,
  user: null,
  uid: null,
  gid: null,
  games: [],
  currentGame: null,
  currentProvider: 'all',
  bet: 1,
  lines: 5,
  spinning: false,
};

// Symbol emoji mapping (standard Novomatic-style)
const SYMBOLS = {
  1: '⭐', 2: '💎', 3: '🍒', 4: '🍋', 5: '🍊',
  6: '🍇', 7: '🔔', 8: '🍀', 9: '👑', 10: '💟',
  11: '🔥', 12: '💫', 13: '🎯', 14: '🌈', 15: '🦁',
};

// Provider icons
const PROVIDER_ICONS = {
  novomatic: '🎰', netent: '🎲', ct: '🕹️', agt: '🎯',
  igt: '👑', playtech: '🎮', aristocrat: '💎', betsoft: '🦁',
  megajack: '💰', playngo: '🎪', default: '🎰'
};

// Provider colors
const PROVIDER_COLORS = {
  novomatic: '#ff6b35', netent: '#00c853', ct: '#2979ff',
  agt: '#d500f9', igt: '#ffd700', playtech: '#00bcd4',
  aristocrat: '#ff4081', betsoft: '#ffab00', megajack: '#76ff03',
  playngo: '#e040fb', default: '#8888aa'
};

// === API ===
async function api(method, path, body = null) {
  const headers = { 'Content-Type': 'application/json' };
  if (state.token) headers['Authorization'] = `Bearer ${state.token}`;
  
  const opts = { method, headers };
  if (body) opts.body = JSON.stringify(body);
  
  const res = await fetch(`/api${path}`, opts);
  const data = await res.json();
  
  if (!res.ok) throw new Error(data.what || `HTTP ${res.status}`);
  return data;
}

// === Auth ===
async function login() {
  const email = document.getElementById('login-email').value.trim();
  const secret = document.getElementById('login-password').value;
  const btn = document.getElementById('login-btn');
  const errorEl = document.getElementById('login-error');
  
  btn.disabled = true;
  btn.querySelector('.btn-text').style.display = 'none';
  btn.querySelector('.btn-loader').style.display = 'inline';
  errorEl.textContent = '';
  
  try {
    const data = await api('POST', '/signin', { email, secret });
    state.token = data.access;
    state.uid = data.uid;
    state.user = email;
    localStorage.setItem('slotopol_token', data.access);
    localStorage.setItem('slotopol_uid', data.uid);
    localStorage.setItem('slotopol_user', email);
    enterApp();
  } catch (e) {
    errorEl.textContent = e.message || 'Login failed. Check credentials.';
  } finally {
    btn.disabled = false;
    btn.querySelector('.btn-text').style.display = 'inline';
    btn.querySelector('.btn-loader').style.display = 'none';
  }
}

function logout() {
  state.token = null;
  state.user = null;
  state.uid = null;
  state.gid = null;
  localStorage.removeItem('slotopol_token');
  localStorage.removeItem('slotopol_uid');
  localStorage.removeItem('slotopol_user');
  showScreen('login');
}

// === Screen Management ===
function showScreen(name) {
  document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
  document.getElementById(`${name}-screen`).classList.add('active');
}

function showView(name) {
  document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
  document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
  document.getElementById(`view-${name}`).classList.add('active');
  document.getElementById(`nav-${name}`).classList.add('active');
  if (name === 'slot' && !state.gid) {
    // Show slot view with current game
  }
}

// === App Entry ===
async function enterApp() {
  showScreen('app');
  document.getElementById('user-email').textContent = state.user;
  await loadGames();
}

// === Game Browser ===
async function loadGames() {
  try {
    const data = await api('GET', '/game/algs');
    state.games = data;
    renderProviderFilter(data);
    renderProviderTabs(data);
    renderGames(data);
  } catch (e) {
    document.getElementById('games-grid').innerHTML = 
      `<div class="loading">Error loading games: ${e.message}</div>`;
  }
}

function renderProviderFilter(games) {
  const providers = [...new Set(games.flatMap(g => 
    g.aliases.map(a => a.prov)
  ))].sort();
  
  const select = document.getElementById('provider-filter');
  select.innerHTML = '<option value="all">All Providers</option>' +
    providers.map(p => `<option value="${p}">${p} (${games.filter(g => g.aliases.some(a => a.prov === p)).length})</option>`).join('');
}

function renderProviderTabs(games) {
  const providers = [...new Set(games.flatMap(g => 
    g.aliases.map(a => a.prov)
  ))].sort();
  
  const container = document.getElementById('provider-tabs');
  container.innerHTML = '<button class="prov-tab active" onclick="filterByProvider(\'all\')" data-provider="all">All</button>' +
    providers.map(p => {
      const count = games.filter(g => g.aliases.some(a => a.prov === p)).length;
      // Escape single quotes in provider name for onclick safety
      const safeProv = p.replace(/'/g, "\\'");
      return `<button class="prov-tab" onclick="filterByProvider('${safeProv}')" data-provider="${p.toLowerCase()}" style="--tab-color: ${PROVIDER_COLORS[p] || '#8888aa'}">
        ${PROVIDER_ICONS[p] || '🎰'} ${p} <span class="prov-count">${count}</span>
      </button>`;
    }).join('');
}

function renderGames(games) {
  const grid = document.getElementById('games-grid');
  const allGameInfos = [];
  
  games.forEach(alg => {
    alg.aliases.forEach(alias => {
      allGameInfos.push({
        prov: alias.prov,
        name: alias.name,
        sx: alg.sx,
        sy: alg.sy,
        ln: alg.ln,
        wn: alg.wn,
        rtp: alg.rtp,
        rtps: alg.rtp ? `${Math.min(...alg.rtp).toFixed(1)}-${Math.max(...alg.rtp).toFixed(1)}%` : 'N/A',
        rtpCount: alg.rtp ? alg.rtp.length : 0,
        grid: alg.sx && alg.sy ? `${alg.sx}x${alg.sy}` : '',
      });
    });
  });
  
  if (allGameInfos.length === 0) {
    grid.innerHTML = '<div class="loading">No games found</div>';
    return;
  }
  
  // Use data attributes instead of inline onclick to avoid issues with special chars in names
  grid.innerHTML = allGameInfos.map(gi => `
    <div class="game-card" data-provider="${gi.prov.replace(/"/g, '&quot;')}" data-name="${gi.name.replace(/"/g, '&quot;')}"
         style="border-top-color: ${PROVIDER_COLORS[gi.prov.toLowerCase()] || '#8888aa'}">
      <div class="game-icon">${PROVIDER_ICONS[gi.prov.toLowerCase()] || '🎰'}</div>
      <div class="game-name">${gi.name}</div>
      <div class="game-prov">${gi.prov}</div>
      <div class="game-meta">
        ${gi.grid ? `<span>${gi.grid}</span>` : ''}
        ${gi.ln ? `<span>${gi.ln} lines</span>` : ''}
        ${gi.wn ? `<span>${gi.wn} ways</span>` : ''}
      </div>
    </div>
  `).join('');
}

function filterByProvider(provider) {
  state.currentProvider = provider.toLowerCase();
  document.querySelectorAll('.prov-tab').forEach(t => {
    t.classList.toggle('active', t.dataset.provider === state.currentProvider);
  });
  // Sync the dropdown to show "All" when using tabs
  document.getElementById('provider-filter').value = 'all';
  filterGames();
}

function filterGames() {
  const search = document.getElementById('game-search').value.toLowerCase().trim();
  // Use tab as primary filter, fall back to dropdown
  const provider = state.currentProvider !== 'all' 
    ? state.currentProvider 
    : document.getElementById('provider-filter').value;
  
  const filtered = state.games.filter(alg => {
    return alg.aliases.some(alias => {
      const matchProv = provider === 'all' || alias.prov.toLowerCase() === provider.toLowerCase();
      const matchSearch = !search || alias.name.toLowerCase().includes(search);
      return matchProv && matchSearch;
    });
  });
  
  renderGames(filtered);
}

// === Slot Machine ===
async function selectGame(prov, name) {
  showView('slot');
  
  // Find the full alias info
  const alg = state.games.find(g => g.aliases.some(a => 
    a.prov.toLowerCase() === prov.toLowerCase() && a.name.toLowerCase() === name.toLowerCase()
  ));
  const alias = alg?.aliases.find(a => 
    a.prov.toLowerCase() === prov.toLowerCase() && a.name.toLowerCase() === name.toLowerCase()
  );
  
  if (!alias) {
    document.getElementById('slot-game-name').textContent = 'Game not found';
    return;
  }
  
  state.currentGame = { prov, name, alg, alias };
  document.getElementById('slot-game-name').textContent = name;
  document.getElementById('slot-provider').textContent = prov;
  
  // Update RTP display
  if (alg?.rtp?.length) {
    const midRtp = alg.rtp[Math.floor(alg.rtp.length / 2)];
    document.getElementById('rtp-value').textContent = midRtp.toFixed(1) + '%';
  }
  
  // Update lines
  if (alg?.ln) {
    document.getElementById('lines-value').textContent = alg.ln;
    state.lines = alg.ln;
  }
  
  // Reset bet
  state.bet = 1;
  document.getElementById('bet-value').textContent = state.bet.toFixed(2);
  
  // Create game session
  await createGame();
}

async function createGame() {
  if (!state.currentGame) return;
  
    // Match Go's util.ToID(): lowercase, keep only [a-z0-9_/], strip everything else
  const sanitize = s => s.toLowerCase().replace(/[^a-z0-9_/]/g, '');
  const aliasId = `${sanitize(state.currentGame.prov)}/${sanitize(state.currentGame.name)}`;
  
  try {
    const data = await api('POST', '/game/new', {
      cid: 1,
      uid: state.uid,
      alias: aliasId,
    });
    
    state.gid = data.gid;
    updateWallet(data.wallet);
    
    // Render initial reel state from grid (array of columns)
    if (data.game && data.game.grid) {
      renderReels(data.game.grid);
    }
    
  } catch (e) {
    console.error('Create game error:', e);
    document.getElementById('slot-game-name').textContent = `Error: ${e.message}`;
  }
}

function renderReels(grid) {
  const container = document.getElementById('reel-container');
  
  if (!grid || !Array.isArray(grid) || grid.length === 0) {
    // Placeholder with 3 reels x 3 rows
    container.innerHTML = Array.from({ length: 3 }, (_, ri) => `
      <div class="reel" id="reel-${ri}">
        ${Array.from({ length: 3 }, () => {
          const sym = Math.floor(Math.random() * 9) + 1;
          return `<div class="symbol" data-sym="${sym}">${SYMBOLS[sym] || '❓'}</div>`;
        }).join('')}
      </div>
    `).join('');
    return;
  }
  
  // Grid is array of columns: [col1, col2, col3] where each col is [row1, row2, row3]
  container.innerHTML = grid.map((col, ri) => `
    <div class="reel" id="reel-${ri}">
      ${col.map(sym => `
        <div class="symbol" data-sym="${sym}">${SYMBOLS[sym] || '❓'}</div>
      `).join('')}
    </div>
  `).join('');
}

function adjustBet(delta) {
  const betValues = [0.25, 0.50, 1.00, 2.00, 5.00, 10.00, 25.00];
  let idx = betValues.indexOf(state.bet);
  if (idx === -1) idx = 2;
  idx = Math.max(0, Math.min(betValues.length - 1, idx + delta));
  state.bet = betValues[idx];
  document.getElementById('bet-value').textContent = state.bet.toFixed(2);
}

async function spin() {
  if (state.spinning || !state.gid) return;
  state.spinning = true;
  
  const btn = document.getElementById('spin-btn');
  btn.disabled = true;
  btn.querySelector('.spin-text').style.display = 'none';
  btn.querySelector('.spin-loader').style.display = 'inline';
  
  const winDisplay = document.getElementById('win-display');
  winDisplay.style.display = 'none';
  
  // Start reel animation
  document.querySelectorAll('.reel').forEach(r => r.classList.add('spinning'));
  
  try {
    const data = await api('POST', '/slot/spin', {
      gid: state.gid,
      bet: state.bet,
    });
    
    // Stop spinning after a short delay
    await new Promise(r => setTimeout(r, 600));
    document.querySelectorAll('.reel').forEach(r => r.classList.remove('spinning'));
    
    // Update reels
    if (data.game && data.game.grid) {
      renderReels(data.game.grid);
    }
    
    // Update wallet
    updateWallet(data.wallet);
    
    // Show wins - the wins field contains winning combinations
    let totalGain = 0;
    if (data.wins) {
      if (Array.isArray(data.wins)) {
        totalGain = data.wins.reduce((s, w) => s + (w.gain || 0), 0);
      } else if (typeof data.wins === 'object') {
        // Single win object with gain field
        totalGain = data.wins.gain || 0;
      }
    }
    
    if (totalGain > 0) {
      winDisplay.style.display = 'block';
      document.getElementById('win-amount').textContent = `+${totalGain.toFixed(2)}`;
      
      const details = [];
      if (Array.isArray(data.wins)) {
        data.wins.filter(w => w.gain > 0).forEach(w => {
          details.push(`${w.gain.toFixed(2)}`);
        });
      }
      document.getElementById('win-detail').textContent = details.length ? details.join(', ') : 'You won!';
    }
    
  } catch (e) {
    document.querySelectorAll('.reel').forEach(r => r.classList.remove('spinning'));
    console.error('Spin error:', e);
    
    if (e.message.includes('no money') || e.message.includes('nomoney')) {
      // Try to create a new game
      document.getElementById('win-display').style.display = 'block';
      document.getElementById('win-amount').textContent = 'No funds';
      document.getElementById('win-detail').textContent = 'Create a new game session to continue';
      await createGame();
    }
  } finally {
    state.spinning = false;
    btn.disabled = false;
    btn.querySelector('.spin-text').style.display = 'inline';
    btn.querySelector('.spin-loader').style.display = 'none';
  }
}

function updateWallet(amount) {
  const el = document.getElementById('wallet-value');
  if (amount !== undefined && amount !== null) {
    el.textContent = Number(amount).toFixed(2);
  }
}

// === Init ===
document.addEventListener('DOMContentLoaded', () => {
  // Check for saved session
  const token = localStorage.getItem('slotopol_token');
  const uid = localStorage.getItem('slotopol_uid');
  const user = localStorage.getItem('slotopol_user');
  
  if (token && uid && user) {
    state.token = token;
    state.uid = parseInt(uid);
    state.user = user;
    enterApp();
  }
});

// Provider filter select change handler - also syncs tabs
function onDropdownChange() {
  state.currentProvider = document.getElementById('provider-filter').value.toLowerCase();
  document.querySelectorAll('.prov-tab').forEach(t => {
    t.classList.toggle('active', t.dataset.provider === state.currentProvider);
  });
  filterGames();
}

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('provider-filter').addEventListener('change', onDropdownChange);
});

// Event delegation for game card clicks (avoids inline onclick issues with special chars)
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('games-grid').addEventListener('click', (e) => {
    const card = e.target.closest('.game-card');
    if (card) {
      selectGame(card.dataset.provider, card.dataset.name);
    }
  });
});

// Enter key on login
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('login-password').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') login();
  });
  document.getElementById('login-email').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') document.getElementById('login-password').focus();
  });
});
