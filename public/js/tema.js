(function () {
  var raiz = document.documentElement;
  var reducir = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var color = '#4bb8b0';
  function leerColor() { color = getComputedStyle(raiz).getPropertyValue('--ac').trim() || color; }

  var boton = document.getElementById('cambiarTema');
  if (!boton) {
    boton = document.createElement('button');
    boton.id = 'cambiarTema';
    boton.type = 'button';
    boton.className = 'tema-flotante';
    var destino = document.querySelector('.home-header__actions')
      || document.querySelector('.app__barra')
      || document.querySelector('body.con-sidebar > header');
    if (destino) {
      destino.appendChild(boton);
    } else {
      boton.classList.add('tema-flotante--fijo');
      document.body.appendChild(boton);
    }
  }

  function actualizarBoton() {
    var temaOscuro = raiz.getAttribute('data-tema') === 'oscuro';
    boton.textContent = temaOscuro ? 'Modo claro' : 'Modo oscuro';
    boton.setAttribute('aria-label', temaOscuro ? 'Activar modo claro' : 'Activar modo oscuro');
    boton.setAttribute('aria-pressed', temaOscuro ? 'true' : 'false');
    leerColor();
  }

  actualizarBoton();
  boton.addEventListener('click', function () {
    var t = raiz.getAttribute('data-tema') === 'claro' ? 'oscuro' : 'claro';
    raiz.setAttribute('data-tema', t);
    try {
      localStorage.setItem('scac-modo', t);
    } catch (error) {
      // The current page still changes if browser storage is unavailable.
    }
    actualizarBoton();
  });

  document.querySelectorAll('.tag').forEach(function (t) {
    t.setAttribute('data-e', t.textContent.trim().toLowerCase().replace(/\s+/g, '_'));
  });

  if (!reducir) {
    document.querySelectorAll('.stat__valor, .stat b').forEach(function (b) {
      var txt = b.textContent.trim();
      if (!/^\d+$/.test(txt) || txt === '0') return;
      var n = parseInt(txt, 10), ini = null;
      b.textContent = '0';
      (function paso(ts) {
        if (ini === null) ini = ts;
        var p = Math.min((ts - ini) / 900, 1);
        b.textContent = Math.round(n * (1 - Math.pow(1 - p, 3)));
        if (p < 1) requestAnimationFrame(paso);
      })(performance.now());
    });

    document.querySelectorAll('.stat, a.tarjeta').forEach(function (el) {
      el.addEventListener('mousemove', function (e) {
        var r = el.getBoundingClientRect();
        var x = (e.clientX - r.left) / r.width - 0.5, y = (e.clientY - r.top) / r.height - 0.5;
        el.style.transform = 'perspective(700px) rotateX(' + (-y * 9) + 'deg) rotateY(' + (x * 9) + 'deg) translateY(-3px)';
      });
      el.addEventListener('mouseleave', function () { el.style.transform = ''; });
    });

    var c = document.getElementById('particulas');
    if (c) {
      var g = c.getContext('2d'), W, H, P = [], N = 55, i;
      var tam = function () { W = c.width = window.innerWidth; H = c.height = window.innerHeight; };
      tam(); window.addEventListener('resize', tam);
      for (i = 0; i < N; i++) P.push({ x: Math.random() * W, y: Math.random() * H, vx: (Math.random() - 0.5) * 0.35, vy: (Math.random() - 0.5) * 0.35, r: Math.random() * 1.8 + 0.6 });
      (function dibujar() {
        if (!document.hidden) {
          g.clearRect(0, 0, W, H); g.fillStyle = color; g.strokeStyle = color;
          for (var a = 0; a < N; a++) {
            var p = P[a];
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0 || p.x > W) p.vx *= -1;
            if (p.y < 0 || p.y > H) p.vy *= -1;
            g.globalAlpha = 0.7; g.beginPath(); g.arc(p.x, p.y, p.r, 0, 6.2832); g.fill();
            for (var b = a + 1; b < N; b++) {
              var q = P[b], dx = p.x - q.x, dy = p.y - q.y, d = Math.sqrt(dx * dx + dy * dy);
              if (d < 120) { g.globalAlpha = (1 - d / 120) * 0.25; g.beginPath(); g.moveTo(p.x, p.y); g.lineTo(q.x, q.y); g.stroke(); }
            }
          }
        }
        requestAnimationFrame(dibujar);
      })();
    }
  }
})();