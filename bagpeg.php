<?php
    //session
    if(empty($_SESSION['admin'])){
        $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else {

        if(isset($_REQUEST['act'])){
            $act = $_REQUEST['act'];
            switch ($act) {
                case 'add':
                    include "tambah_pegawai.php";
                    break;
                case 'edit':
                    include "edit_pegawai.php";
                    break;
                case 'del':
                    include "hapus_pegawai.php";
                    break;
            }
        } else {

            //pagging
            $limit = 5;
            $pg = @$_GET['pg'];
                if(empty($pg)){
                    $curr = 0;
                    $pg = 1;
                } else {
                    $curr = ($pg - 1) * $limit;
                }

                $query = mysqli_query($config, "SELECT * FROM tbl_bag_pegawai LIMIT $curr, $limit");
                echo '<!-- Row Start -->
                    <div class="row">
                        <!-- Secondary Nav START -->
                        <div class="col s12">
                            <div class="z-depth-1">
                                <nav class="secondary-nav">
                                    <div class="nav-wrapper blue-grey darken-1">
                                        <div class="col m12">
                                            <ul class="left">
                                                <li class="waves-effect waves-light hide-on-small-only"><a href="?page=sett&sub=bagpeg" class="judul"><i class="material-icons">people</i> Manajemen Bagian Pegawai</a></li>
                                                <li class="waves-effect waves-light">
                                                    <a href="?page=sett&sub=bagpeg&act=add"><i class="material-icons md-24">person_add</i> Tambah Data</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <!-- Secondary Nav END -->
                    </div>
                    <!-- Row END -->';

                    if(isset($_SESSION['succAdd'])){
                        $succAdd = $_SESSION['succAdd'];
                        echo '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card green lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title green-text"><i class="material-icons md-36">done</i> '.$succAdd.'</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        unset($_SESSION['succAdd']);
                    }
                    if(isset($_SESSION['succEdit'])){
                        $succEdit = $_SESSION['succEdit'];
                        echo '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card green lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title green-text"><i class="material-icons md-36">done</i> '.$succEdit.'</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        unset($_SESSION['succEdit']);
                    }
                    if(isset($_SESSION['succDel'])){
                        $succDel = $_SESSION['succDel'];
                        echo '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card green lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title green-text"><i class="material-icons md-36">done</i> '.$succDel.'</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        unset($_SESSION['succDel']);
                    }

                echo '
                    <!-- Row form Start -->
                    <div class="row jarak-form">

                        <div class="col m12" id="colres">
                            <!-- Table START -->
                            <table class="bordered" id="tbl">
                                <thead class="blue lighten-4" id="head">
                                    <tr>
                                        <th width="8%">No</th>
                                        <th width="40%">Kode<br/>Nama Bagian</th>
                                        <th width="35%">Nama<br/>NIP</th>
                                        <th width="16%">Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>';

                                    //script untuk menampilkan data
                                    $query = mysqli_query($config, "SELECT * FROM tbl_bag_pegawai ORDER BY id_bagian DESC LIMIT $curr, $limit");
                                    if(mysqli_num_rows($query) > 0){
                                        $no = 1;
                                        while($row = mysqli_fetch_array($query)){
                                          echo '<td>'.$no++.'</td>
                                                <td>'.$row['kode_bagian'].'<br/>'.$row['nama_bagian'].'</td>
                                                <td>'.$row['nama_pegawai'].'<br/>'.$row['nip_pegawai'].'</td>
                                                <td>';

                                                if($_SESSION['admin'] != 1){
                                                    echo '<a class="btn small blue-grey waves-effect waves-light"><i class="material-icons">error</i> NO ACTION</a>';
                                                } else {
                                                  echo '<a class="btn small blue waves-effect waves-light" href="?page=sett&sub=bagpeg&act=edit&id_bagian='.$row['id_bagian'].'">
                                                            <i class="material-icons">edit</i> EDIT</a>
                                                        <a class="btn small deep-orange waves-effect waves-light" href="?page=sett&sub=bagpeg&act=del&id_bagian='.$row['id_bagian'].'">
                                                            <i class="material-icons">delete</i> DEL</a>';
                                                } echo '
                                                </td>
                                            </tr>
                                        </tbody>';
                                    }
                                } else {
                                    echo '<tr><td colspan="5"><center><p class="add">Tidak ada data yang ditemukan. <u><a href="?page=sett&sub=bagpeg&act=add">Tambah data baru</a></u></p></center></td></tr>';
                                }
                      echo '</table>
                            <!-- Table END -->
                        </div>

                    </div>
                    <!-- Row form END -->';

                    $query = mysqli_query($config, "SELECT * FROM tbl_bag_pegawai");
                    $cdata = mysqli_num_rows($query);
                    $cpg = ceil($cdata/$limit);

                    echo '<!-- Pagination START -->
                          <ul class="pagination">';

                    if($cdata > $limit){

                        if($pg > 1){
                            $prev = $pg - 1;
                            echo '<li><a href="?page=sett&sub=bagpeg&pg=1"><i class="material-icons md-48">first_page</i></a></li>
                                  <li><a href="?page=sett&sub=bagpeg&pg='.$prev.'"><i class="material-icons md-48">chevron_left</i></a></li>';
                        } else {
                            echo '<li class="disabled"><a href=""><i class="material-icons md-48">first_page</i></a></li>
                                  <li class="disabled"><a href=""><i class="material-icons md-48">chevron_left</i></a></li>';
                        }

                        //perulangan pagging
                        for($i=1; $i <= $cpg; $i++)
                            if($i != $pg){
                                echo '<li class="waves-effect waves-dark"><a href="?page=sett&sub=bagpeg&pg='.$i.'"> '.$i.' </a></li>';
                            } else {
                                echo '<li class="active waves-effect waves-dark"><a href="?page=sett&sub=bagpeg&pg='.$i.'"> '.$i.' </a></li>';
                            }

                        //last and next pagging
                        if($pg < $cpg){
                            $next = $pg + 1;
                            echo '<li><a href="?page=sett&sub=bagpeg&pg='.$next.'"><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li><a href="?page=sett&sub=bagpeg&pg='.$cpg.'"><i class="material-icons md-48">last_page</i></a></li>';
                        } else {
                            echo '<li class="disabled"><a href=""><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li class="disabled"><a href=""><i class="material-icons md-48">last_page</i></a></li>';
                        }
                            echo ' </ul>
                                   <!-- Pagination END -->';
                    } else {
                        echo '';
                    }
                }
            }
?>
