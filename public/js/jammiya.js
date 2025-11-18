        const menuBtnx = document.getElementById('menu-toggle');
        const menuBtn = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        const openModalBtn = document.getElementById('openModal');
        const modal = document.getElementById('productModal');
        const closeModalBtn = document.getElementById('closeModal');

        menuBtnx.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        openModalBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        closeModalBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Ila kliki barra men modal, tsed
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // bach tban l image li f add
    document.getElementById("imageInput").addEventListener("change", function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById("previewImage");

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
            preview.style.display = "none";
        }
    });

function toggleComments(id) {
    let box = document.getElementById(id);
    if (box.style.display === "none" || box.style.display === "") {
        box.style.opacity = 0;
        box.style.display = "block";
        setTimeout(() => box.style.opacity = 1, 10);
    } else {
        box.style.opacity = 0;
        setTimeout(() => box.style.display = "none", 300);
    }
}
