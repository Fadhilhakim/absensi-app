<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../Controller/config.php';
if (!isset($_SESSION['user'])) {
  header('Location: ../../../login.php');
  exit;
}
?>
<link rel="stylesheet" href="css/input.css">

<style>
/***** Simple dashboard widgets *****/
.dashboard-grid { display: grid; grid-template-columns: repeat(4, minmax(180px, 1fr)); gap: 16px; margin: 16px 0 24px; }
.dashboard-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
.dashboard-card h3 { margin: 0 0 8px; font-size: 14px; color: #6b7280; font-weight: 600; }
.dashboard-card .value { font-size: 28px; font-weight: 700; color: #111827; }
.danger { color: #dc2626; }
.success { color: #16a34a; }
.warning { color: #d97706; }

/***** Charts section *****/
.charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; }
.chart-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
.chart-card h3 { margin: 0 0 12px; font-size: 16px; color: #111827; }

/***** Policy notice *****/
.policy-card { background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #ef4444; border-radius: 8px; padding: 12px 16px; margin: 16px 0; }
.policy-card h3 { margin: 0 0 8px; font-size: 16px; color: #111827; }
.policy-card ul { margin: 0; padding-left: 18px; }
.policy-card li { margin: 6px 0; font-size: 14px; color: #374151; }

/***** Lateness panel *****/
.late-panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 0; box-shadow: 0 1px 2px rgba(0,0,0,.04); }
.late-header { padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; }
.late-header h3 { margin: 0; font-size: 16px; }
.late-body { max-height: 320px; overflow: auto; }
.late-row { display: grid; grid-template-columns: 1fr 120px 90px 200px 110px; gap: 12px; align-items: center; padding: 10px 16px; border-top: 1px solid #f1f5f9; font-size: 14px; }
.late-row .actions { display: flex; gap: 8px; justify-content: flex-end; }
.btn { padding: 6px 10px; border: 1px solid #e5e7eb; background: #fff; border-radius: 6px; cursor: pointer; font-size: 12px; }
.btn:hover { background: #f3f4f6; }
.btn.danger { color: #b91c1c; border-color: #fecaca; background: #fef2f2; }
.btn.primary { color: #1f2937; border-color: #c7d2fe; background: #eef2ff; }

@media (max-width: 900px) {
  .late-row { grid-template-columns: 1fr 100px 70px 160px 100px; }
}
@media (max-width: 640px) {
  .late-row { grid-template-columns: 1fr; }
  .late-row .actions { justify-content: flex-start; }
}

/***** Lists & Leaderboard *****/
.lists-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 16px; }
.list-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px 0; box-shadow: 0 1px 2px rgba(0,0,0,.04); max-height: 320px; display: flex; flex-direction: column; }
.list-card h3 { padding: 0 16px; margin: 0 8px 8px; font-size: 16px; flex: 0 0 auto; }
.list-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.list-table th, .list-table td { text-align: left; padding: 8px 16px; border-top: 1px solid #f1f5f9; }
.badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 12px; font-weight: 600; }
.badge.alpha { background: #fee2e2; color: #b91c1c; }
.badge.izin { background: #ffedd5; color: #c2410c; }
.badge.sakit { background: #fef9c3; color: #a16207; }

.leaderboard { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,.04); margin-top: 16px; }
.leaderboard h3 { margin: 0 0 12px; font-size: 16px; }
.leader { display: grid; grid-template-columns: 40px 1fr 80px; align-items: center; gap: 12px; padding: 10px 0; border-top: 1px solid #f1f5f9; }
.leader .rank { width: 28px; height: 28px; border-radius: 50%; background: #eef2ff; color: #3730a3; display: grid; place-items: center; font-weight: 700; }
.leader .name { font-weight: 600; }
.leader .count { text-align: right; font-weight: 700; color: #16a34a; }

@media (max-width: 1024px) {
  .dashboard-grid { grid-template-columns: repeat(2, 1fr); }
  .charts-grid { grid-template-columns: 1fr; }
  .lists-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
  .dashboard-grid { grid-template-columns: 1fr; }
  .lists-grid { grid-template-columns: 1fr; }
}

@media (max-width: 1024px) {
  .dashboard-grid { grid-template-columns: repeat(2, 1fr); }
  .charts-grid { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
  .dashboard-grid { grid-template-columns: 1fr; }
}
</style>

<h1>Dashboard Utama</h1>
<p>Selamat datang di dashboard absensi sekolah. Pilih menu di sidebar untuk mengelola data.</p>

<!-- Quick Stats -->
<div class="dashboard-grid">
  <div class="dashboard-card">
    <h3>Total Siswa</h3>
    <div class="value" id="statTotalSiswa">0</div>
  </div>
  <div class="dashboard-card">
    <h3>Total Guru</h3>
    <div class="value" id="statTotalGuru">0</div>
  </div>
  <div class="dashboard-card">
    <h3>Hadir Hari Ini</h3>
    <div class="value success" id="statHadirHariIni">0</div>
  </div>
  <div class="dashboard-card">
    <h3>Tidak Hadir Hari Ini</h3>
    <div class="value danger" id="statTidakHadirHariIni">0</div>
  </div>
</div>

<!-- Charts -->
<div class="charts-grid">
  <div class="chart-card">
    <h3>Tren Kehadiran 7 Hari</h3>
    <canvas id="chartTrenHarian" height="120"></canvas>
  </div>
  <div class="chart-card">
    <h3>Persentase Kehadiran Hari Ini</h3>
    <canvas id="chartPieHariIni" height="120"></canvas>
  </div>
</div>

<!-- Policy Notice -->
<div class="policy-card">
  <h3>Sanksi Keterlambatan</h3>
  <ul>
    <li><strong>🔴 Terlambat 1 - 2 kali dalam sebulan:</strong> ✅ Teguran lisan dari guru piket atau wali kelas.</li>
    <li><strong>🔴 Terlambat 3 - 4 kali dalam sebulan:</strong> ✅ Teguran tertulis dan pemberitahuan kepada orang tua/wali siswa.</li>
    <li><strong>🔴 Terlambat 5 kali atau lebih dalam sebulan:</strong> ✅ Pemanggilan orang tua untuk pembinaan siswa. ✅ Siswa wajib membuat surat pernyataan tidak mengulangi keterlambatan.</li>
    <li><strong>🔴 Terlambat lebih dari 7 kali dalam sebulan:</strong> ✅ Sanksi lebih lanjut seperti tugas tambahan, skorsing, atau tindakan lain sesuai kebijakan sekolah.</li>
  </ul>
</div>

<!-- Lateness Panel -->
<div class="late-panel">
  <div class="late-header">
    <h3>Daftar Keterlambatan Bulan Ini</h3>
  </div>
  <div class="late-body" id="lateBody">
    <!-- rows injected by JS -->
  </div>
</div>

<!-- Lists -->
<div class="lists-grid">
  <div class="list-card">
    <h3>Alpha > 3 hari</h3>
    <div class="table-wrap">
      <table class="list-table" id="tblAlpha">
        <thead><tr><th>Nama</th><th>Kelas</th><th>Jumlah</th></tr></thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
  <div class="list-card">
    <h3>Izin > 4 hari</h3>
    <div class="table-wrap">
      <table class="list-table" id="tblIzin">
        <thead><tr><th>Nama</th><th>Kelas</th><th>Jumlah</th></tr></thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
  <div class="list-card">
    <h3>Sakit > 7 hari</h3>
    <div class="table-wrap">
      <table class="list-table" id="tblSakit">
        <thead><tr><th>Nama</th><th>Kelas</th><th>Jumlah</th></tr></thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<style>
/***** List table container scroll *****/
.list-card .table-wrap { overflow: auto; flex: 1 1 auto; }
.list-card table { width: 100%; }
</style>

<!-- Leaderboard -->
<div class="leaderboard" id="leaderboardHadir">
  <h3>Top 5 Kehadiran</h3>
  <!-- rows injected by JS -->
</div>

<!-- Dependencies: Chart.js -->
<script src="js/chart.umd.min.js"></script>
<script>
(function() {
  // Placeholder: inisialisasi data dari PHP jika tersedia
  // Anda dapat men-echo variabel PHP ke JS di sini jika controller menyediakannya
  const totals = {
    siswa: window.__TOTAL_SISWA__ ?? 0,
    guru: window.__TOTAL_GURU__ ?? 0
  };

  const today = {
    hadir: window.__HADIR_HARI_INI__ ?? 0,
    tidakHadir: window.__TIDAK_HADIR_HARI_INI__ ?? 0
  };

  const tren7Hari = window.__TREN_7_HARI__ ?? {
    labels: [
      'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'
    ],
    values: [12, 15, 14, 16, 13, 0, 0]
  };

  // Tampilkan angka pada widget
  document.getElementById('statTotalSiswa').textContent = totals.siswa;
  document.getElementById('statTotalGuru').textContent = totals.guru;
  document.getElementById('statHadirHariIni').textContent = today.hadir;
  document.getElementById('statTidakHadirHariIni').textContent = today.tidakHadir;

  const primary = '#3b82f6';
  const success = '#10b981';
  const danger = '#ef4444';
  const gray = '#9ca3af';

  // Line chart: Tren 7 hari
  const ctxLine = document.getElementById('chartTrenHarian');
  if (ctxLine) {
    new Chart(ctxLine, {
      type: 'line',
      data: {
        labels: tren7Hari.labels,
        datasets: [{
          label: 'Hadir',
          data: tren7Hari.values,
          borderColor: primary,
          backgroundColor: 'rgba(59,130,246,0.15)',
          tension: .3,
          fill: true,
          pointRadius: 3,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: { beginAtZero: true, ticks: { precision: 0 } }
        }
      }
    });
  }

  // Pie chart: Hari ini
  const ctxPie = document.getElementById('chartPieHariIni');
  if (ctxPie) {
    new Chart(ctxPie, {
      type: 'doughnut',
      data: {
        labels: ['Hadir', 'Tidak Hadir'],
        datasets: [{
          data: [today.hadir, today.tidakHadir],
          backgroundColor: [success, danger],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' }
        },
        cutout: '60%'
      }
    });
  }

  // Render lists
  function fillTable(id, rows, badgeClass) {
    const tbody = document.querySelector(`#${id} tbody`);
    if (!tbody) return;
    tbody.innerHTML = '';
    if (!rows || rows.length === 0) {
      const tr = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = 3;
      td.textContent = 'Tidak ada data';
      td.style.color = gray;
      tr.appendChild(td);
      tbody.appendChild(tr);
      return;
    }
    rows.forEach(r => {
      const tr = document.createElement('tr');
      const tdNama = document.createElement('td'); tdNama.textContent = r.nama || r.NAMA || '-';
      const tdKelas = document.createElement('td'); tdKelas.textContent = r.nama_kelas || '-';
      const tdJumlah = document.createElement('td'); tdJumlah.innerHTML = `<span class="badge ${badgeClass}">${r.jumlah}</span>`;
      tr.appendChild(tdNama); tr.appendChild(tdKelas); tr.appendChild(tdJumlah);
      tbody.appendChild(tr);
    });
  }

  fillTable('tblAlpha', window.__ALPHA_GT3__ || [], 'alpha');
  fillTable('tblIzin', window.__IZIN_GT4__ || [], 'izin');
  fillTable('tblSakit', window.__SAKIT_GT7__ || [], 'sakit');

  // Leaderboard Top 5 Hadir
  (function renderLeaderboard(){
    const data = window.__TOP5_HADIR__ || [];
    const host = document.getElementById('leaderboardHadir');
    if (!host) return;
    data.forEach((r, i) => {
      const row = document.createElement('div');
      row.className = 'leader';
      row.innerHTML = `
        <div class="rank">${i+1}</div>
        <div class="name">${r.nama || '-' }<div style="color:#6b7280; font-size:12px;">${r.nama_kelas || ''}</div></div>
      `;
      host.appendChild(row);
    });
    if (data.length === 0) {
      const p = document.createElement('p');
      p.textContent = 'Tidak ada data';
      p.style.color = gray;
      host.appendChild(p);
    }
  })();
// Lateness panel rendering with client-side actions only (no DB change)
  (function renderLatePanel(){
    const list = window.__TERLAMBAT_LIST__ || [];
    const host = document.getElementById('lateBody');
    if (!host) return;

    function render() {
      host.innerHTML = '';
      if (list.length === 0) {
        const d = document.createElement('div');
        d.style.color = gray; d.style.padding = '12px 16px';
        d.textContent = 'Tidak ada data keterlambatan';
        host.appendChild(d);
        return;
      }
      list.forEach((r, idx) => {
        const row = document.createElement('div');
        row.className = 'late-row';
        row.innerHTML = `
          <div><strong>${r.nama}</strong><div style="color:#6b7280;font-size:12px">${r.nama_kelas || ''} · NISN ${r.nisn}</div></div>
          <div><span class="badge">${r.jumlah}x</span></div>
          <div><span class="badge ${r.jumlah>=3?'alpha':''}">${r.jumlah>=8?'7+':r.jumlah}</span></div>
          <div style="color:#374151">${r.rekomendasi || '-'}</div>
          <div class="actions">
            <button class="btn primary" title="Tandai sudah ditindak">Tandai</button>
            <button class="btn" title="Ubah status lokal">Ubah</button>
            <button class="btn danger" title="Sembunyikan dari daftar">Hapus</button>
          </div>
        `;
        const [btnTandai, btnUbah, btnHapus] = row.querySelectorAll('button');
        btnHapus.addEventListener('click', () => { list.splice(idx,1); render(); });
        btnTandai.addEventListener('click', () => { row.style.opacity = .5; row.style.filter = 'grayscale(0.4)'; });
        btnUbah.addEventListener('click', () => {
          const newCount = prompt('Masukkan jumlah keterlambatan (hanya untuk tampilan):', r.jumlah);
          const n = parseInt(newCount, 10);
          if (!isNaN(n)) {
            r.jumlah = n;
            // Update rekomendasi lokal sesuai kebijakan
            if (n > 7) r.rekomendasi = 'Sanksi lanjut: tugas tambahan/skorsing (kebijakan sekolah)';
            else if (n >= 5) r.rekomendasi = 'Pemanggilan orang tua + surat pernyataan';
            else if (n >= 3) r.rekomendasi = 'Teguran tertulis + pemberitahuan orang tua';
            else if (n >= 1) r.rekomendasi = 'Teguran lisan';
            else r.rekomendasi = '-';
            render();
          }
        });
        host.appendChild(row);
      });
    }
    render();
  })();
})();
</script>