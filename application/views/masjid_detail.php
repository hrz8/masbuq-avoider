<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $countingdown = $masjid['waktu_mulai_iqamah'] !== '00:00:00';
    $lewat_subuh = ($masjid['azan_selanjutnya'] == 'SUBUH') || ($masjid['azan_selanjutnya'] == 'ASHAR') || ($masjid['azan_selanjutnya'] == 'MAGHRIB') || ($masjid['azan_selanjutnya'] == 'ISYA');
    $lewat_dzuhur = ($masjid['azan_selanjutnya'] == 'SUBUH') || ($masjid['azan_selanjutnya'] == 'MAGHRIB') || ($masjid['azan_selanjutnya'] == 'ISYA');
    $lewat_ashar = ($masjid['azan_selanjutnya'] == 'SUBUH') || ($masjid['azan_selanjutnya'] == 'ISYA');
    $lewat_maghrib = ($masjid['azan_selanjutnya'] == 'SUBUH');
    $from_nearby = (isset($_GET['from']) && $_GET['from'] == 'nearby');
    $back_url = ($from_nearby) ? 'masjid/nearby' : '';
?>

        <div class="container">
            <div class="row mt-3 justify-content-center">
                <div class="col-md-5">
                    <h1 class="text-center"><a href="<?= base_url($back_url) ?>" style="text-decoration: none;display: inline-block;padding: 1px 18px;border-radius: 50%;background-color: #f1f1f1;color: black;">&#8249;</a><?= $masjid['nama']; ?></h1> 
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center <?= ($masjid['azan_selanjutnya'] == 'DZUHUR') ? 'active' : '' ?>">
                        <span class="<?= ($lewat_subuh) ? 'text-muted' : '' ?>">Subuh</span>
                        <span class="badge badge-<?= ($lewat_subuh) ? 'secondary' : 'success' ?> badge-pill"><?= $masjid['subuh']; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center <?= ($masjid['azan_selanjutnya'] == 'ASHAR') ? 'active' : '' ?>">
                        <span class="<?= ($lewat_dzuhur) ? 'text-muted' : '' ?>">Dzuhur</span>
                        <span class="badge badge-<?= ($lewat_dzuhur) ? 'secondary' : 'success' ?> badge-pill"><?= $masjid['dzuhur']; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center <?= ($masjid['azan_selanjutnya'] == 'MAGHRIB') ? 'active' : '' ?>">
                        <span class="<?= ($lewat_ashar) ? 'text-muted' : '' ?>">Ashar</span>
                        <span class="badge badge-<?= ($lewat_ashar) ? 'secondary' : 'success' ?> badge-pill"><?= $masjid['ashar']; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center <?= ($masjid['azan_selanjutnya'] == 'ISYA') ? 'active' : '' ?>">
                        <span class="<?= ($lewat_maghrib) ? 'text-muted' : '' ?>">Maghrib</span>
                        <span class="badge badge-<?= ($lewat_maghrib) ? 'secondary' : 'success' ?> badge-pill"><?= $masjid['maghrib']; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center <?= ($masjid['azan_selanjutnya'] == 'SUBUH') ? 'active' : '' ?>">
                        Isya
                        <span class="badge badge-success badge-pill"><?= $masjid['isya']; ?></span>
                        </li>
                    </ul>
                    <div class="text-center">
                        <?php if ($countingdown): ?>
                        <p class="mt-3 h1">Menuju Iqamah</p>
                        <p id="process" class="h1" style="color:#0AC703;">00:00</p>
                        <hr>
                        <div style="font-family: 'Lalezar', cursive;" >
                            <p class="mt-3 h1">إِنَّ الدُّعَاءَ لاَ يُرَدُّ بَيْنَ الأَذَانِ وَالإِقَامَةِ فَادْعُوا</p>
                        </div>
                        <p>Sesungguhnya do’a diantara adzan dan iqomah tidak tertolak, maka pergunakanlah untuk berdo’a.” (HR. Ahmad).</p>
                        <?php endif; ?>
                        <a href="https://www.google.com/maps/place/<?= $masjid['latitude'] . ',' . $masjid['longitude'] ?>" target="_blank" class="btn btn-outline-success mt-3">Buka di Maps</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <?php if ($countingdown): ?>
        <?php
            $start_iqamah_time = $masjid['waktu_mulai_iqamah'];
            $start_time = $masjid['timenow'];
            $end_time = date('H:i:s', strtotime('+' . $masjid['jarak_azan_iqamah'] . ' minutes', strtotime($start_iqamah_time)));
        ?>
        <script>
            var process = document.getElementById('process');
            var x = 0;
            setInterval(function () {
                var end = <?= strtotime($end_time) * 1000;?>-x;
                x+=1000;
                var current = <?= strtotime($start_time) * 1000;?>;
                var seconds_left = ((end - current) / 1000) - 3;
                days = parseInt(seconds_left / 86400);
                seconds_left = seconds_left % 86400;
                hours = parseInt(seconds_left / 3600);
                seconds_left = seconds_left % 3600;
                minutes = parseInt(seconds_left / 60);
                seconds = parseInt(seconds_left % 60);
                if (minutes >= 0 && seconds >= 0) {
                    if (minutes < 10) {
                        minutes = "0"+minutes;
                    }
                    if (seconds < 10) {
                        seconds = "0"+seconds;
                    }
                    var time = minutes + ":" + seconds;
                    process.innerHTML = time;   
                }
            }, 1000);
        </script>
        <?php endif; ?>
    </body>
</html>
