<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AERA Bridge') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
            input:-webkit-autofill {
                -webkit-box-shadow: 0 0 0 1000px #F8FFFF inset;
            }
        </style>
    </head>
<body class="text-gray-900 antialiased">
        <style>
            /* Bentuk samping: skala mengikuti tinggi layar, maksimal 55% lebar layar */
            .ob-side {
                position: absolute; top: 0; height: 100%;
                width: min(49.5vh, 55vw);
                pointer-events: none;
            }
            .ob-left  { left: 0; }
            .ob-right { right: 0; }
        </style>

        <div class="min-h-screen flex items-center justify-center bg-[#0C343D] relative overflow-hidden px-4 py-10">

            <!-- Bentuk kiri (diambil langsung dari desain Figma) -->
            <svg class="ob-side ob-left" viewBox="0 0 300 606" preserveAspectRatio="xMinYMid meet" aria-hidden="true">
    <path d="M0 309.7 L179.2 409.0 Q200.2 420.6 188.5 441.6 L-400.8 1505 L-611.4 1505 L122.4 460.7 Q136.8 440.2 116.2 426.1 L0 343 Z" fill="#76A5AF" stroke="#76A5AF" stroke-width="1" stroke-linejoin="round"/>
    <rect x="-170.4" y="-84.6" width="96.0" height="569.7" rx="25.4" fill="#A9D6DE" transform="rotate(-54.84 -122.4 200.2)"/>
            </svg>

            <!-- Bentuk kanan (diambil langsung dari desain Figma) -->
            <svg class="ob-side ob-right" viewBox="632 0 300 606" preserveAspectRatio="xMaxYMid meet" aria-hidden="true">
    <rect x="828" y="-900" width="400" height="1230" fill="#76A5AF"/>
    <rect x="806.9" y="298.5" width="192.2" height="192.6" rx="25.3" fill="#A9D6DE" transform="rotate(-145.11 903.0 394.8)"/>
    <rect x="768.6" y="369.3" width="260.9" height="102.6" rx="26.0" fill="#45818E" transform="rotate(-54.60 899.1 420.6)"/>
            </svg>

            <!-- Lingkaran + garis elips (digambar JS). Lingkaran dulu, garis di atasnya seperti di desain -->
            <svg id="orbit-svg" class="absolute inset-0 w-full h-full pointer-events-none" aria-hidden="true">
                <circle class="orbit-dot" fill="#9FABAE" />
                <circle class="orbit-dot" fill="#9FABAE" />
                <ellipse id="orbit-path" fill="none" stroke="white" stroke-opacity="0.85" stroke-width="1" />
            </svg>

            <!-- Card login -->
            <div id="login-card" class="relative z-10 w-full sm:max-w-md bg-[#F8FFFF] shadow-2xl rounded-2xl px-6 py-9">
                {{ $slot }}
            </div>
        </div>

        <script>
            (function () {
                var svg  = document.getElementById('orbit-svg');
                var path = document.getElementById('orbit-path');
                var card = document.getElementById('login-card');
                var dots = svg.querySelectorAll('.orbit-dot');
                var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

                var PERIOD = 14;                          // detik per 1 putaran (kecil = lebih cepat)
                var PHASES = [2.54, 2.54 + Math.PI];      // posisi awal sesuai desain, berseberangan
                var g = { cx: 0, cy: 0, rx: 1, ry: 1, angle: 0, r: 20 };

                function layout() {
                    var w  = svg.clientWidth, h = svg.clientHeight;
                    var sr = svg.getBoundingClientRect();
                    var cr = card.getBoundingClientRect();
                    var cw = cr.width, ch = cr.height;

                    // Satuan skala: di desain Figma lebar kartu = 372
                    var s = cw / 372;

                    g.cx = cr.left - sr.left + cw / 2;
                    g.cy = cr.top  - sr.top  + ch / 2;

                    if (w < 640 || w < h * 0.85) {
                        // HP / tablet tegak: elips dibuat tegak supaya tetap keluar dari sisi atas & bawah kartu
                        g.angle = 62;
                        var hy  = ch / 2 + Math.max(60, (h - ch) / 2 * 0.8);
                        g.rx = hy / Math.sin(g.angle * Math.PI / 180);
                        g.ry = cw * 0.26;
                    } else {
                        // Tablet landscape / desktop: proporsi persis desain (a=406, b=92, miring 31.5 derajat)
                        g.angle = 31.5;
                        g.rx = 406 * s;
                        g.ry = 92 * s;
                        g.cy += 10 * s;

                        // Pastikan elips tidak keluar dari tepi layar
                        var ar = g.angle * Math.PI / 180;
                        var half = g.rx * Math.cos(ar) + g.ry * Math.sin(ar);
                        var maxHalf = w / 2 - 14;
                        if (half > maxHalf) { var k = maxHalf / half; g.rx *= k; g.ry *= k; }
                    }
                    g.r = 40 * s;

                    svg.setAttribute('viewBox', '0 0 ' + w + ' ' + h);
                    path.setAttribute('cx', g.cx);
                    path.setAttribute('cy', g.cy);
                    path.setAttribute('rx', g.rx);
                    path.setAttribute('ry', g.ry);
                    path.setAttribute('transform', 'rotate(' + g.angle + ' ' + g.cx + ' ' + g.cy + ')');
                    dots.forEach(function (d) { d.setAttribute('r', g.r); });
                }

                function place(time) {
                    var a = g.angle * Math.PI / 180;
                    var cosA = Math.cos(a), sinA = Math.sin(a);
                    var spin = (time / 1000) * (Math.PI * 2 / PERIOD);

                    dots.forEach(function (dot, i) {
                        var t  = PHASES[i] + spin;
                        var x0 = g.rx * Math.cos(t);
                        var y0 = g.ry * Math.sin(t);
                        dot.setAttribute('cx', g.cx + x0 * cosA - y0 * sinA);
                        dot.setAttribute('cy', g.cy + x0 * sinA + y0 * cosA);
                    });
                }

                var raf;
                function loop(now) { place(now); raf = requestAnimationFrame(loop); }
                function start() {
                    cancelAnimationFrame(raf);
                    if (reduceMotion.matches) { place(0); } else { raf = requestAnimationFrame(loop); }
                }
                function relayout() { layout(); if (reduceMotion.matches) place(0); }

                window.addEventListener('resize', relayout);
                if (window.ResizeObserver) { new ResizeObserver(relayout).observe(card); }
                reduceMotion.addEventListener('change', start);

                layout();
                start();
            })();
        </script>
    </body>
</html>
