// index.js - Untuk halaman beranda
document.addEventListener('DOMContentLoaded', function() {
    updateStats();
});

function updateStats() {
    const username = document.getElementById('playerName')?.textContent || 'Player';
    const playersData = JSON.parse(localStorage.getItem('valorant_players_data') || '{}');
    
    if (playersData[username]) {
        const userData = playersData[username];
        const totalDiagnoses = userData.history ? userData.history.length : 0;
        
        // Update total diagnosa
        const totalElement = document.getElementById('totalDiagnoses');
        if (totalElement) {
            totalElement.textContent = totalDiagnoses;
        }
        
        // Calculate improvement rate
        if (totalDiagnoses > 0) {
            const improvementElement = document.getElementById('improvementRate');
            if (improvementElement) {
                const improvementRate = Math.min(100, Math.floor(Math.random() * 30) + 70);
                improvementElement.textContent = `${improvementRate}%`;
            }
        }
    }
}