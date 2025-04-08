document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('programForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Menghentikan pengalihan default form
        
        // Debugging log
        console.log('Form submitted');

        // Tampilkan notifikasi
        document.getElementById('notification').style.display = 'block';

        // Reset form (opsional)
        this.reset();
    });
});
document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector(".sidebar");
    const toggleButton = document.querySelector(".js-sidebar-toggle");
    const overlay = document.createElement("div");
    overlay.classList.add("overlay");
    document.body.appendChild(overlay);

    toggleButton.addEventListener("click", () => {
      sidebar.classList.toggle("hide");
      overlay.classList.toggle("active");
    });

    overlay.addEventListener("click", () => {
      sidebar.classList.add("hide");
      overlay.classList.remove("active");
    });
  });