<?= $this->extend('template/layout_user') ?>
<?= $this->section('content') ?>

<style>
    body { padding-bottom: 140px; }
    
    #my_camera,
    #my_camera video {
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: auto !important;
        border-radius: 20px;
    }
    
    /* Bottom Navigation */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(90deg, #1a1a2e 0%, #16213e 50%, #1a1a2e 100%);
        border-top: 1px solid #333;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 90px;
        box-shadow: 0 -4px 30px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.05);
        z-index: 1000;
        padding-bottom: env(safe-area-inset-bottom);
        animation: slideUpNav 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes slideUpNav {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .nav-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0;
        width: 100%;
        height: 100%;
        max-width: 600px;
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
        height: 90px;
        text-decoration: none;
        color: #888;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        font-size: 0.75rem;
        cursor: pointer;
    }

    .nav-item:hover:not(.nav-absensi) {
        color: #06b6d4;
        transform: translateY(-3px);
    }

    .nav-item.active:not(.nav-absensi)::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #4f46e5, #06b6d4);
        border-radius: 3px 3px 0 0;
    }

    .nav-icon {
        font-size: 1.3rem;
        margin-bottom: 0.3rem;
        transition: all 0.3s ease;
    }

    .nav-label {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Absensi Button in Navbar */
    .nav-absensi {
        position: relative;
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        border-radius: 50%;
        color: white;
        font-size: 1.4rem;
        flex: none;
        box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        transform: translateY(-15px);
        animation: bounceAbsensi 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes bounceAbsensi {
        0% {
            transform: translateY(30px);
            opacity: 0;
        }
        50% {
            transform: translateY(-20px);
        }
        100% {
            transform: translateY(-15px);
            opacity: 1;
        }
    }

    .nav-absensi::before {
        content: '';
        position: absolute;
        top: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 10px;
        background: radial-gradient(ellipse at center, rgba(255, 107, 53, 0.3) 0%, transparent 70%);
        border-radius: 50%;
        animation: pulseGlow 2s ease-in-out infinite;
    }

    @keyframes pulseGlow {
        0%, 100% {
            opacity: 0.3;
            transform: translateX(-50%) scale(1);
        }
        50% {
            opacity: 0.6;
            transform: translateX(-50%) scale(1.2);
        }
    }

    .nav-absensi:hover {
        transform: translateY(-15px) scale(1.1);
        box-shadow: 0 12px 40px rgba(255, 107, 53, 0.6), 0 0 20px rgba(255, 107, 53, 0.3);
    }

    .nav-absensi:active {
        transform: translateY(-12px) scale(0.95);
    }

    .nav-absensi .nav-icon {
        margin-bottom: 0;
    }

    .nav-absensi .nav-label {
        font-size: 0.65rem;
        margin-top: 0.2rem;
        font-weight: 700;
        letter-spacing: 1px;
    }
</style>

<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex flex-column">
                                <div class="font-weight-bold"><?= $title; ?></div>
                                <div>
                                    <b><?= $user['name'];?></b><br>
                                    <b><?= isset($lokasi['lokasi']) ? $lokasi['lokasi'] : 'Lokasi tidak ditemukan'; ?></b><br>
                                    <small><?= tanggalIndo(date("Y-m-d"));?> | <span id="jam"></span>
                                    </small>
                                </div>
                                <?php 
                                    $now = date('H:i:s');
                                    $time_absen = null;
                                    $time_pulang = null;
                                    if($jadwal != null && isset($lokasi['lokasi'])):
                                        $jam_masuk = strtotime($lokasi['jam_masuk']);
                                        $jam_keluar = strtotime($lokasi['jam_keluar']);
                                        $time_absen =  date('H:i:s', strtotime('-'.$lokasi['sebelum_masuk'].'minutes', $jam_masuk));
                                        $time_pulang =  date('H:i:s', $jam_keluar);
                                    ?>
                                <?php if($izin != null) {?>
                                <div class="text-center">
                                    <small>Maaf Anda pada hari ini tidak bisa absen karena telah mengajukan
                                        <?= $izin['status'];?>
                                    </small>
                                </div>
                                <?php } else {?>
                                <div class="text-center">
                                    <small>Waktu Absensi masuk anda dari jam <?= $time_absen;?> -
                                        <?= $jadwal['jam_masuk'];?>
                                    </small>
                                    <small class="d-block">
                                        Terlambat maximal sampai jam
                                        <?= $time_pulang;?>
                                    </small>
                                </div>
                                <div>
                                    <small>Lebih dari waktu yang ditentukan anda terlambat
                                    </small>
                                </div>
                                <?php };?>
                                <?php else:?>
                                <div>
                                    <small>Maaf pada <?= tanggalIndo(date('Y-m-d'));?> anda tidak ada jadwal
                                    </small>
                                </div>
                                <?php endif;?>
                            </div>
                            <div class="card-body">
                                <?php if($jadwal != null):?>
                                <?php if($now >= $time_absen && $now <= $time_pulang):?>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div id="my_camera">
                                        </div>
                                        <div class="my-2">
                                            <button type="button" class="btn btn-success btn-block" id="TakeCapture">
                                                <i class="fas fa-camera mr-2"></i><?= $title;?>
                                            </button>
                                            <input type="hidden" name="lokasi" id="lokasi" class="form-control mt-2">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div id="map" style="height: 500px; width: 100%;"></div>
                                    </div>
                                </div>
                                <?php endif;?>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="<?= base_url();?>assets/js/absen.js"></script>
<script>
<?php if($jadwal != null): ?>
<?php if($now >= $time_absen && $now <= $time_pulang):?>
Webcam.set({
    width: 420,
    height: 420,
    image_format: 'jpeg',
    jpeg_quality: 90,
});
Webcam.attach('#my_camera');

// if (navigator.geolocation) {
//     console.log(navigator.geolocation.watchPosition(showPosition));
//     navigator.geolocation.watchPosition(showPosition);
// } else {
//     alert("Geolocation is not supported by this browser.");
// }

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        function(position) {
            var x = document.getElementById("lokasi");
            x.value = position.coords.latitude + ", " + position.coords.longitude;
            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            alert("📍 Lokasi Anda:\nLatitude: " + lat + "\nLongitude: " + lng);

            // contoh menampilkan di peta:
            // L.marker([lat, lng]).addTo(map)
            //   .bindPopup("📍 Lokasi Anda").openPopup();

            // map.setView([lat, lng], 15);
            
            var map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
        
            L.marker([lat, lng]).addTo(map)
                .bindPopup("📍 Lokasi Anda").openPopup();
        
            let str = "<?= $lokasi['lokasi'];?>";
            let lokasi = str.replace(/\s/g, "");
            var kantor = lokasi;
            L.marker([<?= $lokasi['lat'];?>, <?= $lokasi['long'];?>]).addTo(map)
                .bindPopup(kantor).openPopup();
        
            var circle = L.circle([<?= $lokasi['lat'];?>, <?= $lokasi['long'];?>], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: <?= $lokasi['radius'];?>
            }).addTo(map);
        },
        function(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("❌ Akses lokasi ditolak.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("❌ Lokasi tidak tersedia.");
                    break;
                case error.TIMEOUT:
                    alert("❌ Waktu permintaan lokasi habis.");
                    break;
                default:
                    alert("❌ Terjadi kesalahan.");
            }
        }
    );
} else {
    alert("❌ Geolocation tidak didukung di browser ini.");
}
// function showPosition(position) {
//     console.log(position);
//     var x = document.getElementById("lokasi");
//     x.value = position.coords.latitude + ", " + position.coords.longitude;
    
//     console.log(x.value);
//     console.log(position.coords.longitude);
//     var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 16);

//     L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
//         attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
//     }).addTo(map);

//     L.marker([position.coords.latitude, position.coords.longitude]).addTo(map)
//         .bindPopup('Anda di sini').openPopup();

//     let str = "<?= $lokasi['lokasi'];?>";
//     let lokasi = str.replace(/\s/g, "");
//     var kantor = lokasi;
//     L.marker([<?= $lokasi['lat'];?>, <?= $lokasi['long'];?>]).addTo(map)
//         .bindPopup(kantor).openPopup();

//     var circle = L.circle([<?= $lokasi['lat'];?>, <?= $lokasi['long'];?>], {
//         color: 'red',
//         fillColor: '#f03',
//         fillOpacity: 0.5,
//         radius: <?= $lokasi['radius'];?>
//     }).addTo(map);
// }

var successSound = new Audio('/assets/sound/masuk.mp3');
var errorSound = new Audio('/assets/sound/gagal.mp3');
var errorSound2 = new Audio('/assets/sound/errorlokasi.mp3');
var errorSound3 = new Audio('/assets/sound/errorlokasi2.mp3');


$('#TakeCapture').on('click', function(e) {
    Webcam.snap(function(data_uri) {
        image = data_uri;
        lokasi = $('#lokasi').val();
        
        console.log('ini lokasi', lokasi);
        $.ajax({
            type: 'POST',
            url: '<?= base_url('absensi/masuk');?>',
            data: {
                image,
                lokasi,
            },
            success: function(response) {
                var status = response.split("|");
                if (status[0] == 'success') {
                    successSound.play();
                    $('#TakeCapture').prop('disabled', true);
                    swal({
                        title: "Berhasil",
                        text: status[1],
                        icon: "success"
                    }).then(() => {
                        window.location.href = "/home";
                    });
                } else if (status[0] == 'error1') {
                    errorSound2.play();
                    swal({
                        title: "Gagal",
                        text: status[1],
                        icon: "error"
                    })
                } else if (status[0] == 'error2') {
                    errorSound3.play();
                    swal({
                        title: "Gagal",
                        text: status[1],
                        icon: "error"
                    })
                }
            },
            error: function(xhr, status, error) {
                errorSound.play();
                console.error(xhr.responseText);
                swal("Gagal", "Maaf Gagal Absensi, Hubungi Operator", "error");
            }
        });
    });
});
<?php endif;?>
<?php endif;?>
</script>
<div class="bottom-nav">
    <a href="<?= base_url('home');?>" class="nav-item">
        <div class="nav-icon"><i class="fas fa-home"></i></div>
        <div class="nav-label">Home</div>
    </a>
    <a href="<?= base_url('profil');?>" class="nav-item">
        <div class="nav-icon"><i class="fas fa-user"></i></div>
        <div class="nav-label">Profil</div>
    </a>
    
    <a href="<?= base_url('absensi');?>" class="nav-item nav-absensi" title="Absensi Sekarang">
        <div class="nav-icon"><i class="fas fa-camera"></i></div>
        <div class="nav-label">Absensi</div>
    </a>
    
    <a href="<?= base_url('izin');?>" class="nav-item">
        <div class="nav-icon"><i class="fas fa-file-alt"></i></div>
        <div class="nav-label">Izin</div>
    </a>
    <a href="<?= base_url('riwayat');?>" class="nav-item">
        <div class="nav-icon"><i class="fas fa-history"></i></div>
        <div class="nav-label">Riwayat</div>
    </a>
</div>
<?= $this->endSection('content') ?>