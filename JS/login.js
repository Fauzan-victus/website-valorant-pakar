// login.js - Untuk halaman login
document.addEventListener('DOMContentLoaded', function() {
    // Cek jika sudah login, redirect ke beranda
    checkAlreadyLoggedIn();
    
    // Setup form validation
    setupFormValidation();
});

function checkAlreadyLoggedIn() {
    // Cek session di PHP sudah ada atau belum
    // Jika user sudah login di session lain, redirect
    const currentUser = getCurrentUser();
    if (currentUser && window.location.pathname.includes('login.php')) {
        // Tunggu sebentar lalu redirect
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 1000);
    }
}

function setupFormValidation() {
    const usernameInput = document.getElementById('username');
    const errorMessage = document.getElementById('errorMessage');
    
    if (!usernameInput || !errorMessage) return;
    
    // Hide error message initially
    errorMessage.style.display = 'none';
    
    // Real-time validation
    usernameInput.addEventListener('input', function() {
        const username = this.value.trim();
        
        // Reset error state saat user mengetik
        if (username.length > 0) {
            this.style.borderColor = '';
            errorMessage.style.display = 'none';
        }
        
        // Beri feedback visual
        if (username.length >= 3) {
            this.style.borderColor = 'var(--success)';
        } else if (username.length > 0) {
            this.style.borderColor = 'var(--warning)';
        }
    });
    
    // Enter key support
    usernameInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            validateAndSubmit();
        }
    });
    
    // Focus pada input saat halaman load
    usernameInput.focus();
}

function validateAndSubmit() {
    const usernameInput = document.getElementById('username');
    const username = usernameInput.value.trim();
    const errorMessage = document.getElementById('errorMessage');
    
    if (!usernameInput || !errorMessage) return;
    
    // Reset error state
    usernameInput.style.borderColor = '';
    errorMessage.style.display = 'none';
    
    // Validation rules
    if (username === '') {
        showError(usernameInput, errorMessage, 'Nama pemain wajib diisi!');
        return;
    }
    
    if (username.length < 3) {
        showError(usernameInput, errorMessage, 'Nama minimal 3 karakter!');
        return;
    }
    
    if (username.length > 20) {
        showError(usernameInput, errorMessage, 'Nama maksimal 20 karakter!');
        return;
    }
    
    // Validasi karakter khusus (opsional)
    const regex = /^[a-zA-Z0-9_ ]+$/;
    if (!regex.test(username)) {
        showError(usernameInput, errorMessage, 'Hanya boleh huruf, angka, spasi, dan underscore!');
        return;
    }
    
    // Jika validasi lolos, simpan ke localStorage (fallback)
    saveToLocalStorage(username);
    
    // Tampilkan loading state
    showLoadingState();
    
    // Submit form ke PHP
    submitToPHP(username);
}

function showError(inputElement, errorElement, message) {
    inputElement.style.borderColor = 'var(--error)';
    errorElement.querySelector('span').textContent = message;
    errorElement.style.display = 'flex';
    inputElement.focus();
    
    // Shake animation
    inputElement.style.animation = 'shake 0.5s';
    setTimeout(() => {
        inputElement.style.animation = '';
    }, 500);
}

function saveToLocalStorage(username) {
    // Simpan username ke localStorage sebagai fallback
    localStorage.setItem('valorant_current_user', username);
    
    // Inisialisasi data player jika belum ada
    let playersData = JSON.parse(localStorage.getItem('valorant_players_data') || '{}');
    if (!playersData[username]) {
        playersData[username] = {
            createdAt: new Date().toISOString(),
            history: [],
            lastActivity: new Date().toISOString()
        };
        localStorage.setItem('valorant_players_data', JSON.stringify(playersData));
    }
    
    console.log('User saved to localStorage:', username);
}

function showLoadingState() {
    const loginBtn = document.querySelector('.login-btn');
    const usernameInput = document.getElementById('username');
    
    if (loginBtn && usernameInput) {
        // Disable input dan button
        usernameInput.disabled = true;
        loginBtn.disabled = true;
        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        loginBtn.style.opacity = '0.8';
    }
}

function submitToPHP(username) {
    // Method 1: Submit form secara tradisional (lebih reliable)
    const form = document.querySelector('form');
    if (form) {
        // Set value ke hidden input atau langsung ke form
        const usernameInput = form.querySelector('input[name="username"]');
        if (usernameInput) {
            usernameInput.value = username;
        }
        
        // Submit form
        form.submit();
        return;
    }
    
    // Method 2: AJAX request (fallback)
    console.log('Submitting to PHP via AJAX:', username);
    
    const formData = new FormData();
    formData.append('username', username);
    formData.append('login', '1');
    
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Redirect ke index.php
            window.location.href = 'index.php';
        } else {
            throw new Error('Login failed');
        }
    })
    .catch(error => {
        console.error('Login error:', error);
        
        // Reset loading state
        const loginBtn = document.querySelector('.login-btn');
        const usernameInput = document.getElementById('username');
        
        if (loginBtn && usernameInput) {
            usernameInput.disabled = false;
            loginBtn.disabled = false;
            loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Mulai Analisis';
            loginBtn.style.opacity = '1';
            
            // Show error
            const errorMessage = document.getElementById('errorMessage');
            showError(usernameInput, errorMessage, 'Gagal login. Coba lagi.');
        }
    });
}

// Tambah animasi shake ke CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .login-btn:disabled {
        cursor: not-allowed;
    }
    
    input:disabled {
        background: rgba(255, 255, 255, 0.05);
        cursor: not-allowed;
    }
`;
document.head.appendChild(style);

// Export function untuk dipanggil dari HTML
window.validateAndSubmit = validateAndSubmit;