<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Tidak Tersedia Offline</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,700&display=swap" rel="stylesheet">
    <!-- Styles -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            text-align: center;
            padding: 50px 20px;
        }
        h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            margin-top: 20px;
            color: #666;
        }
        .icon {
            font-size: 80px;
            color: #ff6b6b;
        }
        a.button {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background-color: #ff6b6b;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        a.button:hover {
            background-color: #ff5252;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">📴</div>
        <h1>Ups! Halaman Tidak Dapat Diakses</h1>
        <p>HALAMAN INI TIDAK BISA DIAKSES DIKARENAKAN FITUR OFFLINE WEBSITE HANYA TERBATAS MENGAKSES BEBERAPA HALAMAN YANG SUDAH ANDA AKSES</p>
        <p>ATAU</p>
        <p>AKSES OFFLINE WEBSITE SUDAH MELEBIHI BATAS WAKTU MAKSIMAL</p>
        <a href="javascript:history.back()" class="button">Kembali</a>
</body>
</html>
