<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

        <div class="container">
            <div class="row mt-3 justify-content-center">
                <div class="col-md-8">
                    <h1 class="text-center"><?= $h1 ?></h1> 
                    <div class="list-group">
                        <?php if ($masjids):?>
                        <?php foreach($masjids as $masjid): ?>
                            <a href="<?= base_url() . 'masjid/iqamah/' . $masjid['id'] ?>" class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?= $masjid['nama'] ?></h5>
                                <small><?= $masjid['latitude'] ?>, <?= $masjid['longitude'] ?></small>
                                </div>
                                <p class="mb-1"><?= $masjid['alamat'] ?></p>
                                <small>Jarak Azan-Iqamah: <?= $masjid['jarak_azan_iqamah'] ?> menit</small>
                            </a>
                        <?php endforeach ?>
                        <?php else: ?>
                            <h3 class="text-center">Not found</h3> 
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
