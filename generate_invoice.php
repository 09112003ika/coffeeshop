<?php
session_start();
require_once('tcpdf/tcpdf.php'); // Pastikan TCPDF sudah terpasang

// Periksa apakah keranjang belanja ada dan memiliki isi
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    // Menambahkan judul dan informasi transaksi
    $pdf->Cell(0, 10, 'Coffee Shop - Invoice', 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->Cell(0, 10, 'Transaction Details:', 0, 1);

    // Tampilkan detail pesanan
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $pdf->Cell(0, 10, $item['name'] . ' - Rp ' . number_format($item['price'], 0, ',', '.') . ' x ' . $item['quantity'], 0, 1);
        $total += $item['price'] * $item['quantity'];
    }

    // Tambahkan total harga
    $pdf->Ln(5);
    $pdf->Cell(0, 10, 'Total: Rp ' . number_format($total, 0, ',', '.'), 0, 1, 'L');

    // Output PDF
    $pdf->Output('invoice.pdf', 'I');
} else {
    echo "No items in the cart to generate invoice.";
}
?>
