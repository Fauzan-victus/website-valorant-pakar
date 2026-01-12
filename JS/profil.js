// profil.js - Untuk halaman profil
document.addEventListener('DOMContentLoaded', function() {
    loadProfileData();
    loadHistory();
});

function loadProfileData() {
    const currentUser = currentUsername || getCurrentUser();
    const playersData = JSON.parse(localStorage.getItem('valorant_players_data') || '{}');
    
    if (playersData[currentUser]) {
        const userData = playersData[currentUser];
        
        // Set total diagnosa
        const totalDiagnosa = userData.history ? userData.history.length : 0;
        const totalElement = document.getElementById('totalDiagnosa');
        if (totalElement) {
            totalElement.textContent = totalDiagnosa;
        }
        
        // Set last activity
        if (userData.lastActivity) {
            const lastActivity = new Date(userData.lastActivity);
            const now = new Date();
            const diffHours = Math.floor((now - lastActivity) / (1000 * 60 * 60));
            
            const lastActivityElement = document.getElementById('lastActivity');
            if (lastActivityElement) {
                if (diffHours < 1) {
                    lastActivityElement.textContent = 'Baru saja';
                } else if (diffHours < 24) {
                    lastActivityElement.textContent = `${diffHours} jam lalu`;
                } else {
                    const diffDays = Math.floor(diffHours / 24);
                    lastActivityElement.textContent = `${diffDays} hari lalu`;
                }
            }
        }
        
        // Set rank based on total diagnosa
        const rankElement = document.getElementById('playerRank');
        if (rankElement) {
            if (totalDiagnosa >= 10) {
                rankElement.textContent = 'Diamond Analyzer';
            } else if (totalDiagnosa >= 5) {
                rankElement.textContent = 'Gold Analyzer';
            } else if (totalDiagnosa >= 1) {
                rankElement.textContent = 'Silver Analyzer';
            } else {
                rankElement.textContent = 'Bronze Analyzer';
            }
        }
    }
}

function loadHistory() {
    // Jika ada data dari database, tampilkan
    if (riwayatFromDB && riwayatFromDB.length > 0) {
        return; // Sudah ditampilkan oleh PHP
    }
    
    // Jika tidak, ambil dari localStorage
    const currentUser = currentUsername || getCurrentUser();
    const playersData = JSON.parse(localStorage.getItem('valorant_players_data') || '{}');
    
    if (playersData[currentUser] && playersData[currentUser].history) {
        const historyList = document.getElementById('historyList');
        if (historyList && playersData[currentUser].history.length > 0) {
            historyList.innerHTML = '';
            
            playersData[currentUser].history.forEach(item => {
                const historyItem = document.createElement('div');
                historyItem.className = 'history-item';
                historyItem.innerHTML = `
                    <div class="history-content">
                        <div class="history-message">${item.result}</div>
                        <div class="history-date">
                            <i class="far fa-clock"></i>
                            ${item.date}
                        </div>
                    </div>
                `;
                historyList.appendChild(historyItem);
            });
        }
    }
}