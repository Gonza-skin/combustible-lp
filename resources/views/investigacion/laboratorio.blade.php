<x-layouts.app title="Laboratorio de demostración">
    <style>
        .io-nota{color:var(--texto-suave,#667);font-size:13px;line-height:1.6}
        .io-tabs{display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap}
        .io-tabs button{padding:9px 16px;border:1px solid var(--borde,#ccc);border-radius:11px;background:transparent;color:inherit;font:inherit;cursor:pointer}
        .io-tabs button.activo{background:var(--ac,#19d98b);color:#03150d;font-weight:700;border-color:transparent}
        .io-banner{padding:12px 16px;margin-bottom:16px;border-radius:12px;border:1px dashed var(--ac,#19d98b);color:var(--texto-suave,#667);font-size:14px}
        .io-tabla{width:100%;border-collapse:collapse}
        .io-tabla th,.io-tabla td{padding:8px 10px;border-bottom:1px solid var(--borde,#ddd);text-align:left;vertical-align:middle}
        .io-tabla th{font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:var(--texto-suave,#667)}
        .io-wrap{overflow-x:auto}
        .io-lab input,.io-lab select{padding:7px 9px;border:1px solid var(--borde,#ccc);border-radius:9px;background:var(--campo-fondo,#fff);color:inherit;font:inherit;width:100%;min-width:72px}
        .io-filtros{display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;margin-top:14px}
        .io-filtros label{display:block;font-size:12px;color:var(--texto-suave,#667);margin-bottom:4px}
        .io-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px}
        .io-grid>*{min-width:0}
        .io-bar{height:8px;border-radius:99px;background:var(--borde,#ddd);overflow:hidden;margin-top:5px}
        .io-bar i{display:block;height:100%;border-radius:99px;background:var(--ac,#19d98b);transition:width .35s}
        .io-bar.mal i{background:var(--rojo,#d64545)}
        .io-pill{display:inline-block;padding:2px 10px;border-radius:99px;font-size:12px;border:1px solid var(--borde,#ccc)}
        .io-pill.mal{color:var(--rojo,#d64545);border-color:var(--rojo,#d64545)}
        .io-mejor{background:color-mix(in srgb,var(--ac,#19d98b) 18%,transparent);font-weight:700}
        .io-veredicto{font-size:20px;font-weight:700;margin:4px 0}
        .io-mal{color:var(--rojo,#d64545)}
    </style>

    <x-page-header titulo="Laboratorio de demostración"
                   subtitulo="Editá los valores y mirá cómo cambian los resultados en vivo" />

    <div class="io-banner">Esta pantalla es solo para demostrar: <strong>no guarda nada y no usa ni modifica los datos reales</strong>. Todo se calcula en tu navegador.</div>

    <div class="io-tabs">
        <button type="button" class="activo" data-tab="markov">Cadenas de Markov</button>
        <button type="button" data-tab="decisiones">Teoría de decisiones</button>
    </div>

    <div class="io-lab">
    <section id="tab-markov">
        <x-card>
            <div class="tarjeta__titulo"><h2>Matriz de transición P (editable)</h2>
                <span class="io-nota">Cargar:
                    <a href="#" data-mk-preset="informe">matriz del informe</a> ·
                    <a href="#" data-mk-preset="riesgosa">estación riesgosa</a> ·
                    <a href="#" data-mk-preset="estable">estación estable</a></span></div>
            <div class="io-wrap"><table class="io-tabla" id="mk-matriz"></table></div>
            <p class="io-nota" id="mk-aviso"></p>
            <div class="io-filtros">
                <div><label>Hoy la estación está</label>
                    <select id="mk-inicio"><option value="0">Disponible</option><option value="1">Fila larga</option><option value="2">Agotado</option></select></div>
                <div><label>Días a predecir</label><input type="number" id="mk-dias" min="1" max="60" value="3"></div>
                <div><label>Umbral de alerta (%)</label><input type="number" id="mk-umbral" min="1" max="100" value="25"></div>
                <div><button type="button" class="btn btn--outline" id="mk-normalizar">Normalizar filas a 1</button></div>
            </div>
        </x-card>
        <div class="io-grid">
            <x-card><div class="tarjeta__titulo"><h2>Dentro de <span id="mk-n">3</span> días</h2></div><div id="mk-an"></div></x-card>
            <x-card><div class="tarjeta__titulo"><h2>Largo plazo (π)</h2></div><div id="mk-pi"></div></x-card>
            <x-card><div class="tarjeta__titulo"><h2>Primer agotamiento</h2></div><div id="mk-mu"></div></x-card>
        </div>
        <x-card>
            <div class="tarjeta__titulo"><h2>Día por día (aₙ = a₀ · Pⁿ)</h2></div>
            <div class="io-wrap"><table class="io-tabla" id="mk-tray"></table></div>
            <p class="io-nota">Cada fila se obtiene multiplicando la anterior por la matriz P: es la cuenta que se hace en el pizarrón.</p>
        </x-card>
    </section>

    <section id="tab-decisiones" hidden>
        <x-card>
            <div class="tarjeta__titulo"><h2>Tabla de pagos: vehículos atendidos (editable)</h2>
                <span class="io-nota">Cargar:
                    <a href="#" data-dc-preset="informe">Tabla 1 del informe</a> ·
                    <a href="#" data-dc-preset="contraste">ejemplo donde difieren</a></span></div>
            <div class="io-wrap"><table class="io-tabla" id="dc-tabla"></table></div>
            <p class="io-nota" id="dc-aviso"></p>
        </x-card>
        <div class="io-grid">
            <x-card><div class="tarjeta__titulo"><h2>Valor esperado</h2></div><div id="dc-ve"></div></x-card>
            <x-card><div class="tarjeta__titulo"><h2>Wald (maximin)</h2></div><div id="dc-wald"></div></x-card>
        </div>
        <x-card><p class="io-nota" id="dc-msg" style="margin:0"></p></x-card>
    </section>
    </div>

@verbatim
    <script>
    (() => {
      const E = ['Disponible', 'Fila larga', 'Agotado'];
      const D = ['Demanda alta', 'Demanda media', 'Demanda baja'];
      const MK = {
        informe:  [[.70, .20, .10], [.30, .50, .20], [.20, .40, .40]],
        riesgosa: [[.55, .25, .20], [.25, .40, .35], [.15, .35, .50]],
        estable:  [[.80, .15, .05], [.35, .50, .15], [.25, .45, .30]],
      };
      const DC = {
        informe:   { n: ['Estación A', 'Estación B', 'Estación C'], p: [[500, 300, 150], [200, 150, 100], [350, 250, 120]], pr: [30, 50, 20] },
        contraste: { n: ['Estación A', 'Estación B', 'Estación C'], p: [[500, 200, 20], [250, 220, 200], [330, 240, 110]], pr: [30, 50, 20] },
      };
      const $ = s => document.querySelector(s);
      const f = (x, d = 1) => Number(x).toFixed(d).replace('.', ',');
      const pc = x => f(x * 100, 1) + ' %';
      const bar = (x, mal) => '<div class="io-bar ' + (mal ? 'mal' : '') + '"><i style="width:' + Math.round(x * 100) + '%"></i></div>';

      // ---------- matemática (la misma de CadenaMarkov.php) ----------
      const step = (v, P) => P[0].map((_, j) => v.reduce((s, x, i) => s + x * P[i][j], 0));
      function solve(A, b) {
        const n = b.length; A = A.map(r => r.slice()); b = b.slice();
        for (let c = 0; c < n; c++) {
          let m = c;
          for (let r = c + 1; r < n; r++) if (Math.abs(A[r][c]) > Math.abs(A[m][c])) m = r;
          if (Math.abs(A[m][c]) < 1e-12) return null;
          [A[c], A[m]] = [A[m], A[c]]; [b[c], b[m]] = [b[m], b[c]];
          for (let r = c + 1; r < n; r++) {
            const k = A[r][c] / A[c][c];
            for (let j = c; j < n; j++) A[r][j] -= k * A[c][j];
            b[r] -= k * b[c];
          }
        }
        const x = Array(n).fill(0);
        for (let i = n - 1; i >= 0; i--) {
          let s = b[i];
          for (let j = i + 1; j < n; j++) s -= A[i][j] * x[j];
          x[i] = s / A[i][i];
        }
        return x;
      }
      const estacionaria = P => { const A = P.map((_, i) => P.map((_, j) => P[j][i] - (i === j ? 1 : 0))); A[2] = [1, 1, 1]; return solve(A, [0, 0, 1]); };
      const primerAgotamiento = P => solve([[1 - P[0][0], -P[0][1]], [-P[1][0], 1 - P[1][1]]], [1, 1]);

      // ---------- pestañas ----------
      document.querySelectorAll('.io-tabs button').forEach(b => b.addEventListener('click', () => {
        document.querySelectorAll('.io-tabs button').forEach(x => x.classList.toggle('activo', x === b));
        $('#tab-markov').hidden = b.dataset.tab !== 'markov';
        $('#tab-decisiones').hidden = b.dataset.tab !== 'decisiones';
      }));

      // ---------- laboratorio Markov ----------
      const mkIn = () => [...document.querySelectorAll('#mk-matriz input')];
      function mkConstruir(P) {
        let h = '<tr><th>Hoy ↓ / Mañana →</th>' + E.map(e => '<th>' + e + '</th>').join('') + '<th>Suma</th></tr>';
        P.forEach((fila, i) => {
          h += '<tr><th>' + E[i] + '</th>' + fila.map((v, j) => '<td><input type="number" step="0.01" min="0" max="1" data-i="' + i + '" data-j="' + j + '" value="' + v + '"></td>').join('') + '<td data-suma="' + i + '"></td></tr>';
        });
        $('#mk-matriz').innerHTML = h;
        mkIn().forEach(x => x.addEventListener('input', mkCalcular));
      }
      const mkLeer = () => { const P = [[0, 0, 0], [0, 0, 0], [0, 0, 0]]; mkIn().forEach(x => { P[+x.dataset.i][+x.dataset.j] = parseFloat(x.value) || 0; }); return P; };
      function mkCalcular() {
        const P = mkLeer(), sumas = P.map(r => r.reduce((a, b) => a + b, 0));
        sumas.forEach((s, i) => { const c = document.querySelector('[data-suma="' + i + '"]'); const ok = Math.abs(s - 1) < 0.005; c.textContent = f(s, 2); c.className = ok ? '' : 'io-mal'; });
        const malas = sumas.map((s, i) => [s, i]).filter(([s]) => Math.abs(s - 1) >= 0.005);
        if (sumas.some(s => s <= 0)) {
          $('#mk-aviso').textContent = 'Cada fila necesita al menos un valor mayor que 0.';
          ['#mk-an', '#mk-pi', '#mk-mu'].forEach(s => { $(s).innerHTML = ''; }); $('#mk-tray').innerHTML = ''; return;
        }
        $('#mk-aviso').textContent = malas.length ? 'Las filas deben sumar 1. Para calcular se normalizaron automáticamente (podés usar «Normalizar filas a 1»).' : 'Cada fila suma 1: la matriz es válida.';
        const N = P.map((r, i) => r.map(x => x / sumas[i]));
        const n = Math.max(1, Math.min(60, parseInt($('#mk-dias').value) || 1)), umbral = parseFloat($('#mk-umbral').value) || 25;
        let a = [0, 0, 0]; a[+$('#mk-inicio').value] = 1;
        const tray = [a];
        for (let k = 1; k <= n; k++) { a = step(a, N); tray.push(a); }
        $('#mk-n').textContent = n;
        $('#mk-an').innerHTML = E.map((e, i) => '<div style="margin-top:8px">' + e + ': ' + pc(a[i]) + bar(a[i], i === 2) + '</div>').join('')
          + (a[2] * 100 >= umbral ? '<p><span class="io-pill mal">ALERTA: supera el umbral de ' + f(umbral, 0) + ' %</span></p>' : '<p class="io-nota">No supera el umbral de ' + f(umbral, 0) + ' %.</p>');
        const pi = estacionaria(N);
        $('#mk-pi').innerHTML = pi ? E.map((e, i) => '<div style="margin-top:8px">' + e + ': ' + pc(pi[i]) + bar(pi[i], i === 2) + '</div>').join('') + '<p class="io-nota">Agotada ≈ ' + f(pi[2] * 30, 1) + ' días por mes.</p>' : '<p class="io-nota">Sin distribución estable con esta matriz.</p>';
        const mu = primerAgotamiento(N);
        $('#mk-mu').innerHTML = mu ? '<div style="margin-top:8px">Desde Disponible: <strong>' + f(mu[0], 2) + '</strong> días</div><div style="margin-top:8px">Desde Fila larga: <strong>' + f(mu[1], 2) + '</strong> días</div><p class="io-nota">μ = (I − N)⁻¹ · 1</p>' : '<p class="io-nota">La estación nunca llega a agotarse con esta matriz.</p>';
        const ks = n <= 10 ? tray.map((_, k) => k) : [...Array(10).keys(), n];
        $('#mk-tray').innerHTML = '<tr><th>Día</th>' + E.map(e => '<th>' + e + '</th>').join('') + '</tr>'
          + ks.map(k => '<tr><td>' + k + (k === 0 ? ' (hoy)' : '') + '</td>' + tray[k].map((x, i) => '<td' + (i === 2 && k === n ? ' class="io-mejor"' : '') + '>' + pc(x) + '</td>').join('') + '</tr>').join('');
      }
      document.querySelectorAll('[data-mk-preset]').forEach(a => a.addEventListener('click', e => { e.preventDefault(); mkConstruir(MK[a.dataset.mkPreset]); mkCalcular(); }));
      $('#mk-normalizar').addEventListener('click', () => { const P = mkLeer(); mkConstruir(P.map(r => { const s = r.reduce((x, y) => x + y, 0) || 1; return r.map(x => Math.round(x / s * 100) / 100); })); mkCalcular(); });
      ['#mk-inicio', '#mk-dias', '#mk-umbral'].forEach(s => $(s).addEventListener('input', mkCalcular));

      // ---------- laboratorio de decisiones ----------
      const dcIn = s => [...document.querySelectorAll('#dc-tabla ' + s)];
      function dcConstruir(d) {
        let h = '<tr><th>Enviar la cisterna a…</th>' + D.map(x => '<th>' + x + '</th>').join('') + '</tr>';
        d.p.forEach((fila, i) => { h += '<tr><td><input type="text" data-nom="' + i + '" value="' + d.n[i] + '"></td>' + fila.map((v, j) => '<td><input type="number" min="0" step="any" data-i="' + i + '" data-j="' + j + '" value="' + v + '"></td>').join('') + '</tr>'; });
        h += '<tr><td><strong>Probabilidad (%)</strong></td>' + d.pr.map((v, j) => '<td><input type="number" min="0" max="100" step="any" data-pr="' + j + '" value="' + v + '"></td>').join('') + '</tr>';
        $('#dc-tabla').innerHTML = h;
        dcIn('input').forEach(x => x.addEventListener('input', dcCalcular));
      }
      function dcCalcular() {
        const nom = dcIn('[data-nom]').map(x => x.value || 'Alternativa');
        const p = [[0, 0, 0], [0, 0, 0], [0, 0, 0]]; dcIn('[data-i]').forEach(x => { p[+x.dataset.i][+x.dataset.j] = parseFloat(x.value) || 0; });
        const pr = dcIn('[data-pr]').map(x => parseFloat(x.value) || 0), sp = pr.reduce((a, b) => a + b, 0);
        if (sp <= 0) { $('#dc-aviso').textContent = 'Poné probabilidades mayores que 0.'; return; }
        $('#dc-aviso').textContent = Math.abs(sp - 100) < 0.1 ? 'Las probabilidades suman 100 %.' : 'Las probabilidades suman ' + f(sp, 1) + ' % (deben sumar 100). Para calcular se normalizaron.';
        const pn = pr.map(x => x / sp);
        const ve = p.map(r => r.reduce((s, x, j) => s + x * pn[j], 0)), mn = p.map(r => Math.min(...r));
        const mejor = v => v.reduce((m, x, i) => (x > v[m] + 1e-9 ? i : m), 0), bv = mejor(ve), bw = mejor(mn);
        const lista = (v, b, d) => v.map((x, i) => '<div class="' + (i === b ? 'io-mejor' : '') + '" style="padding:6px 8px;margin-top:6px;border-radius:8px">' + nom[i] + ': <strong>' + f(x, d) + '</strong>' + bar(x / (Math.max(...v) || 1)) + '</div>').join('');
        $('#dc-ve').innerHTML = lista(ve, bv, 1) + '<p class="io-nota">VE = Σ pⱼ · pagoᵢⱼ</p>';
        $('#dc-wald').innerHTML = lista(mn, bw, 0) + '<p class="io-nota">Se elige el mayor de los peores casos.</p>';
        $('#dc-msg').innerHTML = bv === bw
          ? 'Los dos criterios coinciden: <strong>' + nom[bv] + '</strong> es la mejor opción en promedio y en el peor escenario.'
          : 'Los criterios difieren: el valor esperado prefiere <strong>' + nom[bv] + '</strong> (mejor en promedio) y Wald prefiere <strong>' + nom[bw] + '</strong> (más seguro si la demanda es baja).';
      }
      document.querySelectorAll('[data-dc-preset]').forEach(a => a.addEventListener('click', e => { e.preventDefault(); dcConstruir(DC[a.dataset.dcPreset]); dcCalcular(); }));

      mkConstruir(MK.informe); mkCalcular(); dcConstruir(DC.informe); dcCalcular();
    })();
    </script>
@endverbatim
</x-layouts.app>