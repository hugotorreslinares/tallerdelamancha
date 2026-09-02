// Tinta Brava — interacciones básicas
(function () {
  'use strict';

  // Menú móvil
  const navToggle = document.getElementById('nav-toggle');
  const nav = document.getElementById('primary-nav');
  if (navToggle && nav) {
    navToggle.addEventListener('click', () => {
      const open = navToggle.getAttribute('aria-expanded') === 'true';
      navToggle.setAttribute('aria-expanded', String(!open));
      nav.classList.toggle('is-open', !open);
      document.body.style.overflow = !open ? 'hidden' : '';
    });
    // cerrar al hacer click en un enlace
    nav.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => {
        if (window.innerWidth < 900) {
          navToggle.setAttribute('aria-expanded', 'false');
          nav.classList.remove('is-open');
          document.body.style.overflow = '';
        }
      });
    });
  }

  // Header scroll
  const header = document.getElementById('site-header');
  if (header) {
    let last = 0;
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      header.classList.toggle('is-scrolled', y > 8);
      last = y;
    }, { passive: true });
  }

  // FAQ
  document.querySelectorAll('.faq-item').forEach(item => {
    const btn = item.querySelector('.faq-q');
    if (!btn) return;
    btn.addEventListener('click', () => {
      const open = item.getAttribute('aria-expanded') === 'true';
      item.setAttribute('aria-expanded', String(!open));
    });
  });

  // Filtros de catálogo
  document.querySelectorAll('.kit-filter').forEach(btn => {
    btn.addEventListener('click', () => {
      const group = btn.parentElement;
      group.querySelectorAll('.kit-filter').forEach(b => b.setAttribute('aria-pressed', 'false'));
      btn.setAttribute('aria-pressed', 'true');
      const filter = btn.dataset.filter;
      document.querySelectorAll('[data-cat]').forEach(card => {
        card.style.display = (filter === 'all' || card.dataset.cat === filter) ? '' : 'none';
      });
    });
  });

  // Modo oscuro
  const themeToggle = document.getElementById('theme-toggle');
  if (themeToggle) {
    const STORAGE_KEY = 'tinta-brava-theme';
    const media = window.matchMedia('(prefers-color-scheme: dark)');

    const effectiveTheme = () => {
      const explicit = document.documentElement.getAttribute('data-theme');
      if (explicit === 'light' || explicit === 'dark') return explicit;
      return media.matches ? 'dark' : 'light';
    };

    const syncIcon = () => {
      themeToggle.classList.toggle('is-dark', effectiveTheme() === 'dark');
    };

    syncIcon();
    media.addEventListener('change', () => {
      if (!localStorage.getItem(STORAGE_KEY)) syncIcon();
    });

    themeToggle.addEventListener('click', () => {
      const next = effectiveTheme() === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', next);
      try {
        localStorage.setItem(STORAGE_KEY, next);
      } catch (e) {}
      syncIcon();
    });
  }

  // Galería producto
  document.querySelectorAll('.gallery-thumb').forEach(thumb => {
    thumb.addEventListener('click', () => {
      const group = thumb.parentElement;
      group.querySelectorAll('.gallery-thumb').forEach(t => t.setAttribute('aria-current', 'false'));
      thumb.setAttribute('aria-current', 'true');
    });
  });
})();
