{{-- Welcome Modal Component with CSS and JavaScript --}}

<style>
/* Welcome Modal Styles */
.welcome-modal {
    display: flex;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.welcome-content {
    margin: auto;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 0;
    border-radius: 20px;
    max-width: 600px;
    width: 90%;
    position: relative;
    animation: slideIn 0.5s ease-out;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    overflow: hidden;
}

.welcome-header {
    background: rgba(255, 255, 255, 0.1);
    padding: 30px;
    text-align: center;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

.welcome-header i {
    font-size: 60px;
    color: #fff;
    margin-bottom: 15px;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.welcome-header h2 {
    color: #fff;
    font-size: 32px;
    font-weight: bold;
    margin: 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.welcome-body {
    padding: 40px;
    color: #fff;
}

.welcome-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.welcome-body h3 {
    font-size: 24px;
    margin-bottom: 15px;
    font-weight: 600;
}

.welcome-body p {
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 15px;
    color: rgba(255, 255, 255, 0.95);
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.feature-list li {
    padding: 10px 0;
    padding-left: 30px;
    position: relative;
    font-size: 15px;
}

.feature-list li:before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #4ade80;
    font-weight: bold;
    font-size: 20px;
}

.welcome-footer {
    padding: 30px 40px;
    text-align: center;
    background: rgba(0, 0, 0, 0.2);
}

.btn-explore {
    background: #fff;
    color: #667eea;
    border: none;
    padding: 15px 40px;
    font-size: 18px;
    font-weight: 600;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.btn-explore:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    background: #f0f0f0;
}

.emoji-rain {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
}

.emoji {
    position: absolute;
    font-size: 30px;
    animation: fall linear infinite;
}

@keyframes fall {
    to {
        transform: translateY(600px) rotate(360deg);
        opacity: 0;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-content {
        width: 95%;
    }

    .welcome-body {
        padding: 30px 20px;
    }
}
</style>

<!-- Welcome Modal HTML -->
<div id="welcomeModal" class="welcome-modal" style="display: none;">
    <div class="emoji-rain">
        <span class="emoji" style="left: 10%; animation-duration: 3s; animation-delay: 0s;">🚢</span>
        <span class="emoji" style="left: 25%; animation-duration: 4s; animation-delay: 0.5s;">📦</span>
        <span class="emoji" style="left: 40%; animation-duration: 3.5s; animation-delay: 1s;">🚚</span>
        <span class="emoji" style="left: 60%; animation-duration: 4.5s; animation-delay: 0.3s;">✈️</span>
        <span class="emoji" style="left: 75%; animation-duration: 3.8s; animation-delay: 0.8s;">🌍</span>
        <span class="emoji" style="left: 90%; animation-duration: 4.2s; animation-delay: 0.2s;">📊</span>
    </div>

    <div class="welcome-content">
        <div class="welcome-header">
            <i class="fas fa-ship"></i>
            <h2>Welcome! 🎉</h2>
        </div>

        <div class="welcome-body">
            <span class="welcome-badge">🚀 Portfolio Demo Project</span>

            <h3>Logistics Management System</h3>
            <p>
                <strong>Welcome to my portfolio project!</strong>
                This is a comprehensive logistics management system built with Laravel 12 and Bootstrap 5.
            </p>

            <p style="background: rgba(255, 255, 255, 0.15); padding: 15px; border-radius: 10px; border-left: 4px solid #fbbf24;">
                <strong>📋 Project Status:</strong><br>
                This demo is approximately <strong>95% complete</strong>. The frontend UI, navigation,
                and design are fully functional. Some backend features like database integration and
                authentication are planned for future updates as this evolves into a production application.
            </p>

            <p style="background: rgba(255, 255, 255, 0.15); padding: 15px; border-radius: 10px; border-left: 4px solid #4ade80;">
                <strong>💡 What You Can Explore:</strong><br>
                ✓ Complete UI/UX with responsive design<br>
                ✓ Interactive dashboards and charts<br>
                ✓ All module interfaces and pages<br>
                ✓ Professional navigation system<br>
                ✓ AI-generated realistic dummy data
            </p>

            <p style="font-size: 18px; margin-top: 20px;">
                <strong>Have fun exploring! 🚀</strong>
            </p>
        </div>

        <div class="welcome-footer">
            <button class="btn-explore" onclick="closeWelcome()">
                Let's Explore! <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
</div>

<script>
// Welcome Modal JavaScript - Shows once per session
window.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('welcomeModal');
    const hasSeenModal = sessionStorage.getItem('hasSeenWelcomeModal');

    // Show modal only if user hasn't seen it in this session
    if (!hasSeenModal) {
        modal.style.display = 'flex';
    }
});

function closeWelcome() {
    const modal = document.getElementById('welcomeModal');
    modal.style.opacity = '0';
    setTimeout(() => {
        modal.style.display = 'none';
        // Mark that user has seen the modal in this session
        sessionStorage.setItem('hasSeenWelcomeModal', 'true');
    }, 300);
}
</script>
