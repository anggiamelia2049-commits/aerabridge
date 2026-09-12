<!DOCTYPE html>
<html>
<head>
    <title>Test Kamera</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 30px;
        }

        video, canvas, img {
            width: 500px;
            max-width: 100%;
            border-radius: 10px;
            border: 2px solid #ccc;
        }

        button {
            margin-top: 15px;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <h2>Test Kamera Laptop</h2>

    <video id="video" autoplay playsinline></video>

    <br>

    <button type="button" onclick="ambilFoto()">
        📸 Ambil Foto
    </button>

    <br><br>

    <canvas id="canvas" style="display: none;"></canvas>

    <img id="hasilFoto" style="display: none;">

    <script>

        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const hasilFoto = document.getElementById('hasilFoto');

        // Meminta akses kamera
        navigator.mediaDevices.getUserMedia({
            video: true
        })
        .then(function(stream) {
            video.srcObject = stream;
        })
        .catch(function(error) {
            console.log(error);

            alert('Kamera tidak bisa digunakan: ' + error.message);
        });

        // Mengambil foto dari video
        function ambilFoto() {

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const context = canvas.getContext('2d');

            context.drawImage(
                video,
                0,
                0,
                canvas.width,
                canvas.height
            );

            const foto = canvas.toDataURL('image/png');

            hasilFoto.src = foto;
            hasilFoto.style.display = 'block';

            video.style.display = 'none';
        }

    </script>

</body>
</html>