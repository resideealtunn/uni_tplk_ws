function showRejectReason(button) {
    const redReasonDiv = button.nextElementSibling;
    redReasonDiv.style.display = (redReasonDiv.style.display === "block") ? "none" : "block";
  }

  function openModal(text) {
    document.getElementById('modalTextContent').innerText = text;
    const textModal = new bootstrap.Modal(document.getElementById('textModal'));
    textModal.show();
  }

  function openImage(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
  }

  