function previewImageUpload(event) {
    if (event.target.files[0]) {
        previewImage.src = URL.createObjectURL(event.target.files[0]);
        previewImage.onload = function() {
            URL.revokeObjectURL(previewImage.src) // free memory
        }
    }
}
