<?php
require 'koneksi.php';
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

if (isset($_POST['add_category'])) {
    $name = $_POST['name'];
    $conn->query("INSERT INTO categories (name) VALUES ('$name')");
    header("Location: index.php?page=kategori");
}
if (isset($_GET['del_category'])) {
    $id = $_GET['del_category'];
    $conn->query("DELETE FROM categories WHERE id=$id");
    header("Location: index.php?page=kategori");
}

// Produk
if (isset($_POST['add_product'])) {
    $cat_id = $_POST['category_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $conn->query("INSERT INTO products (category_id, name, price) VALUES ('$cat_id', '$name', '$price')");
    header("Location: index.php?page=produk");
}
if (isset($_GET['del_product'])) {
    $id = $_GET['del_product'];
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: index.php?page=produk");
}

// Transaksi
if (isset($_POST['add_transaction'])) {
    $prod_id = $_POST['product_id'];
    $qty = $_POST['qty'];
    
    // Ambil harga produk untuk hitung total
    $prod_query = $conn->query("SELECT price FROM products WHERE id=$prod_id");
    $prod = $prod_query->fetch_assoc();
    $total_price = $prod['price'] * $qty;

    $conn->query("INSERT INTO transactions (product_id, qty, total_price) VALUES ('$prod_id', '$qty', '$total_price')");
    header("Location: index.php?page=transaksi");
}
if (isset($_GET['del_transaction'])) {
    $id = $_GET['del_transaction'];
    $conn->query("DELETE FROM transactions WHERE id=$id");
    header("Location: index.php?page=transaksi");
}

if (isset($_GET['clear_transactions'])) {
    // Menghapus semua data dari tabel transaksi
    $conn->query("TRUNCATE TABLE transactions"); 
    header("Location: index.php?page=transaksi");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rusyad Coffee - Manajemen App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --coffee-dark: #3e2723;
            --coffee-medium: #5d4037;
            --coffee-light: #d7ccc8;
            --coffee-cream: #efebe9;
        }
        /* body { background-color: var(--coffee-cream); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; } */
        body { 
            background-color: var(--coffee-cream); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            }
        .navbar { background-color: var(--coffee-dark) !important; }
        .navbar-brand, .nav-link { color: var(--coffee-light) !important; }
        .nav-link:hover { color: white !important; }
        .card { border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 12px; }
        .card-header { background-color: var(--coffee-medium); color: white; border-radius: 12px 12px 0 0 !important; }
        .btn-coffee { background-color: var(--coffee-dark); color: white; }
        .btn-coffee:hover { background-color: #2b1b18; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">☕ Rusyad Coffee</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=kategori">Kategori</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=produk">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=transaksi">Transaksi</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <?php if($page == 'dashboard'): ?>
        <div class="row text-center mt-5">
            <div class="col-md-12 mb-4">
                <h1 class="fw-bold" style="color: var(--coffee-dark);">Selamat Datang di Rusyad Coffee</h1>
                <p class="lead">Sistem Manajemen Point of Sales (POS)</p>
            </div>
            <div class="col-md-4">
                <div class="card p-4">
                    <h3>Kategori</h3>
                    <a href="index.php?page=kategori" class="btn btn-coffee mt-2">Kelola Kategori</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4">
                    <h3>Produk</h3>
                    <a href="index.php?page=produk" class="btn btn-coffee mt-2">Kelola Produk</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4">
                    <h3>Transaksi</h3>
                    <a href="index.php?page=transaksi" class="btn btn-coffee mt-2">Kasir / Transaksi</a>
                </div>
            </div>
        </div>

    <?php elseif($page == 'kategori'): ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kelola Kategori</h5>
            </div>
            <div class="card-body">
                <form action="index.php?page=kategori" method="POST" class="mb-4 d-flex gap-2">
                    <input type="text" name="name" class="form-control" placeholder="Nama Kategori Baru" required>
                    <button type="submit" name="add_category" class="btn btn-coffee">Tambah</button>
                </form>
                <table class="table table-hover">
                    <thead><tr><th>ID</th><th>Nama Kategori</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php 
                        $res = $conn->query("SELECT * FROM categories");
                        while($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td>
                                <a href="index.php?del_category=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kategori ini? (Produk terkait juga akan terhapus)')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php elseif($page == 'produk'): ?>
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Kelola Produk</h5></div>
            <div class="card-body">
                <form action="index.php?page=produk" method="POST" class="mb-4 row g-2">
                    <div class="col-md-3">
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php 
                            $cats = $conn->query("SELECT * FROM categories");
                            while($c = $cats->fetch_assoc()) echo "<option value='{$c['id']}'>{$c['name']}</option>";
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control" placeholder="Nama Produk" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="price" class="form-control" placeholder="Harga (Rp)" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="add_product" class="btn btn-coffee w-100">Tambah</button>
                    </div>
                </form>
                <table class="table table-hover">
                    <thead><tr><th>ID</th><th>Kategori</th><th>Nama Produk</th><th>Harga</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php 
                        $res = $conn->query("SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.id");
                        while($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><span class="badge bg-secondary"><?= $row['cat_name'] ?></span></td>
                            <td><?= $row['name'] ?></td>
                            <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                            <td>
                                <a href="index.php?del_product=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php elseif($page == 'transaksi'): ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kasir / Pencatatan Transaksi</h5>
            </div>
            <div class="card-body">
                <form action="index.php?page=transaksi" method="POST" class="mb-4 row g-2 border-bottom pb-4">
                    <div class="col-md-6">
                        <select name="product_id" class="form-select" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php 
                            $prods = $conn->query("SELECT * FROM products");
                            while($p = $prods->fetch_assoc()) {
                                echo "<option value='{$p['id']}'>{$p['name']} - Rp " . number_format($p['price'], 0, ',', '.') . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="qty" class="form-control" placeholder="Jumlah (Qty)" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="add_transaction" class="btn btn-coffee w-100">Tambah ke Pesanan</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $grand_total = 0; // Inisialisasi variabel Total Order
                            $res = $conn->query("SELECT t.*, p.name as prod_name FROM transactions t JOIN products p ON t.product_id = p.id ORDER BY t.transaction_date DESC");
                            
                            if ($res->num_rows > 0):
                                while($row = $res->fetch_assoc()): 
                                    // Tambahkan subtotal ke grand total
                                    $grand_total += $row['total_price'];
                            ?>
                            <tr>
                                <td>#<?= $row['id'] ?></td>
                                <td><?= date('d M Y, H:i', strtotime($row['transaction_date'])) ?></td>
                                <td><?= $row['prod_name'] ?></td>
                                <td><?= $row['qty'] ?></td>
                                <td class="fw-bold text-success">Rp <?= number_format($row['total_price'], 0, ',', '.') ?></td>
                                <td>
                                    <a href="index.php?del_transaction=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Batalkan pesanan ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            else:
                            ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Belum ada transaksi hari ini.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        
                        <?php if ($grand_total > 0): ?>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end fs-5">Total Order Keseluruhan:</th>
                                <th class="fs-5 text-success fw-bold">Rp <?= number_format($grand_total, 0, ',', '.') ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>

                <?php if ($grand_total > 0): ?>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="index.php?page=transaksi&clear_transactions=1" 
                       class="btn btn-outline-secondary btn-lg" 
                       onclick="return confirm('Apakah Anda yakin ingin memulai orderan baru? Semua pesanan saat ini akan dihapus!')">
                        🔄 Orderan Baru
                    </a>
                    
                    <button class="btn btn-success btn-lg" onclick="alert('Pesanan berhasil diselesaikan dengan total Rp <?= number_format($grand_total, 0, ',', '.') ?>!')">
                        💵 Selesaikan Pesanan
                    </button>
                </div>
                <?php endif; ?>

            </div>
        </div>
    <?php endif; ?>
</div>

<!-- FOOTER SECTION -->
<footer class="mt-auto py-4 text-center mt-5" style="background-color: var(--coffee-dark); color: var(--coffee-light);">
    <div class="container">
        <div class="row text-start text-md-center">
            <!-- Kolom 1: Deskripsi -->
            <div class="col-md-4 mb-3 mb-md-0 text-md-start">
                <h5 class="fw-bold text-white">☕ Rusyad Coffee</h5>
                <p class="small mb-0" style="color: var(--coffee-light);">Sistem Manajemen Point of Sales (POS) berbasis web. Memudahkan pencatatan transaksi dengan cepat dan akurat.</p>
            </div>
            
            <!-- Kolom 2: Navigasi Cepat -->
            <div class="col-md-4 mb-3 mb-md-0">
                <h5 class="fw-bold text-white">Navigasi</h5>
                <ul class="list-unstyled small mb-0 d-flex flex-column gap-1">
                    <li><a href="index.php?page=dashboard" class="text-decoration-none" style="color: var(--coffee-light);">&rarr; Beranda</a></li>
                    <li><a href="index.php?page=kategori" class="text-decoration-none" style="color: var(--coffee-light);">&rarr; Kelola Kategori</a></li>
                    <li><a href="index.php?page=produk" class="text-decoration-none" style="color: var(--coffee-light);">&rarr; Kelola Produk</a></li>
                </ul>
            </div>
            
            <!-- Kolom 3: Kontak -->
            <div class="col-md-4 text-md-end">
                <h5 class="fw-bold text-white">Hubungi Kami</h5>
                <p class="small mb-0" style="color: var(--coffee-light);">
                    📍 Jl. Kopi Nusantara No. 123<br>
                    📞 0812-3456-7890<br>
                    ✉️ hello@rusyadcoffee.com
                </p>
            </div>
        </div>
        
        <!-- Garis Pembatas -->
        <hr class="mt-4 mb-3" style="border-color: var(--coffee-medium); opacity: 0.5;">
        
        <!-- Copyright Section -->
        <div class="small" style="color: var(--coffee-light);">
            &copy; <?= date("Y"); ?> <strong>Rusyad Coffee</strong>. Dibuat dengan 🤎 untuk untuk nilai UAS 100.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>