(function() {
  const CHAVE = 'mb-tema';
  const raiz = document.documentElement;
  const logo = document.getElementById('logo-site');

  function aplicarTema(t) {
    raiz.setAttribute('data-bs-theme', t);
    atualizarLogo(t);
  }

  function temaPreferido() {
    const salvo = localStorage.getItem(CHAVE);
    if (salvo === 'light' || salvo === 'dark') return salvo;
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

function atualizarLogo(tema) {
  const base = window.caminhoBase || '/barbearia';

  const logoTopo = document.getElementById('logo-topo');
  const logoConteudo = document.getElementById('logo-site');

  const nome = (tema === 'dark')
    ? "/meireles_barbearia_logo-Photoroom-dark.png"
    : "/meireles_barbearia_logo-Photoroom.png";

  if (logoTopo) logoTopo.src = base + nome;
  if (logoConteudo) logoConteudo.src = base + nome;
}




  function alternarTema() {
    const atual = raiz.getAttribute('data-bs-theme') || temaPreferido();
    const proximo = (atual === 'dark') ? 'light' : 'dark';
    localStorage.setItem(CHAVE, proximo);
    aplicarTema(proximo);
  }

  // Garante estado coerente
  aplicarTema(temaPreferido());

  // Delegação para qualquer botão com data-tema-toggle
  document.addEventListener('click', function(e) {
    const alvo = e.target.closest('[data-tema-toggle]');
    if (alvo) {
      e.preventDefault();
      alternarTema();
    }
  });

  // Se o usuário não escolheu nada, seguir o sistema
  if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      if (!localStorage.getItem(CHAVE)) aplicarTema(temaPreferido());
    });
  }
})();
