// diagnosa.js - Untuk halaman diagnosa
const categories = [
    "Aim & Dueling",
    "Accuracy & Control", 
    "Map Awareness",
    "Team Communication",
    "Game Sense"
];

let currentQuestionIndex = 0;
let userAnswers = {};

// Initialize diagnosis
document.addEventListener('DOMContentLoaded', function() {
    if (questionsFromDB && questionsFromDB.length > 0) {
        renderQuestion();
        updateProgress();
    }
});

function renderQuestion() {
    if (!questionsFromDB || questionsFromDB.length === 0) return;
    
    const question = questionsFromDB[currentQuestionIndex];
    
    // Update question text
    document.getElementById('questionText').textContent = question.pertanyaan;
    document.getElementById('currentQuestionNumber').textContent = currentQuestionIndex + 1;
    document.getElementById('currentQuestion').textContent = currentQuestionIndex + 1;
    
    // Update category
    const categoryElement = document.getElementById('questionCategory');
    const progressCategoryElement = document.getElementById('progressCategory');
    if (categoryElement && categories[currentQuestionIndex]) {
        categoryElement.textContent = categories[currentQuestionIndex];
        progressCategoryElement.textContent = categories[currentQuestionIndex];
    }
    
    // Create options - HANYA 2 OPSI
    const optionsContainer = document.getElementById('optionsContainer');
    optionsContainer.innerHTML = '';
    
    const options = [
        { value: 'ya', icon: 'fa-check-circle', text: 'Ya, sering terjadi' },
        { value: 'tidak', icon: 'fa-times-circle', text: 'Tidak, jarang terjadi' }
    ];
    
    options.forEach(option => {
        const button = document.createElement('button');
        button.className = 'option-btn';
        button.innerHTML = `
            <i class="fas ${option.icon}"></i>
            <span>${option.text}</span>
        `;
        button.onclick = () => selectAnswer(option.value);
        optionsContainer.appendChild(button);
    });
    
    // Update navigation buttons
    document.getElementById('prevButton').disabled = currentQuestionIndex === 0;
    document.getElementById('nextButton').textContent = 
        currentQuestionIndex === questionsFromDB.length - 1 ? 'Selesaikan' : 'Selanjutnya';
}

function selectAnswer(value) {
    const question = questionsFromDB[currentQuestionIndex];
    userAnswers[question.id] = value;
    
    // Highlight selected option
    const buttons = document.querySelectorAll('.option-btn');
    buttons.forEach(btn => btn.classList.remove('selected'));
    
    // Find and highlight selected button
    buttons.forEach(btn => {
        if (btn.textContent.includes(value === 'ya' ? 'Ya' : 'Tidak')) {
            btn.classList.add('selected');
        }
    });
}

function previousQuestion() {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        renderQuestion();
        updateProgress();
    }
}

function nextQuestion() {
    const question = questionsFromDB[currentQuestionIndex];
    
    // Check if current question is answered
    if (!userAnswers[question.id]) {
        alert('Silakan pilih jawaban terlebih dahulu!');
        return;
    }
    
    if (currentQuestionIndex < questionsFromDB.length - 1) {
        currentQuestionIndex++;
        renderQuestion();
        updateProgress();
    } else {
        submitDiagnosis();
    }
}

function updateProgress() {
    const progress = ((currentQuestionIndex + 1) / questionsFromDB.length) * 100;
    
    document.getElementById('progressText').textContent = 
        `Pertanyaan ${currentQuestionIndex + 1}/${questionsFromDB.length}`;
    document.getElementById('progressPercentage').textContent = `${Math.round(progress)}%`;
    document.getElementById('progressFill').style.width = `${progress}%`;
}

function submitDiagnosis() {
    // Show loading
    const container = document.querySelector('.diagnosa-container');
    if (container) container.classList.add('loading');
    
    // Submit to server
    fetch('proses_diagnosa.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'answers=' + encodeURIComponent(JSON.stringify(userAnswers))
    })
    .then(response => {
        if (response.ok) {
            // Redirect ke hasil.php
            window.location.href = 'hasil.php';
        } else {
            alert('Terjadi kesalahan saat mengirim hasil diagnosa.');
            if (container) container.classList.remove('loading');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan jaringan.');
        if (container) container.classList.remove('loading');
    });
}

// Add selected style to CSS
const style = document.createElement('style');
style.textContent = `
    .option-btn.selected {
        background: rgba(255, 70, 85, 0.2) !important;
        border-color: var(--primary) !important;
        transform: translateX(5px);
    }
    
    .loading {
        opacity: 0.7;
        pointer-events: none;
    }
    
    .loading::after {
        content: 'Memproses...';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: var(--primary);
        font-weight: bold;
    }
`;
document.head.appendChild(style);