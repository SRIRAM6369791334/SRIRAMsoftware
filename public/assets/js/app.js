(function () {
  const body = document.body;
  body.classList.add('page-enter');

  const role = body.dataset.role || 'admin';
  body.classList.add(role === 'client' ? 'role-simple' : 'role-dense');

  const progress = document.createElement('div');
  progress.className = 'progress-scroll';
  document.body.appendChild(progress);

  const cursor = document.createElement('div');
  cursor.className = 'cursor-light';
  document.body.appendChild(cursor);
  document.addEventListener('mousemove', (e) => {
    cursor.style.left = e.clientX + 'px';
    cursor.style.top = e.clientY + 'px';
  });

  const palette = document.createElement('div');
  palette.id = 'commandPalette';
  palette.className = 'command-palette d-none';
  palette.innerHTML = `<div class="command-palette-box"><input id="commandInput" class="form-control mb-2" placeholder="Type a command..." /><div id="commandResults"></div></div>`;
  document.body.appendChild(palette);

  const notifCenter = document.createElement('div');
  notifCenter.className = 'notification-center d-none';
  notifCenter.innerHTML = '<strong class="px-2 d-block mb-1">Notifications</strong><div id="notifList"></div>';
  document.body.appendChild(notifCenter);

  const toastStack = document.createElement('div');
  toastStack.className = 'toast-stack';
  document.body.appendChild(toastStack);

  const results = document.getElementById('commandResults');
  const input = document.getElementById('commandInput');

  const visualScore = () => {
    const cards = document.querySelectorAll('.card').length;
    const headings = document.querySelectorAll('h1,h2,h3').length;
    return Math.min(100, cards * 4 + headings * 6);
  };

  function toast(text, level = 'info', timeout = 2600) {
    const el = document.createElement('div');
    el.className = `toast-item ${level}`;
    el.textContent = text;
    toastStack.appendChild(el);
    setTimeout(() => el.remove(), timeout);
  }

  async function queryCommands(q = '') {
    const res = await fetch(`/api.php?resource=command-search&q=${encodeURIComponent(q)}`);
    const data = await res.json();
    results.innerHTML = (data.results || []).map((r) => `<a class="list-group-item list-group-item-action" href="${r.path}">${r.label}</a>`).join('') || '<div class="text-muted p-2">No command</div>';
  }

  function autoThemeByTime() {
    if (localStorage.getItem('ui_mode')) return;
    const h = new Date().getHours();
    if (h >= 19 || h < 6) body.dataset.mode = 'dark';
    else if (h >= 6 && h < 11) body.dataset.mode = 'light';
    else body.dataset.mode = 'glass';
  }

  function toneByContext() {
    const hour = new Date().getHours();
    body.dataset.tone = hour >= 17 ? 'evening' : 'morning';
  }

  function loadControls() {
    const c = document.getElementById('uiControls');
    if (!c) return;
    const get = (k, d) => localStorage.getItem(k) ?? d;
    c.querySelector('[name=accent]').value = get('accent', '#0d6efd');
    c.querySelector('[name=fontSize]').value = get('fontSize', '100');
    c.querySelector('[name=lineHeight]').value = get('lineHeight', '1.45');
    c.querySelector('[name=radius]').value = get('radius', '8');
    c.querySelector('[name=shadow]').value = get('shadow', '1');
    c.querySelector('[name=anim]').value = get('anim', '1');
    c.querySelector('[name=density]').value = get('density', '1');
    c.querySelector('[name=contrast]').checked = get('contrast', '0') === '1';
    c.querySelector('[name=reducedMotion]').checked = get('reducedMotion', '0') === '1';
    c.querySelector('[name=focusMode]').checked = get('focusMode', '0') === '1';
    applyControlState();

    c.addEventListener('input', (e) => {
      const t = e.target;
      localStorage.setItem(t.name, t.type === 'checkbox' ? (t.checked ? '1' : '0') : t.value);
      applyControlState();
    });
  }

  function applyControlState() {
    const root = document.documentElement;
    const g = (k, d) => localStorage.getItem(k) ?? d;
    root.style.setProperty('--primary', g('accent', '#0d6efd'));
    root.style.setProperty('font-size', `${g('fontSize', '100')}%`);
    root.style.setProperty('--line-height', g('lineHeight', '1.45'));
    root.style.setProperty('--radius-md', `${g('radius', '8')}px`);
    root.style.setProperty('--shadow-intensity', g('shadow', '1'));
    root.style.setProperty('--anim-speed', g('anim', '1'));
    root.style.setProperty('--card-density', g('density', '1'));
    body.dataset.contrast = g('contrast', '0') === '1' ? 'high' : 'normal';
    body.dataset.reducedMotion = g('reducedMotion', '0') === '1' ? 'on' : 'off';
    body.dataset.focus = g('focusMode', '0') === '1' ? 'on' : 'off';
  }

  document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
      e.preventDefault();
      palette.classList.toggle('d-none');
      input.focus();
      queryCommands('');
    }
    if (e.key === 'Escape') {
      palette.classList.add('d-none');
      notifCenter.classList.add('d-none');
    }
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'n') {
      e.preventDefault();
      notifCenter.classList.toggle('d-none');
    }
  });

  document.getElementById('commandPaletteBtn')?.addEventListener('click', () => {
    palette.classList.remove('d-none');
    input.focus();
    queryCommands('');
  });
  input?.addEventListener('input', () => queryCommands(input.value));

  const modeSelect = document.createElement('select');
  modeSelect.className = 'form-select form-select-sm ms-2';
  modeSelect.innerHTML = '<option value="light">Light</option><option value="dark">Dark</option><option value="glass">Glass</option>';
  modeSelect.value = localStorage.getItem('ui_mode') || 'light';
  document.querySelector('.navbar .container-fluid')?.appendChild(modeSelect);
  modeSelect.addEventListener('change', () => {
    body.dataset.mode = modeSelect.value;
    localStorage.setItem('ui_mode', modeSelect.value);
    toast(`Theme: ${modeSelect.value}`);
  });

  autoThemeByTime();
  toneByContext();
  if (!localStorage.getItem('ui_mode')) modeSelect.value = body.dataset.mode || 'light';

  const nav = document.querySelector('.navbar-nav');
  if (nav) {
    const links = Array.from(nav.querySelectorAll('a.nav-link'));
    links.sort((a, b) => Number(localStorage.getItem(`menu_hits_${b.getAttribute('href')}`) || '0') - Number(localStorage.getItem(`menu_hits_${a.getAttribute('href')}`) || '0'));
    links.forEach((l) => nav.appendChild(l));
    links.forEach((l) => l.addEventListener('click', () => {
      const k = `menu_hits_${l.getAttribute('href')}`;
      localStorage.setItem(k, String(Number(localStorage.getItem(k) || '0') + 1));
    }));
    links.forEach((l) => {
      const hits = Number(localStorage.getItem(`menu_hits_${l.getAttribute('href')}`) || '0');
      if (hits < 2 && role === 'client') l.classList.add('hidden-rare');
      if (hits > 10) l.classList.add('hover-spot');
    });
  }

  const main = document.querySelector('main.container');
  if (main) {
    const quick = document.createElement('div');
    quick.className = 'card mb-3';
    quick.innerHTML = '<strong>Your usual actions</strong><div class="small text-muted">Smart panel based on your behavior.</div>';
    main.prepend(quick);
  }

  document.querySelectorAll('.btn').forEach((btn) => {
    btn.classList.add('magnetic');
    btn.addEventListener('click', (e) => {
      const r = document.createElement('span');
      const rect = btn.getBoundingClientRect();
      r.style.cssText = `position:absolute;border-radius:50%;background:rgba(255,255,255,.5);width:10px;height:10px;left:${e.clientX-rect.left}px;top:${e.clientY-rect.top}px;transform:translate(-50%,-50%) scale(0);animation:rip .45s ease-out forwards`;
      btn.appendChild(r);
      setTimeout(() => r.remove(), 500);
    });
  });
  const style = document.createElement('style');
  style.textContent='@keyframes rip{to{transform:translate(-50%,-50%) scale(18);opacity:0}}';
  document.head.appendChild(style);

  document.querySelectorAll('.form-control').forEach((i) => {
    i.classList.add('input-focus-trail');
    i.addEventListener('input', () => {
      i.classList.remove('is-invalid');
      if (i.required && i.value.trim() === '') i.classList.add('is-invalid');
      if (i.name && /phone/i.test(i.name)) i.value = i.value.replace(/[^\d+]/g, '').slice(0, 15);
    });
  });

  document.querySelectorAll('[data-counter]').forEach((el) => {
    const target = Number(el.dataset.counter || '0');
    let n = 0;
    const tick = () => {
      n += Math.max(1, Math.ceil((target - n) / 10));
      el.textContent = String(Math.min(target, n));
      if (n < target) requestAnimationFrame(tick);
    };
    el.classList.add('kpi-breathe');
    tick();
  });

  window.addEventListener('scroll', () => {
    const doc = document.documentElement;
    const top = doc.scrollTop;
    const height = doc.scrollHeight - doc.clientHeight;
    progress.style.width = `${height > 0 ? (top / height) * 100 : 0}%`;
    document.querySelector('.navbar')?.classList.toggle('shrink', top > 30);
  }, { passive: true });

  if (navigator.connection && (navigator.connection.saveData || /2g/.test(navigator.connection.effectiveType || ''))) {
    body.classList.add('reduced-effects');
    toast('Lightweight mode enabled for low data connection', 'warn', 4200);
  }

  let idleTimer;
  const markActive = () => {
    clearTimeout(idleTimer);
    body.dataset.idle = 'off';
    idleTimer = setTimeout(() => {
      body.dataset.idle = 'on';
      toast('System calm mode active', 'info', 2200);
    }, 60000);
  };
  ['mousemove', 'keydown', 'click', 'scroll'].forEach((evt) => addEventListener(evt, markActive, { passive: true }));
  markActive();

  async function loadBehaviorHints() {
    const res = await fetch('/api.php?resource=behavior-snapshot&user_id=1');
    const data = await res.json();
    const hint = document.createElement('div');
    hint.className = 'jarvis-msg';
    hint.textContent = `Jarvis: ${data.persona || 'user'} • Inactivity ${Number(data.inactivity_decay_score || 0).toFixed(1)} • VisualScore ${visualScore()}`;
    document.body.appendChild(hint);
    setTimeout(() => hint.remove(), 6500);
  }

  async function hydrateNotifications() {
    const list = document.getElementById('notifList');
    if (!list) return;
    const sample = [
      { t: 'Approval pending', p: 'warn', unread: true },
      { t: 'Workflow simulated successfully', p: 'info', unread: false },
      { t: 'Critical SLA threshold reached', p: 'critical', unread: true },
    ];
    list.innerHTML = sample.map(n => `<div class="notif ${n.unread ? 'unread' : ''}"><span>${n.t}</span><small>${n.p}</small></div>`).join('');
  }

  document.getElementById('workflowSimForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.currentTarget;
    const out = document.getElementById('workflowSimResult');
    const btn = form.querySelector('button[type=submit]');
    const old = btn.textContent;
    btn.textContent = 'Thinking...';
    btn.disabled = true;
    const res = await fetch(`/api.php?resource=workflow-simulate&event=${encodeURIComponent(form.event.value)}&priority=${encodeURIComponent(form.priority.value)}`);
    const data = await res.json();
    if (out) out.textContent = JSON.stringify(data, null, 2);
    btn.textContent = '✓ Simulated';
    setTimeout(() => { btn.textContent = old; btn.disabled = false; }, 1300);
  });

  loadControls();
  hydrateNotifications();
  setTimeout(loadBehaviorHints, 1100);
})();
