<?php
    //cek session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        if(isset($_REQUEST['submit'])){

                $id_bagian = $_REQUEST['id_bagian'];
                $kode_bagian = $_REQUEST['kode_bagian'];
                $nama_bagian = $_REQUEST['nama_bagian'];
                $nama_pegawai = $_REQUEST['nama_pegawai'];
                $nip_pegawai = $_REQUEST['nip_pegawai'];
                $id_user = $_SESSION['hak_akses'];

                //validasi form kosong
                if($_REQUEST['kode_bagian'] == "" || $_REQUEST['nama_bagian'] == "" || $_REQUEST['nama_pegawai'] == "" || $_REQUEST['nip_pegawai'] == ""){
                    $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
                    echo '<script language="javascript">
                            window.location.href="./admin.php?page=sett&sub=bagpeg&act=edit&id_bagian='.$id_bagian.'";
                          </script>';
                } else {

                //validasi input data
                if(!preg_match("/^[a-zA-Z0-9. ]*$/", $kode_bagian)){
                    $_SESSION['kode_bagian'] = 'Form Kode Bagian hanya boleh mengandung karakter huruf, spasi, titik(.)';
                    echo '<script language="javascript">window.history.back();</script>';
                } else {

                    if(!preg_match("/^[a-zA-Z0-9. ]*$/", $nama_bagian)){
                        $_SESSION['nama_bagian'] = 'Form Nama Bagian hanya boleh mengandung karakter huruf, spasi, titik(.), koma(,) dan minus(-)';
                        echo '<script language="javascript">window.history.back();</script>';
                    } else {

                        if(!preg_match("/^[a-zA-Z0-9.,\/ -]*$/", $nama_pegawai)){
                            $_SESSION['nama_pegawai'] = 'Form Nama Pegawai hanya boleh mengandung karakter huruf, spasi, titik(.), koma(,) dan minus(-)';
                            echo '<script language="javascript">window.history.back();</script>';
                        } else {

                            if(!preg_match("/^[a-zA-Z0-9.,()\/\r\n -]*$/", $nip_pegawai)){
                                $_SESSION['nip_pegawai'] = 'Form NIP hanya boleh mengandung angka, spasi, titik(.), dan minus(-)';
                                echo '<script language="javascript">window.history.back();</script>';
                            } else {

                                $query = mysqli_query($config, "UPDATE tbl_bag_pegawai SET kode_bagian='$kode_bagian', nama_bagian='$nama_bagian', nama_pegawai='$nama_pegawai', nip_pegawai='$nip_pegawai', id_user='$id_user' WHERE id_bagian='$id_bagian'");

                                if($query != false){
                                    $_SESSION['succEdit'] = 'SUKSES! Data berhasil diupdate';
                                    header("Location: ./admin.php?page=sett&sub=bagpeg");
                                    die();
                                } else {
                                    $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                    echo '<script language="javascript">window.history.back();</script>';
                                }
                            }
                        }
                    }
                }
            }
        } else {

            $id_bagian = mysqli_real_escape_string($config, $_REQUEST['id_bagian']);
            $query = mysqli_query($config, "SELECT * FROM tbl_bag_pegawai WHERE id_bagian='$id_bagian'");
            if(mysqli_num_rows($query) > 0){
                $no = 1;
                while($row = mysqli_fetch_array($query))
                if($_SESSION['admin'] != 1){
                    echo '<script language="javascript">
                            window.alert("ERROR! Anda tidak memiliki hak akses untuk mengedit data ini");
                            window.location.href="./admin.php?page=sett&sub=bagpeg";
                          </script>';
                } else {?>

                    <!-- Row Start -->
                    <div class="row">
                        <!-- Secondary Nav START -->
                        <div class="col s12">
                            <nav class="secondary-nav">
                                <div class="nav-wrapper blue-grey darken-1">
                                    <ul class="left">
                                        <li class="waves-effect waves-light"><a href="#" class="judul"><i class="material-icons">edit</i> Edit Bagian Pegawai</a></li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <!-- Secondary Nav END -->
                    </div>
                    <!-- Row END -->

                    <?php
                        if(isset($_SESSION['errEmpty'])){
                            $errEmpty = $_SESSION['errEmpty'];
                            echo '<div id="alert-message" class="row">
                                    <div class="col m12">
                                        <div class="card red lighten-5">
                                            <div class="card-content notif">
                                                <span class="card-title red-text"><i class="material-icons md-36">clear</i> '.$errEmpty.'</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                            unset($_SESSION['errEmpty']);
                        }
                        if(isset($_SESSION['errQ'])){
                            $errQ = $_SESSION['errQ'];
                            echo '<div id="alert-message" class="row">
                                    <div class="col m12">
                                        <div class="card red lighten-5">
                                            <div class="card-content notif">
                                                <span class="card-title red-text"><i class="material-icons md-36">clear</i> '.$errQ.'</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                            unset($_SESSION['errQ']);
                        }
                    ?>

                    <!-- Row form Start -->
                    <div class="row jarak-form">

                        <!-- Form START -->
                        <form class="col s12" method="post" action="?page=sett&sub=bagpeg&act=edit">

                            <!-- Row in form START -->
                            <div class="row">
                                <div class="input-field col s6 tooltipped" data-position="top" data-tooltip="Isi dengan huruf, angka, spasi dan titik(.)">
                                    <input type="hidden" value="<?php echo $row['id_bagian']; ?>" name="id_bagian">
                                    <i class="material-icons prefix md-prefix">font_download</i>
                                    <input id="kode_bagian" type="text" class="validate" name="kode_bagian" value="<?php echo $row['kode_bagian']; ?>" required>
                                    <?php
                                        if(isset($_SESSION['kode_bagian'])){
                                            $kode_bagian = $_SESSION['kode_bagian'];
                                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$kode_bagian.'</div>';
                                            unset($_SESSION['kode_bagian']);
                                        }
                                    ?>
                                    <label for="kode_bagian">Kode Bagian</label>
                                </div>
                                <div class="input-field col s6">
                                    <i class="material-icons prefix md-prefix">text_fields</i>
                                    <input id="nama_bagian" type="text" class="validate" maxlength="100" name="nama_bagian" value="<?php echo $row['nama_bagian']; ?>" required>
                                        <?php
                                            if(isset($_SESSION['nama_bagian'])){
                                                $nama_bagian = $_SESSION['nama_bagian'];
                                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$nama_bagian.'</div>';
                                                unset($_SESSION['nama_bagian']);
                                            }
                                        ?>
                                    <label for="nama_bagian">Nama Bagian</label>
                                </div>
                                <div class="input-field col s6">
                                    <i class="material-icons prefix md-prefix">text_fields</i>
                                    <input id="nama_pegawai" type="text" class="validate" name="nama_pegawai" value="<?php echo $row['nama_pegawai']; ?>" required>
                                        <?php
                                            if(isset($_SESSION['nama_pegawai'])){
                                                $namaref = $_SESSION['nama_pegawai'];
                                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$nama_pegawai.'</div>';
                                                unset($_SESSION['nama_pegawai']);
                                            }
                                        ?>
                                    <label for="nama_pegawai">Nama Pegawai</label>
                                </div>
                                <div class="input-field col s6">
                                    <i class="material-icons prefix md-prefix">subject</i>
                                    <input id="nip_pegawai" type="text" class="validate" name="nip_pegawai" value="<?php echo $row['nip_pegawai']; ?>" required>
                                        <?php
                                            if(isset($_SESSION['nip_pegawai'])){
                                                $uraian = $_SESSION['nip_pegawai'];
                                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">'.$nip_pegawai.'</div>';
                                                unset($_SESSION['nip_pegawai']);
                                            }
                                        ?>
                                    <label for="nip_pegawai">NIP</label>
                                </div>
                            </div>
                            <!-- Row in form END -->
                            <div class="row">
                                <div class="col 6">
                                    <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i class="material-icons">done</i></button>
                                </div>
                                <div class="col 6">
                                    <a href="?page=sett&sub=bagpeg" class="btn-large deep-orange waves-effect waves-light">BATAL <i class="material-icons">clear</i></a>
                                </div>
                            </div>

                        </form>
                        <!-- Form END -->

                    </div>
                    <!-- Row form END -->

<?php
                }
            }
        }
    }
?>
