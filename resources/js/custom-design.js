// ======================================
// Inisialisasi Canvas
// ======================================
let canvas, designCanvas;
const mockupCanvasId = "mockup-canvas";
const designCanvasId = "design-canvas";

// Daftar mockup
const mockupImages = {
    putih: {
        front: "/kaos/kaos-putih-depan.png",
        back: "/kaos/kaos-putih-belakang.png",
        side: "/kaos/kaos-putih-samping.png",
    },
    hitam: {
        front: "/kaos/kaos-hitam-depan.png",
        back: "/kaos/kaos-hitam-belakang.png",
        side: "/kaos/kaos-hitam-samping.png",
    },
    merah: {
        front: "/kaos/kaos-merah-depan.png",
        back: "/kaos/kaos-merah-belakang.png",
        side: "/kaos/kaos-merah-samping.png",
    },
};

// ======================================
// Fungsi Init
// ======================================
function init() {
    // Mockup canvas (untuk background kaos)
    canvas = new fabric.Canvas(mockupCanvasId, {
        preserveObjectStacking: true,
    });

    // Design canvas (untuk desain custom)
    designCanvas = new fabric.Canvas(designCanvasId, {
        preserveObjectStacking: true,
    });

    // Default: load kaos putih depan
    loadMockup("putih", "front");

    // Event listener designCanvas
    designCanvas.on("object:added", hitungHarga);
    designCanvas.on("object:modified", hitungHarga);
    designCanvas.on("object:removed", hitungHarga);

    // Tombol
    document.getElementById("save-design-btn").addEventListener("click", saveDesign);
    document.getElementById("whatsapp-btn").addEventListener("click", sendToWhatsApp);
}

// ======================================
// Load Mockup ke Canvas
// ======================================
function loadMockup(color, view) {
    if (!mockupImages[color] || !mockupImages[color][view]) {
        console.error("Mockup tidak ditemukan untuk:", color, view);
        return;
    }

    const imageUrl = mockupImages[color][view];
    console.log("Loading mockup:", imageUrl);

    canvas.clear();

    fabric.Image.fromURL(imageUrl, function(img) {
        img.scaleToWidth(canvas.width);
        img.scaleToHeight(canvas.height);
        canvas.add(img);
        canvas.sendToBack(img);
    }, { crossOrigin: "anonymous" });
}

// ======================================
// Hitung Harga (contoh sederhana)
// ======================================
function hitungHarga() {
    const objects = designCanvas.getObjects();
    let harga = 50000; // harga dasar kaos

    if (objects.length > 0) {
        harga += objects.length * 5000; // contoh: tambah 5k per object
    }

    document.getElementById("harga").innerText = "Rp " + harga.toLocaleString();
}

// ======================================
// Save Desain
// ======================================
function saveDesign() {
    const dataURL = designCanvas.toDataURL({
        format: "png",
        quality: 1,
    });

    const link = document.createElement("a");
    link.href = dataURL;
    link.download = "desain-kaos.png";
    link.click();
}

// ======================================
// Kirim ke WhatsApp
// ======================================
function sendToWhatsApp() {
    const nomor = "6281234567890"; // ganti nomor WA lu
    const pesan = encodeURIComponent("Halo, saya mau pesan kaos custom.");
    const url = `https://wa.me/${nomor}?text=${pesan}`;
    window.open(url, "_blank");
}

// ======================================
// Panggil Init
// ======================================
document.addEventListener("DOMContentLoaded", function() {
    init();
});
