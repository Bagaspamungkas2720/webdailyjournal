<?php
include "koneksi.php"; 

// Fungsi untuk mengupload gambar
function upload_gambar($file) {
    $nama_file = $_FILES['gambar']['name'];
    $ukuran_file = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmp_name = $_FILES['gambar']['tmp_name'];

    // Cek apakah ada file yang diupload
    if ($nama_file == '') {
        return ['status' => false, 'message' => 'Tidak ada file yang diunggah'];
    }

    // Cek ukuran file maksimal (sesuaikan dengan kebutuhan)
    if ($ukuran_file > 2097152) {
        return ['status' => false, 'message' => 'Ukuran file terlalu besar'];
    }

    // Cek tipe file yang diunggah (sesuaikan dengan tipe yang diinginkan)
    $ekstensi_valid = ['jpg', 'jpeg', 'png'];
    $ekstensi_file = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    if (!in_array($ekstensi_file, $ekstensi_valid)) {
        return ['status' => false, 'message' => 'Ekstensi file tidak valid. Hanya diperbolehkan JPG, JPEG, atau PNG.'];
    }

    // Generate nama file baru untuk menghindari duplikasi
    $nama_file_baru = uniqid() . '.' . $ekstensi_file;
    $tujuan = 'img/' . $nama_file_baru;

    // Pindahkan file ke direktori tujuan
    if (move_uploaded_file($tmp_name, $tujuan)) {
        return ['status' => true, 'message' => $nama_file_baru];
    } else {
        return ['status' => false, 'message' => 'Upload gambar gagal'];
    }
}

// Fungsi untuk menghapus gambar
function hapus_gambar($file) {
    if (file_exists("img/" . $file)) {
        unlink("img/" . $file);
    }
}

// Handle tambah data galeri
if (isset($_POST['simpan_gallery'])) {
    $judul_gambar = $_POST['judul_gambar'];
    $deskripsi = $_POST['deskripsi'];
    $nama_gambar = $_FILES['gambar']['name'];

    if ($nama_gambar != '') {
        $cek_upload = upload_gambar($_FILES["gambar"]);

        if ($cek_upload['status']) {
            $gambar = $cek_upload['message'];
        } else {
            echo "<script>
                    alert('" . $cek_upload['message'] . "');
                    document.location='admin.php?page=gallery'; 
                </script>";
            die;
        }
    } else {
        $gambar = $_POST['gambar_lama'];
    }

    $stmt = $conn->prepare("INSERT INTO gallery (judul_gambar, deskripsi, nama_gambar) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $judul_gambar, $deskripsi, $gambar);
    $simpan = $stmt->execute();

    if ($simpan) {
        echo "<script>
                alert('Simpan data sukses');
                document.location='admin.php?page=gallery';
            </script>";
    } else {
        echo "<script>
                alert('Simpan data gagal');
                document.location='admin.php?page=gallery';
            </script>";
    }

    $stmt->close();
}

// Handle edit data galeri
if (isset($_POST['edit_gallery'])) {
    $id = $_POST['id'];
    $judul_gambar = $_POST['judul_gambar'];
    $deskripsi = $_POST['deskripsi'];
    $gambar_lama = $_POST['gambar_lama'];
    $nama_gambar = $_FILES['gambar']['name'];

    if ($nama_gambar != '') {
        $cek_upload = upload_gambar($_FILES["gambar"]);

        if ($cek_upload['status']) {
            $gambar = $cek_upload['message'];
            unlink("img/" . $gambar_lama); // Hapus gambar lama
        } else {
            echo "<script>
                    alert('" . $cek_upload['message'] . "');
                    document.location='admin.php?page=gallery'; 
                </script>";
            die;
        }
    } else {
        $gambar = $gambar_lama;
    }

    $stmt = $conn->prepare("UPDATE gallery SET judul_gambar = ?, deskripsi = ?, nama_gambar = ? WHERE id = ?");
    $stmt->bind_param("sssi", $judul_gambar, $deskripsi, $gambar, $id);
    $simpan = $stmt->execute();

    if ($simpan) {
        echo "<script>
                alert('Simpan data sukses');
                document.location='admin.php?page=gallery';
            </script>";
    } else {
        echo "<script>
                alert('Simpan data gagal');
                document.location='admin.php?page=gallery';
            </script>";
    }

    $stmt->close();
}

// Handle hapus data galeri
if (isset($_POST['hapus_gallery'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];

    if ($gambar != '') {
        unlink("img/" . $gambar);
    }

    $stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $id);
    $hapus = $stmt->execute();

    if ($hapus) {
        echo "<script>
                alert('Hapus data sukses');
                document.location='admin.php?page=gallery';
            </script>";
    } else {
        echo "<script>
                alert('Hapus data gagal');
                document.location='admin.php?page=gallery';
            </script>";
    }

    $stmt->close();
}



// Tampilkan data galeri
?>

<!DOCTYPE html>
<html>
<head>
    <title>Galeri</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalTambahGallery">
            Tambah Gambar
        </button>

        <div class="row">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM gallery";
                        $hasil = $conn->query($sql);

                        $no = 1;
                        while ($row = $hasil->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <img src="img/<?= $row['nama_gambar'] ?>" alt="<?= $row['judul_gambar'] ?>" width="100">
                            </td>
                            <td><?= $row['judul_gambar'] ?></td>
                            <td><?= $row['deskripsi'] ?></td>
                            <td>
                                <a href="#" title="edit" class="badge rounded-pill text-bg-success" data-bs-toggle="modal" data-bs-target="#modalEditGallery<?= $row["id"] ?>"><i class="bi bi-pencil"></i></a>
                                <a href="#" title="delete" class="badge rounded-pill text-bg-danger" data-bs-toggle="modal" data-bs-target="#modalHapusGallery<?= $row["id"] ?>"><i class="bi bi-x-circle"></i></a>
                                </td>
                        </tr>

                        
        <div class="modal fade" id="modalEditGallery<?= $row['id'] ?>" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="mb-3">
                        <label for="judul_gambar" class="form-label">Judul Gambar</label>
                        <input type="text" class="form-control" id="judul_gambar" name="judul_gambar" value="<?= $row['judul_gambar'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi"><?= $row['deskripsi'] ?></textarea>
                    </div>
                    <div class="mb-3">
                                <label for="gambar" class="form-label">Pilih Gambar</label>
                                <input type="file" class="form-control" id="gambar" name="gambar" required>
                            </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit_gallery" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modalHapusGallery<?= $row['id'] ?>" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus gambar <strong><?= $row['judul_gambar'] ?></strong>?
            </div>
            <div class="modal-footer">
                <form method="post" action="">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="gambar" value="<?= $row['nama_gambar'] ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="hapus_gallery" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        </div>

        <div class="modal fade" id="modalTambahGallery" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Gambar</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="judul_gambar" class="form-label">Judul Gambar</label>
                                <input type="text" class="form-control" id="judul_gambar" name="judul_gambar" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="gambar" class="form-label">Pilih Gambar</label>
                                <input type="file" class="form-control" id="gambar" name="gambar" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="simpan_gallery" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEditGallery<?= $row['id'] ?>" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="mb-3">
                        <label for="judul_gambar" class="form-label">Judul Gambar</label>
                        <input type="text" class="form-control" id="judul_gambar" name="judul_gambar" value="<?= $row['judul_gambar'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi"><?= $row['deskripsi'] ?></textarea>
                    </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit_gallery" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>