// Main JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('LogiFlow JavaScript loaded from public directory');
    
    initializeApp();
});

function initializeApp() {
    initializeNavigation();
    initializeTabs();
    initializeKeyboardShortcuts();
    initializeSearch();
    initializeDemoCredentials();
}

function initializeNavigation() {
    // Tab functionality
    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabGroup = this.closest('.content-area') || document;
            const tabContents = tabGroup.querySelectorAll('.tab-content');
            const targetTab = this.getAttribute('data-tab') + '-tab';
            
            // Remove active class from all tabs in this group
            tabGroup.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Hide all tab contents in this group
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Show target tab content
            const targetContent = document.getElementById(targetTab);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });
}

function initializeTabs() {
    // Additional tab initialization if needed
}

function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey) {
            switch(e.key) {
                case 'F1':
                    e.preventDefault();
                    alert('Ctrl+F1 pressed - New shipment shortcut');
                    break;
                case 'u':
                case 'U':
                    e.preventDefault();
                    alert('Ctrl+U pressed - Users shortcut');
                    break;
                case 'e':
                case 'E':
                    e.preventDefault();
                    alert('Ctrl+E pressed - Employee shortcut');
                    break;
                case 'g':
                case 'G':
                    e.preventDefault();
                    alert('Ctrl+G pressed - Settings shortcut');
                    break;
            }
        }
    });
}

function initializeSearch() {
    const searchBars = document.querySelectorAll('.search-bar');
    searchBars.forEach(searchBar => {
        searchBar.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = this.nextElementSibling?.nextElementSibling; // Skip button, get table
            
            if (table && table.classList.contains('data-table')) {
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            }
        });
    });
}

function initializeDemoCredentials() {
    const credentialItems = document.querySelectorAll('.credential-item');
    credentialItems.forEach(item => {
        item.addEventListener('click', function() {
            const text = this.textContent;
            if (text.includes('admin / admin123')) {
                fillDemoCredentials('admin', 'admin123');
            } else if (text.includes('manager / manager123')) {
                fillDemoCredentials('manager', 'manager123');
            } else if (text.includes('user / user123')) {
                fillDemoCredentials('user', 'user123');
            }
        });
    });
}

function fillDemoCredentials(username, password) {
    const usernameField = document.getElementById('username');
    const passwordField = document.getElementById('password');
    
    if (usernameField) usernameField.value = username;
    if (passwordField) passwordField.value = password;
}

function trackShipment(shipmentId = null) {
    const trackingId = shipmentId || document.getElementById('tracking-input')?.value;
    if (trackingId) {
        // Make AJAX request to track shipment
        fetch(`/shipments/${trackingId}/track`)
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('tracking-results');
                if (resultsDiv) {
                    resultsDiv.innerHTML = `
                        <h4>Tracking Results: ${data.shipment_id}</h4>
                        <div style="margin-top: 15px;">
                            ${data.tracking_events.map(event => `
                                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                    <div style="width: 20px; height: 20px; background: ${event.color}; border-radius: 50%; margin-right: 15px;"></div>
                                    <div>
                                        <strong>${event.status}</strong><br>
                                        <small>${event.location} - ${event.timestamp}</small>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `;
                    resultsDiv.style.display = 'block';
                }
            })
            .catch(error => {
                alert('Shipment not found or tracking information unavailable');
                console.error('Tracking error:', error);
            });
    }
}

// Make functions available globally
window.trackShipment = trackShipment;