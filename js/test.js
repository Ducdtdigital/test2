// Lưu trữ đường dẫn URL hiện tại vào sessionStorage
sessionStorage.setItem('previousUrl', window.location.href);

// Lấy đường dẫn URL trước đó từ sessionStorage
const previousUrl = sessionStorage.getItem('previousUrl');

// Kiểm tra nếu trang trước đó là trang được chuyển hướng 302 và chứa 'ch=5024' hoặc 'ch=5025'
if (previousUrl && previousUrl.includes('tabid=556')) {
  // Lưu trữ đường dẫn trước khi bị chuyển hướng
  sessionStorage.setItem('redirectedUrl', previousUrl);
  
  // Kiểm tra tham số 'ch' trong đường dẫn trước đó và chuyển hướng tới đường dẫn mới tương ứng
  if (previousUrl.includes('ch=5024')) {
    window.location.href = 'http://google.com';
  } else if (previousUrl.includes('ch=5025')) {
    window.location.href = 'http://google.com.vn';
  }
}

// Hiển thị đường dẫn URL trước đó trong popup
alert(previousUrl);