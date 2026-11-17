// Fungsi untuk membuka halaman baru
function bukaHalaman() {
    window.location.href = 'dashboard.php';
}

document.addEventListener('keydown', function (event) {
    if (event.key === 'P' || event.key === 'p') {
        bukaHalaman();
    }
});

window.addEventListener("load", () => {
    clock();
    function clock() {
        const today = new Date();
        const hours = today.getHours();
        const minutes = today.getMinutes();
        const seconds = today.getSeconds();

        const hour = hours < 10 ? "0" + hours : hours;
        const minute = minutes < 10 ? "0" + minutes : minutes;
        const second = seconds < 10 ? "0" + seconds : seconds;

        const ampm = hour >= 12 ? "PM" : "AM";
        const hourTime = hour > 12 ? hour - 12 : hour;
        const time = hourTime + ":" + minute + ":" + second + " " + ampm;

        document.getElementById("date-time").innerHTML = time;
        setTimeout(clock, 1000);
    }
});

setTimeout(() => {
    location.reload();
}, 10000); // 10000 ms = 10 detik
