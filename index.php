<!DOCTYPE html>
<html>
<head>
    <title>Absensi QR | Kamera & Form</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }

        .navbar {
            background-color: #007bff;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        video {
            border: 2px solid #ccc;
            border-radius: 5px;
        }

        #history {
            margin: 30px auto;
            width: 90%;
            max-width: 800px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            color: black;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h2>📘 Sistem Absensi</h2>
        <div>
            <a href="#">Beranda</a>
            <a href="#form">Form</a>
            <a href="#kamera">Kamera</a>
        </div>
    </div>

    <h1 id="form">📸 Sistem Absensi Mahasiswa</h1>
    <h2>Scan kode QR atau gunakan tombol di bawah ini</h2>

    <!-- Video dari kamera -->
    <video id="video" width="320" height="240" autoplay></video>
    <br><br>

    <!-- Tombol untuk mengisi Google Form -->
    <a href="YOUR FORM" target="_blank">
        <button type="button">Isi Google Form 📄</button>
    </a>

    <!-- Tombol untuk melihat hasil absen (Spreadsheet) -->
    <a href="YOUR SPREDSHEET" target="_blank">
        <button type="button">Absen Sekarang 📊</button>
    </a>

    <!-- Form tersembunyi untuk upload foto -->
    <form method="POST" action="upload.php" id="photoForm">
        <input type="hidden" name="image" id="image">
        <button type="button" id="capture">📷 Simpan Foto ke Server</button>
    </form>

    <!-- Riwayat Foto -->
    <div id="history">
        <h3>Riwayat Foto</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama File</th>
                    <th>Waktu Upload</th>
                </tr>
            </thead>
            <tbody id="photo-history"></tbody>
        </table>
    </div>

    <script>
        const video = document.getElementById('video');
        const image = document.getElementById('image');
        const photoForm = document.getElementById('photoForm');
        const historyContainer = document.getElementById('photo-history');

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => video.srcObject = stream)
            .catch(err => alert('Gagal mengakses kamera: ' + err));

        document.getElementById('capture').addEventListener('click', function () {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);
            const dataURL = canvas.toDataURL('image/png');
            image.value = dataURL;

            fetch('upload.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'image=' + encodeURIComponent(dataURL)
            })
            .then(response => response.text())
            .then(() => setTimeout(loadHistory, 1000));
        });

        function loadHistory() {
            fetch('get_photos.php')
                .then(response => response.json())
                .then(data => {
                    historyContainer.innerHTML = '';
                    data.forEach(item => {
                        const row = document.createElement('tr');

                        const nameCell = document.createElement('td');
                        const link = document.createElement('a');
                        link.href = 'uploads/' + item.name;
                        link.textContent = item.name;
                        link.target = '_blank';
                        nameCell.appendChild(link);

                        const timeCell = document.createElement('td');
                        timeCell.textContent = item.time;

                        row.appendChild(nameCell);
                        row.appendChild(timeCell);
                        historyContainer.appendChild(row);
                    });
                });
        }

        loadHistory();
    </script>
</body>
</html>
