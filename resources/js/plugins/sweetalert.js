import Swal from 'sweetalert2';

// SweetAlert untuk notifikasi
export const showNotification = (message, type = 'success') => {
  Swal.fire({
    title: message,
    icon: type,
    confirmButtonText: 'OK',
    timer: 2000,
    timerProgressBar: true,
  });
};

// SweetAlert untuk konfirmasi
export const showConfirmation = async (message = 'Are you sure?', confirmText = 'Yes', cancelText = 'Cancel') => {
  const result = await Swal.fire({
    title: message,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: confirmText,
    cancelButtonText: cancelText,
  });

  return result.isConfirmed;
};

export default Swal;
