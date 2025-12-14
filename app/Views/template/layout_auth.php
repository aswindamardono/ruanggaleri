<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TXK6967J');</script>
 <!-- End Google Tag Manager-->
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= $title;?> | Ruang Galeri</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="<?= base_url()?>assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url()?>assets/modules/fontawesome/css/all.min.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="<?= base_url()?>assets/modules/bootstrap-social/bootstrap-social.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= base_url()?>assets/css/style.css">
    <link rel="stylesheet" href="<?= base_url()?>assets/css/components.css">
    <script src="<?= base_url();?>assets/modules/jquery.min.js"></script>
    <script src="<?= base_url();?>assets/modules/sweetalert/sweetalert.min.js"></script>

    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=GTM-TXK6967J"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'GTM-TXK6967J');
    </script>
    <!-- /END GA -->
</head>

<body>
    <div id="app">
        <section class="section">
            <?= $this->renderSection('content') ?>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="<?= base_url()?>assets/modules/jquery.min.js"></script>
    <script src="<?= base_url()?>assets/modules/popper.js"></script>
    <script src="<?= base_url()?>assets/modules/tooltip.js"></script>
    <script src="<?= base_url()?>assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= base_url()?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="<?= base_url()?>assets/modules/moment.min.js"></script>
    <script src="<?= base_url()?>assets/js/stisla.js"></script>

    <!-- JS Libraies -->

    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="<?= base_url()?>assets/js/scripts.js"></script>
    <script src="<?= base_url()?>assets/js/custom.js"></script>
    <script>
    <?php if(!empty(session()->getFlashdata('pesan'))) : ?>
    swal("Berhasil", "<?= session()->getFlashdata('pesan');?>", "success");
    <?php elseif(!empty(session()->getFlashdata('error'))): ?>
    swal("Gagal", "<?php echo session()->get('error'); ?>", "error");
    <?php endif; ?>
    </script>
</body>

</html>