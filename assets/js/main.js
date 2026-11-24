/* =====================================================================
   LA PUBLICIDAD — JavaScript del tema (sin librerías)
   Módulos: menú móvil, carrusel 3D, cotizador, descuento web, contacto.
   ===================================================================== */
(function () {
  'use strict';

  var LPD = window.LP || { precios: {}, whatsapp: '', ajax: '', nonce: '', vigencia: '' };

  function $(s, r) { return (r || document).querySelector(s); }
  function $$(s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); }
  function money(n) { return '$' + String(Math.round(n)).replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
  function el(tag, cls, text) {
    var e = document.createElement(tag);
    if (cls) { e.className = cls; }
    if (text !== undefined) { e.textContent = text; }
    return e;
  }
  function waUrl(text) { return 'https://wa.me/' + LPD.whatsapp + '?text=' + encodeURIComponent(text); }
  function cleanWa(v) {
    var d = String(v || '').replace(/\D/g, '');
    if (d.length === 12 && d.indexOf('57') === 0) { d = d.slice(2); }
    return /^3\d{9}$/.test(d) ? d : '';
  }
  function store(k, v) { try { if (v === undefined) { return window.localStorage.getItem(k); } window.localStorage.setItem(k, v); } catch (e) { return null; } }

  function post(action, data) {
    var fd = new FormData();
    fd.append('action', action);
    fd.append('nonce', LPD.nonce);
    Object.keys(data).forEach(function (k) {
      var v = data[k];
      if (Array.isArray(v)) { v.forEach(function (x) { fd.append(k + '[]', x); }); } else { fd.append(k, v); }
    });
    return fetch(LPD.ajax, { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) { return r.json(); });
  }

  /* ---------- Cálculo (mismo orden de operaciones que lp_calcular en PHP) ---------- */
  function calc(p, s) {
    var cant = Math.max(1, Math.min(1000, parseInt(s.cantidad, 10) || 1));
    if (p.tipo === 'paquete') {
      var t = Math.round(p.precio * cant);
      return { ok: true, producto: p.nombre, medidas: p.descripcion || '', material: '', diseno: false, area: 0, cantidad: cant,
        impresion: t, minimoAplicado: false, pct: 0, descVol: 0, acabados: 0, acabadosDet: [], total: t };
    }
    var w = parseFloat(s.ancho), h = parseFloat(s.alto);
    if (!(w > 0) || !(h > 0)) { return { ok: false, error: 'Ingresa el ancho y el alto.' }; }
    var lim = p.limites;
    if (w < lim[0] || w > lim[1] || h < lim[0] || h > lim[1]) {
      return { ok: false, error: 'Cada lado debe medir entre ' + lim[0] + ' y ' + lim[1] + ' cm.' };
    }
    var area = (w * h) / 10000;
    var mat = p.materiales[0];
    p.materiales.forEach(function (m) { if (m.id === s.material) { mat = m; } });
    var dis = !!(s.diseno && p.diseno);
    var tarifa = (dis && mat.tarifa_diseno) ? mat.tarifa_diseno : mat.tarifa;
    var imp = area * tarifa;
    var minAp = imp < p.minimo;
    var base = Math.max(imp, p.minimo);
    var sub = base * cant;
    var pct = 0;
    if (p.descuentos) {
      var m2 = area * cant;
      p.descuentos.forEach(function (d) { if (m2 >= d[0]) { pct = d[1]; } });
    }
    var dv = sub * pct / 100;
    var ac = 0, det = [];
    (p.acabados || []).forEach(function (a) {
      if (s.acabados.indexOf(a.id) === -1) { return; }
      var v = a.tipo === 'unidad' ? a.valor * cant : (a.tipo === 'm2' ? a.valor * area * cant : a.valor);
      ac += v; det.push({ nombre: a.nombre, valor: Math.round(v) });
    });
    return { ok: true, producto: p.nombre, medidas: w + ' × ' + h + ' cm', material: mat.nombre, diseno: dis, area: area, cantidad: cant,
      impresion: Math.round(imp), minimoAplicado: minAp, pct: pct, descVol: Math.round(dv), acabados: Math.round(ac),
      acabadosDet: det, total: Math.round(sub - dv + ac) };
  }

  /* ---------- Cabecera y menú móvil ---------- */
  function initHeader() {
    var h = $('.lap-header');
    if (!h) { return; }
    // Subrayado parcial en menú (ej: Inicio -> "icio", Productos -> "ctos")
    var underlineMap = { inicio: 4, productos: 4, servicios: 3, nosotros: 5, portafolio: 5, contacto: 5 };
    $$('.lap-nav a, .lap-offcanvas__list a').forEach(function (a) {
      if (a.querySelector('.lp-nav-u')) { return; }
      var text = a.textContent.trim();
      var key = text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
      var n = underlineMap[key] || Math.min(3, Math.max(2, Math.floor(text.length / 2)));
      if (text.length > n) {
        a.textContent = '';
        a.appendChild(document.createTextNode(text.slice(0, text.length - n)));
        var u = el('span', 'lp-nav-u', text.slice(text.length - n));
        a.appendChild(u);
      }
    });
    var onScroll = function () { h.classList.toggle('is-scrolled', window.scrollY > 20); };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    var btn = $('.lap-toggle'), panel = $('#lap-offcanvas'), ov = $('.lap-overlay'), closeBtn = $('.lap-offcanvas__close');
    if (!btn || !panel || !ov) { return; }
    var last = null;
    function open() {
      last = document.activeElement;
      panel.classList.add('is-open'); ov.classList.add('is-open');
      btn.setAttribute('aria-expanded', 'true'); panel.setAttribute('aria-hidden', 'false');
      document.body.classList.add('lap-lock');
      if (closeBtn) { closeBtn.focus(); }
    }
    function close() {
      panel.classList.remove('is-open'); ov.classList.remove('is-open');
      btn.setAttribute('aria-expanded', 'false'); panel.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('lap-lock');
      if (last && last.focus) { last.focus(); }
    }
    btn.addEventListener('click', function () { return btn.getAttribute('aria-expanded') === 'true' ? close() : open(); });
    ov.addEventListener('click', close);
    if (closeBtn) { closeBtn.addEventListener('click', close); }
    $$('a', panel).forEach(function (a) { a.addEventListener('click', function () { close(); }); });
    document.addEventListener('keydown', function (e) {
      if (!panel.classList.contains('is-open')) { return; }
      if (e.key === 'Escape') { close(); return; }
      if (e.key === 'Tab') {
        var f = $$('a[href], button', panel);
        if (!f.length) { return; }
        var first = f[0], lastEl = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); lastEl.focus(); }
        else if (!e.shiftKey && document.activeElement === lastEl) { e.preventDefault(); first.focus(); }
      }
    });
    if (window.matchMedia) {
      var mq = window.matchMedia('(min-width: 1024px)');
      var onMq = function () { if (mq.matches && panel.classList.contains('is-open')) { close(); } };
      if (mq.addEventListener) { mq.addEventListener('change', onMq); } else if (mq.addListener) { mq.addListener(onMq); }
    }
  }

  /* ---------- Carrusel 3D ---------- */
  function initCoverflow(root) {
    var items = $$('.lap-cf__item', root);
    if (!items.length) { return; }
    var stage = $('.lap-cf__stage', root), dots = $('.lap-cf__dots', root);
    var info = { badge: $('.lap-cf__badge', root), name: $('.lap-cf__name', root), desc: $('.lap-cf__desc', root), price: $('.lap-cf__price', root), btn: $('.lap-cf__cta', root) };
    var n = items.length, cur = 0, dotEls = [];

    items.forEach(function (it, i) {
      var b = el('button', 'lap-cf__dot');
      b.type = 'button'; b.setAttribute('aria-label', 'Ver producto ' + (i + 1));
      b.addEventListener('click', function () { go(i); });
      dots.appendChild(b); dotEls.push(b);
      it.addEventListener('click', function () { if (i !== cur) { go(i); } });
    });

    function go(i) { cur = (i + n) % n; render(); }
    function render() {
      items.forEach(function (it, i) {
        var d = i - cur;
        if (d > n / 2) { d -= n; }
        if (d < -n / 2) { d += n; }
        var a = Math.abs(d);
        it.style.setProperty('--d', Math.max(-2, Math.min(2, d)));
        it.style.setProperty('--s', a === 0 ? 1 : 0.76);
        it.style.setProperty('--o', a === 0 ? 1 : (a === 1 ? 0.8 : 0));
        it.style.setProperty('--z', 10 - a);
        it.style.pointerEvents = a > 1 ? 'none' : 'auto';
        it.classList.toggle('is-active', a === 0);
        it.setAttribute('aria-hidden', a > 1 ? 'true' : 'false');
      });
      dotEls.forEach(function (d, i) { d.classList.toggle('is-active', i === cur); });
      var it = items[cur], key = it.getAttribute('data-key'), name = it.getAttribute('data-name');
      info.name.textContent = name;
      info.desc.textContent = it.getAttribute('data-desc');
      info.price.textContent = it.getAttribute('data-price');
      var badge = it.getAttribute('data-badge');
      info.badge.textContent = badge; info.badge.hidden = !badge;
      info.btn.setAttribute('data-producto', key || '');
      if (key && LPD.precios[key]) {
        info.btn.setAttribute('href', '#cotizador'); info.btn.removeAttribute('target'); info.btn.removeAttribute('rel');
      } else {
        info.btn.setAttribute('href', waUrl('Hola, quiero cotizar: ' + name)); info.btn.setAttribute('target', '_blank'); info.btn.setAttribute('rel', 'noopener');
      }
    }
    $$('[data-cf]', root).forEach(function (b) {
      b.addEventListener('click', function () { go(cur + (b.getAttribute('data-cf') === 'next' ? 1 : -1)); });
    });
    info.btn.addEventListener('click', function () {
      var key = info.btn.getAttribute('data-producto');
      if (key && LPD.precios[key]) { window.dispatchEvent(new CustomEvent('lap:select-product', { detail: key })); }
    });
    stage.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowRight') { e.preventDefault(); go(cur + 1); }
      if (e.key === 'ArrowLeft') { e.preventDefault(); go(cur - 1); }
    });
    var x0 = null;
    stage.addEventListener('pointerdown', function (e) { x0 = e.clientX; });
    stage.addEventListener('pointerup', function (e) {
      if (x0 === null) { return; }
      var dx = e.clientX - x0; x0 = null;
      if (Math.abs(dx) > 45) { go(cur + (dx < 0 ? 1 : -1)); }
    });
    stage.addEventListener('pointercancel', function () { x0 = null; });
    render();
  }

  /* ---------- Cotizador ---------- */
  function initCotizador(root) {
    var precios = LPD.precios || {};
    var keys = Object.keys(precios);
    if (!keys.length) { return; }
    var q = function (r) { return $('[data-role="' + r + '"]', root); };
    var S = { key: keys[0], medida: 0, custom: false, ancho: '', alto: '', material: '', diseno: false, acabados: [], cantidad: 1, tab: 'rapida' };
    var last = null;
    var secs = {
      medidas: $('[data-step="medidas"]', root), material: $('[data-step="material"]', root),
      diseno: $('[data-step="diseno"]', root), acabados: $('[data-step="acabados"]', root)
    };

    function prod() { return precios[S.key]; }

    function buildProductos() {
      var box = q('productos'); box.textContent = '';
      keys.forEach(function (k) {
        var b = el('button', 'lap-opt', precios[k].nombre);
        b.type = 'button'; b.setAttribute('aria-pressed', k === S.key ? 'true' : 'false');
        b.addEventListener('click', function () { select(k); });
        box.appendChild(b);
      });
    }
    function select(k) {
      if (!precios[k]) { return; }
      S.key = k; S.medida = 0; S.custom = false; S.ancho = ''; S.alto = ''; S.diseno = false; S.acabados = []; S.cantidad = 1;
      var p = precios[k];
      S.material = p.materiales ? p.materiales[0].id : '';
      if (p.medidas && p.medidas.length) { S.ancho = p.medidas[0][0]; S.alto = p.medidas[0][1]; } else { S.custom = true; }
      q('cantidad').value = 1;
      buildProductos(); buildStep(); update();
    }
    function buildStep() {
      var p = prod(), pack = p.tipo === 'paquete';
      secs.medidas.hidden = pack;
      secs.material.hidden = pack || !p.materiales || !p.materiales.length;
      secs.diseno.hidden = pack || (!p.diseno && !p.diseno_incluido);
      secs.acabados.hidden = pack || !p.acabados || !p.acabados.length;

      // medidas
      var mb = q('medidas'); mb.textContent = '';
      (p.medidas || []).forEach(function (m, i) {
        var b = el('button', 'lap-opt', m[0] + '×' + m[1] + ' cm');
        b.type = 'button'; b.setAttribute('aria-pressed', (!S.custom && S.medida === i) ? 'true' : 'false');
        b.addEventListener('click', function () { S.custom = false; S.medida = i; S.ancho = m[0]; S.alto = m[1]; syncMedidas(); update(); });
        mb.appendChild(b);
      });
      var cb = el('button', 'lap-opt', 'Personalizado');
      cb.type = 'button'; cb.setAttribute('aria-pressed', S.custom ? 'true' : 'false');
      cb.addEventListener('click', function () { S.custom = true; syncMedidas(); update(); });
      mb.appendChild(cb);
      q('limites').textContent = p.limites ? 'Cada lado entre ' + p.limites[0] + ' y ' + p.limites[1] + ' cm. Mínimo facturable: ' + money(p.minimo || 0) + ' COP.' : '';
      q('ancho').value = S.custom ? S.ancho : ''; q('alto').value = S.custom ? S.alto : '';
      q('custom').hidden = !S.custom;

      // materiales
      var mt = q('materiales'); mt.textContent = '';
      (p.materiales || []).forEach(function (m) {
        var b = el('button', 'lap-opt'); b.type = 'button';
        b.appendChild(document.createTextNode(m.nombre));
        b.appendChild(el('small', '', money(m.tarifa) + ' COP por m²'));
        b.setAttribute('aria-pressed', m.id === S.material ? 'true' : 'false');
        b.addEventListener('click', function () {
          S.material = m.id;
          $$('button', mt).forEach(function (x) { x.setAttribute('aria-pressed', 'false'); });
          b.setAttribute('aria-pressed', 'true'); update();
        });
        mt.appendChild(b);
      });

      // diseño
      var dis = q('diseno'), dtxt = q('diseno-texto');
      dis.checked = false; dis.disabled = !p.diseno;
      if (p.diseno_incluido) { dtxt.textContent = 'Diseño gráfico incluido en el precio'; }
      else if (p.diseno) {
        var m0 = p.materiales[0]; var extra = (m0.tarifa_diseno || m0.tarifa) - m0.tarifa;
        dtxt.textContent = 'Incluir diseño gráfico (+' + money(extra) + ' COP por m²)';
      }

      // acabados
      var ab = q('acabados'); ab.textContent = '';
      (p.acabados || []).forEach(function (a) {
        var lab = el('label', 'lap-check'), inp = document.createElement('input');
        inp.type = 'checkbox'; inp.value = a.id;
        inp.addEventListener('change', function () {
          S.acabados = $$('input:checked', ab).map(function (i) { return i.value; }); update();
        });
        var span = el('span', '', a.nombre);
        var unit = a.tipo === 'unidad' ? ' por unidad' : (a.tipo === 'm2' ? ' por m²' : ' (valor fijo)');
        span.appendChild(el('small', '', money(a.valor) + ' COP' + unit));
        lab.appendChild(inp); lab.appendChild(span); ab.appendChild(lab);
      });
    }
    function syncMedidas() {
      var p = prod();
      $$('button', q('medidas')).forEach(function (b, i) {
        var isCustomBtn = i === (p.medidas || []).length;
        b.setAttribute('aria-pressed', isCustomBtn ? (S.custom ? 'true' : 'false') : ((!S.custom && S.medida === i) ? 'true' : 'false'));
      });
      q('custom').hidden = !S.custom;
      if (S.custom) { q('ancho').value = S.ancho; q('alto').value = S.alto; }
    }

    function update() {
      var p = prod(), r = calc(p, S); last = r;
      var dl = q('resumen'); dl.textContent = '';
      var err = q('error');
      function row(k, v) { var d = el('div'); d.appendChild(el('dt', '', k)); d.appendChild(el('dd', '', v)); dl.appendChild(d); }
      if (!r.ok) {
        err.textContent = r.error; err.hidden = false;
        q('total').textContent = '$0'; q('total-ref').textContent = '';
        row('Producto', p.nombre);
      } else {
        err.hidden = true;
        row('Producto', r.producto);
        if (r.medidas) { row('Medidas', r.medidas); }
        if (r.material) { row('Material', r.material); }
        if (r.area) { row('Área total', (r.area * r.cantidad).toFixed(2).replace('.', ',') + ' m²'); }
        row('Cantidad', String(r.cantidad));
        row('Impresión', money(r.impresion) + ' COP');
        if (r.minimoAplicado) { row('Mínimo facturable', 'aplicado'); }
        if (r.pct) { row('Descuento por volumen', '-' + r.pct + ' % (' + money(r.descVol) + ')'); }
        r.acabadosDet.forEach(function (a) { row(a.nombre, money(a.valor) + ' COP'); });
        q('total').textContent = money(r.total) + ' COP';
        q('total-ref').textContent = 'Valor de referencia';
      }
      q('nota').textContent = 'Precios en COP, vigentes para ' + (LPD.vigencia || '') + '. Precio sujeto a verificación por un asesor antes de producir.';
    }

    // eventos de los campos
    q('ancho').addEventListener('input', function () { S.ancho = this.value; update(); });
    q('alto').addEventListener('input', function () { S.alto = this.value; update(); });
    q('diseno').addEventListener('change', function () { S.diseno = this.checked; update(); });
    var qty = q('cantidad');
    qty.addEventListener('input', function () { S.cantidad = parseInt(this.value, 10) || 1; update(); });
    q('menos').addEventListener('click', function () { S.cantidad = Math.max(1, (parseInt(qty.value, 10) || 1) - 1); qty.value = S.cantidad; update(); });
    q('mas').addEventListener('click', function () { S.cantidad = Math.min(1000, (parseInt(qty.value, 10) || 1) + 1); qty.value = S.cantidad; update(); });

    // pestañas rápida / formal
    var tabs = $$('[data-tab]', root), formal = q('formal');
    tabs.forEach(function (t) {
      t.addEventListener('click', function () {
        S.tab = t.getAttribute('data-tab');
        tabs.forEach(function (x) { x.setAttribute('aria-selected', x === t ? 'true' : 'false'); });
        formal.hidden = S.tab !== 'formal';
      });
    });

    // envío
    var form = q('form'), result = q('resultado');
    function clearErrors() {
      $$('.lap-field', form).forEach(function (f) { f.classList.remove('has-error'); var e = $('.lap-error', f); if (e) { e.textContent = ''; } });
    }
    function showErrors(errors) {
      Object.keys(errors).forEach(function (k) {
        if (k === 'cotizacion') { var e0 = q('error'); e0.textContent = errors[k]; e0.hidden = false; return; }
        var f = $('[data-field="' + k + '"]', form);
        if (f) { f.classList.add('has-error'); var e = $('.lap-error', f); if (e) { e.textContent = errors[k]; } }
      });
    }
    form.addEventListener('submit', function (ev) {
      ev.preventDefault(); clearErrors();
      if (!last || !last.ok) { showErrors({ cotizacion: last ? last.error : 'Completa la cotización.' }); return; }
      var fd = new FormData(form), data = {
        producto: S.key, ancho: S.ancho, alto: S.alto, material: S.material, diseno: S.diseno ? '1' : '', acabados: S.acabados,
        cantidad: S.cantidad, tipo: S.tab, nombre: fd.get('nombre') || '', whatsapp: fd.get('whatsapp') || '',
        correo: fd.get('correo') || '', documento: fd.get('documento') || '', ciudad: fd.get('ciudad') || '',
        codigo: fd.get('codigo') || '', consent: fd.get('consent') ? '1' : '', web: fd.get('web') || ''
      };
      var btn = q('enviar'); btn.disabled = true; var old = btn.textContent; btn.textContent = 'Enviando…';
      post('lp_guardar_cotizacion', data).then(function (res) {
        btn.disabled = false; btn.textContent = old;
        if (!res || !res.success) {
          var d = (res && res.data) || {};
          if (d.errors) { showErrors(d.errors); } else { var e = q('error'); e.textContent = d.message || 'No pudimos enviar tu cotización.'; e.hidden = false; }
          return;
        }
        var d2 = res.data; result.textContent = ''; result.hidden = false;
        result.appendChild(el('h4', '', '¡Tu cotización #' + d2.id + ' está lista!'));
        result.appendChild(el('p', '', 'Total: ' + money(d2.total) + ' COP' + (d2.descuento_web ? ' (incluye 10 % de descuento web)' : '') + '. Precio sujeto a verificación.'));
        result.appendChild(el('pre', '', d2.resumen));
        var a = el('a', 'lap-btn lap-btn--green lap-btn--block', 'Enviar por WhatsApp');
        a.href = d2.whatsapp_url; a.target = '_blank'; a.rel = 'noopener'; result.appendChild(a);
        result.appendChild(el('p', 'lap-note', 'Un asesor confirmará el valor final contigo por WhatsApp.'));
        result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }).catch(function () {
        btn.disabled = false; btn.textContent = old;
        var e = q('error'); e.textContent = 'No pudimos conectar con el servidor. Intenta de nuevo.'; e.hidden = false;
      });
    });

    // PDF (imprimir)
    q('pdf').addEventListener('click', function () {
      if (!last || !last.ok) { showErrors({ cotizacion: last ? last.error : 'Completa la cotización.' }); return; }
      var old = $('.lap-print-area'); if (old) { old.remove(); }
      var d = el('div', 'lap-print-area');
      d.appendChild(el('h1', '', 'LA PUBLICIDAD — Cotización de referencia'));
      d.appendChild(el('p', '', new Date().toLocaleDateString('es-CO') + ' · Precios en COP, vigentes para ' + (LPD.vigencia || '') + '. Precio sujeto a verificación por un asesor.'));
      var t = document.createElement('table');
      function tr(k, v) { var r = document.createElement('tr'); r.appendChild(el('td', '', k)); r.appendChild(el('td', '', v)); t.appendChild(r); }
      tr('Producto', last.producto);
      if (last.medidas) { tr('Medidas', last.medidas); }
      if (last.material) { tr('Material', last.material); }
      tr('Cantidad', String(last.cantidad));
      tr('Impresión', money(last.impresion) + ' COP');
      if (last.pct) { tr('Descuento por volumen', '-' + last.pct + ' %'); }
      last.acabadosDet.forEach(function (a) { tr(a.nombre, money(a.valor) + ' COP'); });
      tr('TOTAL', money(last.total) + ' COP');
      d.appendChild(t); document.body.appendChild(d);
      window.print();
    });

    // preselección desde el carrusel o la URL (?producto=pendon)
    window.addEventListener('lap:select-product', function (e) { select(e.detail); });
    try {
      var pq = new URLSearchParams(window.location.search).get('producto');
      if (pq && precios[pq]) { S.key = pq; }
    } catch (e) { /* sin soporte */ }
    var saved = store('lap_codigo'); if (saved) { var ci = $('[name="codigo"]', form); if (ci) { ci.value = saved; } }
    select(S.key);
  }

  /* ---------- Descuento web ---------- */
  function initDiscount() {
    var dlg = $('#lap-discount');
    if (!dlg) { return; }
    var form = $('[data-lap-discount-form]', dlg), err = $('[data-role="error"]', dlg);
    var p1 = $('[data-role="paso1"]', dlg), p2 = $('[data-role="paso2"]', dlg);
    document.addEventListener('click', function (e) {
      var t = e.target.closest ? e.target.closest('[data-lap-open-discount]') : null;
      if (!t) { return; }
      e.preventDefault();
      p1.hidden = false; p2.hidden = true; err.hidden = true;
      if (typeof dlg.showModal === 'function') { dlg.showModal(); } else { dlg.setAttribute('open', ''); }
    });
    dlg.addEventListener('click', function (e) { if (e.target === dlg && dlg.close) { dlg.close(); } });
    $('[data-role="ir"]', dlg).addEventListener('click', function () { if (dlg.close) { dlg.close(); } });
    form.addEventListener('submit', function (ev) {
      ev.preventDefault(); err.hidden = true;
      var fd = new FormData(form);
      if (!cleanWa(fd.get('whatsapp'))) { err.textContent = 'Ingresa un WhatsApp colombiano de 10 dígitos (empieza por 3).'; err.hidden = false; return; }
      var btn = $('button[type="submit"]', form); btn.disabled = true;
      post('lp_generar_descuento', { nombre: fd.get('nombre') || '', whatsapp: fd.get('whatsapp') || '', consent: fd.get('consent') ? '1' : '', web: fd.get('web') || '' })
        .then(function (res) {
          btn.disabled = false;
          if (!res || !res.success) { err.textContent = (res && res.data && res.data.message) || 'No pudimos generar el código.'; err.hidden = false; return; }
          $('[data-role="codigo"]', dlg).textContent = res.data.codigo;
          $('[data-role="vence"]', dlg).textContent = res.data.vence;
          $('[data-role="wa"]', dlg).href = res.data.whatsapp_url;
          store('lap_codigo', res.data.codigo);
          var ci = $('[name="codigo"]'); if (ci) { ci.value = res.data.codigo; }
          p1.hidden = true; p2.hidden = false;
        })
        .catch(function () { btn.disabled = false; err.textContent = 'No pudimos conectar con el servidor.'; err.hidden = false; });
    });
  }

  /* ---------- Formulario de contacto (abre WhatsApp con el mensaje) ---------- */
  function initContact(form) {
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      var fd = new FormData(form), ok = true;
      $$('.lap-field', form).forEach(function (f) { f.classList.remove('has-error'); $('.lap-error', f).textContent = ''; });
      function bad(k, msg) { ok = false; var f = $('[data-field="' + k + '"]', form); f.classList.add('has-error'); $('.lap-error', f).textContent = msg; }
      var nombre = String(fd.get('nombre') || '').trim(), correo = String(fd.get('correo') || '').trim();
      var tel = String(fd.get('telefono') || '').trim(), msg = String(fd.get('mensaje') || '').trim();
      if (nombre.length < 3) { bad('nombre', 'Escribe tu nombre.'); }
      if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(correo)) { bad('correo', 'Escribe un correo válido.'); }
      if (tel.replace(/\D/g, '').length < 7) { bad('telefono', 'Escribe un teléfono o WhatsApp.'); }
      if (msg.length < 5) { bad('mensaje', 'Cuéntanos qué necesitas.'); }
      if (!ok) { return; }
      var text = 'Hola, soy ' + nombre + '.\n' + msg + '\nCorreo: ' + correo + '\nTeléfono: ' + tel;
      window.open(waUrl(text), '_blank', 'noopener');
    });
  }

  /* ---------- Arranque (y re-arranque dentro del editor de Elementor) ---------- */
  function init(root) {
    root = root || document;
    $$('[data-lap-coverflow]', root).forEach(function (r) { if (!r.__lap) { r.__lap = 1; initCoverflow(r); } });
    $$('[data-lap-cotizador]', root).forEach(function (r) { if (!r.__lap) { r.__lap = 1; initCotizador(r); } });
    $$('[data-lap-contact]', root).forEach(function (r) { if (!r.__lap) { r.__lap = 1; initContact(r); } });
  }
  function boot() { initHeader(); initDiscount(); init(document); }
  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', boot); } else { boot(); }

  if (window.jQuery) {
    window.jQuery(window).on('elementor/frontend/init', function () {
      if (!window.elementorFrontend || !window.elementorFrontend.hooks) { return; }
      ['lp_coverflow', 'lp_cotizador', 'lp_contacto'].forEach(function (n) {
        window.elementorFrontend.hooks.addAction('frontend/element_ready/' + n + '.default', function ($el) { init($el[0]); });
      });
    });
  }
})();
