document.addEventListener("DOMContentLoaded", function () {

    // KONFIRMASI LOGOUT
    document.addEventListener("click", function (e) {
        if (e.target.closest(".btn-logout")) {
            if (!confirm("Apakah Anda yakin ingin logout?")) {
                e.preventDefault();
            }
        }
    });


    // MODAL GAMBAR
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImg");

    document.addEventListener("click", function (e) {
        // buka gambar
        let btnGambar = e.target.closest(".lihat-gambar");
        if (btnGambar) {
            e.preventDefault();

            if (modal && modalImg) {
                modal.style.display = "block";
                modalImg.src = btnGambar.dataset.src;
            }
        }

        // tombol close
        if (e.target.classList.contains("close-modal")) {
            if (modal) modal.style.display = "none";
        }

        // klik luar modal
        if (modal && e.target === modal) {
            modal.style.display = "none";
        }
    });
});