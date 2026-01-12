// element.js - File JavaScript Utama
/* ===== STORAGE MANAGEMENT ===== */
const STORAGE_KEYS = {
    CURRENT_USER: 'valorant_current_user',
    PLAYERS_DATA: 'valorant_players_data'
};

// User management
function getCurrentUser() {
    return localStorage.getItem(STORAGE_KEYS.CURRENT_USER) || 
           (typeof username !== 'undefined' ? username : '');
}

function setCurrentUser(username) {
    localStorage.setItem(STORAGE_KEYS.CURRENT_USER, username);
}

function clearCurrentUser() {
    localStorage.removeItem(STORAGE_KEYS.CURRENT_USER);
}

// Players data management
function getPlayersData() {
    const data = localStorage.getItem(STORAGE_KEYS.PLAYERS_DATA);
    return data ? JSON.parse(data) : {};
}

function savePlayersData(data) {
    localStorage.setItem(STORAGE_KEYS.PLAYERS_DATA, JSON.stringify(data));
}

function saveDiagnosisResult(result) {
    const username = getCurrentUser();
    if (!username) return;

    const playersData = getPlayersData();
    if (!playersData[username]) {
        playersData[username] = {
            createdAt: new Date().toISOString(),
            history: []
        };
    }

    const diagnosisEntry = {
        id: Date.now().toString(),
        result: result,
        timestamp: new Date().toISOString(),
        date: new Date().toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        })
    };

    playersData[username].history.unshift(diagnosisEntry);
    playersData[username].lastActivity = new Date().toISOString();
    
    savePlayersData(playersData);
}

/* ===== HAMBURGER MENU ===== */
function toggleMenu() {
    const menu = document.getElementById('navMenu');
    const hamburger = document.querySelector('.hamburger-menu');
    menu.classList.toggle('active');
    hamburger.classList.toggle('active');
}

/* ===== GLOBAL FUNCTIONS ===== */
function logout() {
    window.location.href = 'logout.php';
}

// Global export
window.toggleMenu = toggleMenu;
window.logout = logout;
window.getCurrentUser = getCurrentUser;
window.saveDiagnosisResult = saveDiagnosisResult;