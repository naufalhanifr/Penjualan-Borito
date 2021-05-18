<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "belajarpi");

// jika tidak ada session pelanggan (belum login), maka dilarikan ke login.php
if (!isset($_SESSION["pelanggan"])) {
	echo "<script>alert('silahkan login');</script>";
	echo "<script>location='login.php';</script>";
}

?>

<?php $koneksi = new mysqli("localhost", "root", "", "belajarpi"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Nota</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
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

			<!-- array -->
			<?php
			$id_pelanggan = $_SESSION["pelanggan"]["id_pelanggan"];
			$ambil = $koneksi->query("SELECT * FROM pembelian JOIN pelanggan ON pembelian.id_pelanggan=pelanggan.id_pelanggan WHERE pelanggan.id_pelanggan = $id_pelanggan Order By id_pembelian DESC");
			$detail = $ambil->fetch_assoc();
			if (isset($detail)) {
			?>
				<pre><?php print_r($detail); ?></pre>
				<p>Nama : <?php echo $detail['nama_pelanggan']; ?> <br>
					Telepon : <?php echo $detail['telepon_pelanggan']; ?> <br>
					Email : <?php echo $detail['email_pelanggan']; ?><br>
					Tanggal :<?php echo $detail['tanggal_pembelian']; ?> <br>
					Total Pembelian :<?php echo $detail['total_pembelian']; ?><br>
				</p>
			<?php } ?>
			<!-- array -->

			<h2>Detail Pembelian</h2>

			<table class="table table-bordered">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama Produk</th>
						<th>Harga</th>
						<th>Jumlah</th>
						<th>Ongkir</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					<?php $nomor = 1;
					$id_pelanggan = $_SESSION["pelanggan"]["id_pelanggan"];
					$ambil = $koneksi->query("SELECT * FROM pembelian INNER JOIN produk ON pembelian.id_produk = produk.id_produk INNER JOIN ongkir ON pembelian.id_produk = ongkir.id_ongkir WHERE pembelian.id_pelanggan=$id_pelanggan");
					while ($pecah = $ambil->fetch_assoc()) { ?>
						<tr>
							<td><?php echo $nomor; ?></td>
							<td><?php echo $pecah['nama_produk']; ?></td>
							<td><?php echo $pecah['harga_produk']; ?></td>
							<td><?php echo $pecah['jumlah']; ?></td>
							<td><?php echo $pecah['tarif']; ?></td>
							<td><?php echo $pecah['total_pembelian']; ?></td>
						</tr>
						<?php $nomor++; ?>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5">Total Pembayaran</th>
						<th>Rp. <?php $a = $koneksi->query("SELECT SUM(total_pembelian) AS total FROM pembelian WHERE pembelian.id_pelanggan=$id_pelanggan");
								while ($b = $a->fetch_assoc()) {
									$output = $b['total'];
									echo $output;
								} ?></th>
					</tr>
				</tfoot>
			</table>
			<?php
			if (!isset($pecah)) { ?>
				<div class="row">
					<div class="col-md-7">
						<div class="alert alert-info">
							<p>
								Silahkan melakukan pembayaran Rp. <?php echo $output; ?>
								ke <br> <strong>BANK BCA 7401457805 AN. Dendi Ghazali</strong>
							</p>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</section>
</body>

</html>