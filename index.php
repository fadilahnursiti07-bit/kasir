<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kasir Modern</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#1a0f0f;
color:white;
padding:25px;
}

.title{
font-size:35px;
font-weight:700;
margin-bottom:25px;
color:#ffb3b3;
}

.container{
display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
}

.card{
background:#2b1515;
padding:20px;
border-radius:25px;
box-shadow:0 10px 20px rgba(0,0,0,0.3);
}

.card h2{
margin-bottom:15px;
color:#ffd6d6;
}

input,select{
width:100%;
padding:14px;
border:none;
border-radius:14px;
margin-bottom:12px;
background:#4a1f1f;
color:white;
}

button{
width:100%;
padding:14px;
border:none;
border-radius:14px;
background:#ff4d4d;
color:white;
font-weight:600;
cursor:pointer;
transition:0.3s;
}

button:hover{
background:#ffb3b3;
color:#1a0f0f;
}

table{
width:100%;
border-collapse:collapse;
margin-top:15px;
}

th,td{
padding:12px;
text-align:left;
}

th{
background:#ff4d4d;
}

tr:nth-child(even){
background:#3a1c1c;
}

.total{
margin-top:20px;
font-size:22px;
font-weight:700;
color:#ffb3b3;
}

@media(max-width:900px){
.container{
grid-template-columns:1fr;
}
}
</style>
</head>
<body>

<div class="title">Aplikasi Kasir Modern</div>

<div class="dashboard" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:20px;">

<div class="card">
<h2>Total Produk</h2>
<h1 id="totalProduk">0</h1>
</div>

<div class="card">
<h2>Total Transaksi</h2>
<h1 id="totalTransaksi">0</h1>
</div>

<div class="card">
<h2>Total Pendapatan</h2>
<h1 id="totalPendapatan">Rp0</h1>
</div>

</div>

<div class="container">

<div class="card">
<h2>Tambah Produk</h2>

<input type="text" id="nama" placeholder="Nama Produk">
<input type="number" id="harga" placeholder="Harga Produk">
<input type="number" id="stok" placeholder="Stok Produk">

<button onclick="tambahProduk()">Tambah Produk</button>

<table>
<thead>
<tr>
<th>Produk</th>
<th>Harga</th>
<th>Stok</th>
<th>Aksi</th>
</tr>
</thead>
<tbody id="produkList"></tbody>
</table>
</div>

<div class="card">
<h2>Transaksi</h2>

<select id="produkSelect"></select>
<input type="number" id="jumlah" placeholder="Jumlah Beli">

<button onclick="tambahKeranjang()">Tambah Keranjang</button>

<table>
<thead>
<tr>
<th>Nama</th>
<th>Qty</th>
<th>Subtotal</th>
</tr>
</thead>
<tbody id="keranjang"></tbody>
</table>

<div class="total" id="totalHarga">Total : Rp0</div>

<input type="number" id="bayar" placeholder="Jumlah Bayar">

<button onclick="bayarSekarang()">Bayar</button>

<div class="total" id="kembalian">Kembalian : Rp0</div>
</div>

<div class="card" style="margin-top:20px;">
<h2>Riwayat Transaksi</h2>

<table>
<thead>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Total</th>
<th>Struk</th>
</tr>
</thead>
<tbody id="riwayatList"></tbody>
</table>
</div>

</div>

<script>

let produk = JSON.parse(localStorage.getItem('produkKasir')) || [];
let cart = [];
let transaksi = JSON.parse(localStorage.getItem('riwayatKasir')) || [];

function rupiah(angka){
return 'Rp' + angka.toLocaleString('id-ID');
}

function updateDashboard(){

let pendapatan = 0;

transaksi.forEach(item=>{
pendapatan += item.total;
});

document.getElementById('totalProduk').innerText = produk.length;
document.getElementById('totalTransaksi').innerText = transaksi.length;
document.getElementById('totalPendapatan').innerText = rupiah(pendapatan);
}

function renderRiwayat(){

const riwayat = document.getElementById('riwayatList');
riwayat.innerHTML='';

transaksi.forEach((item,index)=>{

riwayat.innerHTML += `
<tr>
<td>${index+1}</td>
<td>${item.tanggal}</td>
<td>${rupiah(item.total)}</td>
<td><button onclick="lihatStruk(${index})">Lihat</button></td>
</tr>
`;

});
}

function lihatStruk(index){

const data = transaksi[index];

let teks = '===== STRUK PEMBELIAN =====';

 data.items.forEach(item=>{
 teks += item.nama + ' x' + item.jumlah + ' = ' + rupiah(item.subtotal) + '';
 });

 teks += 'Total : ' + rupiah(data.total);
 teks += 'Tanggal : ' + data.tanggal;

 alert(teks);
}

function renderProduk(){
const list = document.getElementById('produkList');
const select = document.getElementById('produkSelect');

list.innerHTML = '';
select.innerHTML = '';

produk.forEach((item,index)=>{

list.innerHTML += `
<tr>
<td>${item.nama}</td>
<td>${rupiah(item.harga)}</td>
<td>${item.stok}</td>
<td><button onclick="hapusProduk(${index})">Hapus</button></td>
</tr>
`;

select.innerHTML += `
<option value="${index}">
${item.nama} - ${rupiah(item.harga)}
</option>
`;

});
}

function tambahProduk(){

const nama = document.getElementById('nama').value;
const harga = document.getElementById('harga').value;
const stok = document.getElementById('stok').value;

if(nama=='' || harga=='' || stok==''){
alert('Isi semua data');
return;
}

produk.push({
nama:nama,
harga:Number(harga),
stok:Number(stok)
});

localStorage.setItem('produkKasir',JSON.stringify(produk));

renderProduk();

nama.value='';
harga.value='';
stok.value='';
}

function hapusProduk(index){
produk.splice(index,1);
localStorage.setItem('produkKasir',JSON.stringify(produk));
renderProduk();
}

function tambahKeranjang(){

const index = document.getElementById('produkSelect').value;
const jumlah = Number(document.getElementById('jumlah').value);

if(jumlah <=0){
alert('Jumlah salah');
return;
}

const item = produk[index];

if(jumlah > item.stok){
alert('Stok tidak cukup');
return;
}

cart.push({
index:index,
nama:item.nama,
jumlah:jumlah,
subtotal:item.harga * jumlah
});

renderCart();
}

function renderCart(){

const keranjang = document.getElementById('keranjang');
keranjang.innerHTML='';

let total = 0;

cart.forEach(item=>{

keranjang.innerHTML += `
<tr>
<td>${item.nama}</td>
<td>${item.jumlah}</td>
<td>${rupiah(item.subtotal)}</td>
</tr>
`;

 total += item.subtotal;

});

 document.getElementById('totalHarga').innerText = 'Total : ' + rupiah(total);
}

function bayarSekarang(){

let total = 0;

cart.forEach(item=>{
total += item.subtotal;
});

const bayar = Number(document.getElementById('bayar').value);

if(bayar < total){
alert('Uang kurang');
return;
}

const kembali = bayar-total;

document.getElementById('kembalian').innerText = 'Kembalian : ' + rupiah(kembali);

cart.forEach(item=>{
produk[item.index].stok -= item.jumlah;
});

transaksi.push({
tanggal:new Date().toLocaleString('id-ID'),
total:total,
items:[...cart]
});

localStorage.setItem('produkKasir',JSON.stringify(produk));
localStorage.setItem('riwayatKasir',JSON.stringify(transaksi));

cart=[];

renderProduk();
renderCart();
renderRiwayat();
updateDashboard();

renderRiwayat();
updateDashboard();

alert('Pembayaran berhasil');
}

renderProduk();
renderCart();

</script>

</body>
</html>
