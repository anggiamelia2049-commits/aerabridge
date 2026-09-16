import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                // Palet warna resmi AERA Bridge (lihat proposal bab 7.1 - Tema Warna)
                'cyan-6': '#0C343D',     // Latar login/registrasi, header landing page, sidebar dashboard, tombol sekunder
                'cyan-4': '#45818E',     // Tombol aksi utama (Masuk/Daftar/Kirim), header formulir pelaporan
                'cyan-3': '#76A5AF',     // Ikon, elemen dekoratif, komponen pendukung
                'cyan-muda': '#A9D6DD',  // Sorotan ringan, latar dekoratif
                'abu-muda': '#D9D9D9',   // Border input, tabel, kotak pencarian, komponen formulir
                'abu-tua': '#4A4A4A',    // Teks utama, label formulir, informasi pendukung
                'hijau': '#4CAF50',      // Indikator status berhasil / laporan selesai
                'kuning': '#FFC107',     // Indikator prioritas rendah / peringatan SLA mendekati batas
                'oranye': '#FF9800',     // Indikator prioritas sedang / laporan dalam proses
                'merah': '#E53935',      // Indikator prioritas tinggi (kritis) / SLA overdue
            },
        },
    },

    plugins: [forms],
};