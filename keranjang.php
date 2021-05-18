<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "belajarpi");
if (empty($_SESSION["keranjang"]) or !isset($_SESSION["keranjang"])) {
	echo "<script>alert('keranjang kosong, silahkan pesan terlebih dahulu');</script>";
	echo "<script>location='index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>keranjang belanja</title>
	<link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
</head>

<body>
	<!--navbar -->
	<nav class="navbar navbar-default">
		<div class="container">
			<ul class="nav navbar-nav">
				<li><a href="index.php">Home</a></li>
				<li><a href="keranjang.php">Keranjang</a></li>
				<li><a href="checkout.php">Checkout</a></li>
				<li><a href="nota.php">Nota</a></li>
				<!-- Jika sudah login (ada session pelanggan) -->
				<?php if (isset($_SESSION["pelanggan"])) : ?>
					<li><a href="logout.php">Logout</a></li>
					<!-- selain itu (belum login||belum ada session pelanggan) -->
				<?php else : ?>
					<li><a href="login.php">Login</a></li>
				<?php endif ?>
			</ul>
		</div>
	</nav>

	<section class="konten">
		<div class="container">
			<?php if (($_SESSION["keranjang"])) { ?>
				<pre> <?php print_r($_SESSION["keranjang"]); ?></pre>
			<?php } ?>
			<h1>Keranjang Belanja</h1>
			<hr>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>No</th>
						<th>Produk</th>
						<th>Harga</th>
						<th>Jumlah</th>
						<th>Subharga</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php $nomor = 1; ?>
					<?php foreach ($_SESSION["keranjang"] as $id_produk => $jumlah) : ?>
						<?php
						$ambil = $koneksi->query("SELECT * FROM produk WHERE id_produk = '$id_produk'");
						$pecah = $ambil->fetch_assoc();
						$subharga = $pecah["harga_produk"] * $jumlah;
						?>
						<tr>
							<td><?php echo $nomor; ?></td>
							<td><?php echo $pecah["nama_produk"]; ?></td>
							<td>Rp. <?php echo number_format($pecah["harga_produk"]); ?></td>
							<td><?php echo $jumlah; ?></td>
							<td>Rp. <?php echo number_format($subharga); ?></td>
							<td>
								<a href="hapuskeranjang.php?id=<?php echo $id_produk ?>" class="btn btn-danger btn-xs">Hapus</a>
							</td>
						</tr>
						<?php $nomor++; ?>
					<?php endforeach ?>
				</tbody>
			</table>
			<a href="index.php" class="btn btn-default">Lanjutkan Belanja</a>
			<a href="checkout.php" class="btn btn-primary">Checkout</a>

		</div>
	</section>
</body>

</html>