<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

        <div class="container">
            <div class="row mt-3 justify-content-center">
                <div class="col-md-8">
                    <h1 class="text-center">Nearby Masjid <small>(3KM Range)</small></h1>
                    <div class="text-center">
                        <a href="<?= base_url() ?>masjid/nearby/" class="btn btn-outline-danger mt-3 mb">Refresh</a>
                    </div>
                    <div id="lokasi-user" class="text-center"></div>
                    <div id="list-masjid" class="list-group"></div>
                    <div id="not-found-or-error"></div>
                </div>
            </div>
        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script>
            let base_url = "<?= base_url()?>";
            let api_url = base_url + "api/masjid/";
            let jarak_max = 3.00;

            let userLat;
            let userLong;

            let error;
            
            function setLatLong(position) {
                let urlMaps = 'https://www.google.com/maps/place/'+position.coords.latitude+','+position.coords.longitude;
                $('#lokasi-user').html(`
                    <a href="` + urlMaps + `" target="_blank" class="btn btn-outline-success mt-3 mb-3">Lokasi Anda</a>
                    <button class="btn btn-outline-primary mt-3 mb-3" onclick="getDataMasjid()">Tampilkan</button>
                `);
                userLat = position.coords.latitude;
                userLong = position.coords.longitude;
            }

            function setError(error) {
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        error = "User tidak mengizinkan membaca lokasi."
                        break;
                    case error.POSITION_UNAVAILABLE:
                        error = "Lokasi tidak tersedia."
                        break;
                    case error.TIMEOUT:
                        error = "Time out request."
                        break;
                    case error.UNKNOWN_ERROR:
                        error = "Terjadi error."
                        break;
                }
            }

            function deg2rad(deg) {
                return deg * (Math.PI/180)
            }

            function distanceInKM(lat1, lon1, lat2, lon2) {
                let R = 6371;
                let dLat = deg2rad(lat2-lat1);
                let dLon = deg2rad(lon2-lon1); 
                let a = 
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
                    Math.sin(dLon/2) * Math.sin(dLon/2)
                ; 
                let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
                let d = R * c;
                return d;
            }

            function getDataMasjid() {
                $.ajax({ 
                    type: 'GET', 
                    url: api_url,
                    dataType: 'json',
                    success: function(data) {
                        let masjids = data.data;
                        if (userLat && userLong) {
                            let jumlah_masjid = 0;
                            $("#list-masjid").html("");
                            $("#not-found-or-error").html("");
                            $.each(masjids, function(i, masjid) {
                                let jarak = distanceInKM(userLat, userLong, masjid.latitude, masjid.longitude);
                                if (jarak < jarak_max) {
                                    jumlah_masjid++;
                                    $("#list-masjid").append(`
                                        <a href="` + base_url + `masjid/iqamah/` + masjid.id + `?from=nearby" class="list-group-item list-group-item-action flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1">` + masjid.nama + `</h5>
                                            <small>Jarak: ` + jarak.toFixed(2) + ` KM</small>
                                            </div>
                                            <p class="mb-1">` + masjid.alamat + `</p>
                                            <small>Jarak Azan-Iqamah: ` + masjid.jarak_azan_iqamah + ` menit</small>
                                        </a>
                                    `);
                                }
                            });
                            if (jumlah_masjid == 0) {
                                $("#not-found-or-error").html(`
                                    <p class="text-center">Tidak ditemukan masjid berdasarkan lokasi anda. <a href="` + base_url + `">Tampilkan semua</a>.</p>
                                `);
                            }
                        }
                        else {
                            if (error) {
                                $("#not-found-or-error").html(`
                                    <p class="text-center">` + error + ` <a href="` + base_url + `">Tampilkan semua</a>.</p>
                                `);
                            }
                        }
                    }
                });
            }

            $(window).on("load", function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(setLatLong, setError);
                }
                else {
                    $("#not-found-or-error").html(`
                        <p class="text-center">Geolocation tidak support di perangkat ini.</p>
                    `);
                }
                if (typeof Website2APK !== 'undefined') {
                    Website2APK.askEnableGPS();
                }
            });
        </script>
    </body>
</html>
