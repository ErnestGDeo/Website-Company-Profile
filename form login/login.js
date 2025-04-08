let inputSequence = ""; // Variable untuk menyimpan urutan input

// Tambahkan event listener untuk keydown
document.addEventListener('keydown', function(event) {
    inputSequence += event.key.toLowerCase(); // Tambahkan karakter yang ditekan ke urutan input

    // Hanya simpan maksimal 5 karakter terakhir
    if (inputSequence.length > 5) {
        inputSequence = inputSequence.slice(-5);
    }

    // Periksa apakah urutan input adalah "login"
    if (inputSequence === "login") {
        const loginContainer = document.getElementById('loginContainer');
        
        // Periksa apakah loginContainer ada
        if (loginContainer) {
            // Toggle tampilan loginContainer
            loginContainer.style.display = 
                (loginContainer.style.display === 'none' || loginContainer.style.display === '') 
                ? 'block' : 'none';
        }

        // Reset inputSequence setelah berhasil
        inputSequence = "";
    }
});
