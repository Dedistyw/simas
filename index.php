<?php
require_once __DIR__ . '/config/helpers.php';
$nama = get_setting('nama_masjid')?:DEFAULT_MASJID;
$tagline = get_setting('tagline')?:'Makmur dengan Iman dan Ilmu';
?>
<!doctype html><html><head><meta charset="utf-8"><title><?php echo htmlspecialchars($nama); ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="/simas/assets/css/style.css">
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
</head><body>
<header class="hero">
  <div class="wrap"><img src="/simas/assets/images/logo-default.png" height="80" alt="logo"><h1><?php echo htmlspecialchars($nama); ?></h1>
  <p id="quote" class="tagline"><?php echo htmlspecialchars($tagline); ?></p></div>
</header>
<nav class="nav"><a href="#profil">Profil</a><a href="#galeri">Galeri</a><a href="#laporan">Laporan</a><a href="#jadwal">Jadwal</a><a href="#kontak">Kontak</a></nav>
<main class="wrap">
<section id="profil"><h2>Profil</h2><p><?php echo nl2br(htmlspecialchars(get_setting('deskripsi')?:'Deskripsi masjid. Edit via admin.')); ?></p></section>
<section id="galeri"><h2>Galeri</h2>
  <div class="swiper-container"><div class="swiper-wrapper" id="galeri-wrapper"></div><div class="swiper-pagination"></div></div>
</section>
<section id="laporan"><h2>Laporan Kas Ringkas</h2><div id="laporan-box"><p>Pemasukan: <span id="masuk">Rp0</span></p><p>Pengeluaran: <span id="keluar">Rp0</span></p><p>Saldo: <span id="saldo">Rp0</span></p></div></section>
<section id="jadwal"><h2>Jadwal Salat</h2><div>Jam sekarang: <span id="jam"></span></div><div id="jadwal-box"></div></section>
<section id="kontak"><h2>Kontak & Pengurus</h2><div id="pengurus-list"></div></section>
</main>
<footer class="sitefoot"><small>SIMAS BERKAH <?php echo APP_VERSION;?> — Developed by Dedi & Hasan</small></footer>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
// clock
function clock(){document.getElementById('jam').innerText=new Date().toLocaleTimeString();}
setInterval(clock,1000); clock();
// load APIs
fetch('/simas/api/quotes.php').then(r=>r.json()).then(qs=>{ if(qs.length){ let idx=0; document.getElementById('quote').innerText=qs[0].teks; setInterval(()=>{ idx=(idx+1)%qs.length; document.getElementById('quote').innerText=qs[idx].teks; },8000); } });
fetch('/simas/api/galeri.php').then(r=>r.json()).then(items=>{ const w=document.getElementById('galeri-wrapper'); items.forEach(it=>{ let s=document.createElement('div'); s.className='swiper-slide'; s.innerHTML='<img src="'+it.file+'" alt="'+it.judul+'" style="width:100%"><div class="cap">'+it.judul+'</div>'; w.appendChild(s); }); new Swiper('.swiper-container',{loop:true,autoplay:{delay:5000,disableOnInteraction:false},pagination:{el:'.swiper-pagination'}}); });
fetch('/simas/api/laporan_ringkas.php').then(r=>r.json()).then(d=>{ document.getElementById('masuk').innerText=d.masuk_formatted; document.getElementById('keluar').innerText=d.keluar_formatted; document.getElementById('saldo').innerText=d.saldo_formatted; });
fetch('/simas/api/pengurus.php').then(r=>r.json()).then(list=>{ let el=document.getElementById('pengurus-list'); list.forEach(p=> el.innerHTML += '<p>'+p.nama+' — '+p.jabatan+' — <a href="https://wa.me/'+p.wa+'">WA</a></p>' ); });
</script>
</body></html>