// Data storage
let shipments = [];
let companies = [];
let currentUser = null;

// Navigation functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeNavigation();
    initializeTabs();
    initializeSubmenu();
    initializeKeyboardShortcuts();
    initializeForms();
    initializeSearch();
    initializeModals();
    initializeDemoCredentials();
});

function initializeNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.content-area');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove active class from all links
            navLinks.forEach(l => l.classList.remove('active'));

            // Add active class to clicked link
            this.classList.add('active');

            // Hide all sections
            sections.forEach(section => section.classList.add('hidden'));

            // Show target section
            const target = this.getAttribute('data-section') + '-section';
            const targetSection = document.getElementById(target);
            if (targetSection) {
                targetSection.classList.remove('hidden');
            }

            // Update page title
            const pageTitle = document.getElementById('page-title');
            if (pageTitle) {
                pageTitle.textContent = this.textContent.trim();
            }
        });
    });
}

function initializeTabs() {
    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabGroup = this.closest('.content-area');
            if (!tabGroup) return;

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

function initializeSubmenu() {
    const logistics.tems = document.querySelectorAll('.logistics.item');
    logistics.tems.forEach(item => {
        item.addEventListener('click', function() {
            const logistics.ype = this.getAttribute('data-logistics.);
            showSubmenuDetail(logistics.ype);
        });
    });
}

function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey) {
            switch(e.key) {
                case 'F1':
                    e.preventDefault();
                    showNewShipmentForm();
                    break;
                case 'u':
                case 'U':
                    e.preventDefault();
                    showSection('users');
                    break;
                case 'e':
                case 'E':
                    e.preventDefault();
                    showSection('employee');
                    break;
                case 'g':
                case 'G':
                    e.preventDefault();
                    showSection('settings');
                    break;
            }
        }

        if (e.altKey && e.key === 's') {
            e.preventDefault();
            alert('Opening Service management...');
        }
    });
}

function initializeForms() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        // Skip login form to allow normal submission
        if (form.id === 'login-form') return;

        form.addEventListener('submit', function(e) {
            if (this.id === 'shipment-form') {
                e.preventDefault();
                handleShipmentSubmission(this);
            } else if (this.id === 'company-form') {
                e.preventDefault();
                handleCompanySubmission(this);
            }
            // Let other forms submit normally to Laravel
        });
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

function initializeModals() {
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
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

// Helper functions
function showSection(sectionName) {
    const link = document.querySelector(`[data-section="${sectionName}"]`);
    if (link) {
        link.click();
    }
}

function showNewShipmentForm() {
    showSection('shipment');
    setTimeout(() => {
        const newShipmentTab = document.querySelector('[data-tab="new-shipment"]');
        if (newShipmentTab) {
            newShipmentTab.click();
        }
    }, 100);
}

function handleShipmentSubmission(form) {
    // This will be handled by Laravel now
    console.log('Shipment form submitted');
}

function handleCompanySubmission(form) {
    // This will be handled by Laravel now
    console.log('Company form submitted');
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

function showSubmenuDetail(logistics.ype) {
    // Hide logistics.overview
    const overview = document.getElementById('logistics.overview');
    if (overview) overview.classList.add('hidden');

    // Hide all logistics.details
    const allDetails = document.querySelectorAll('.logistics.detail');
    allDetails.forEach(detail => detail.classList.add('hidden'));

    // Show specific logistics.detail
    const targetDetail = document.getElementById(logistics.ype + '-management');
    if (targetDetail) {
        targetDetail.classList.remove('hidden');
    }

    // Show back button
    const backBtn = document.getElementById('back-to-logistics.);
    if (backBtn) backBtn.style.display = 'inline-block';
}

function showSubmenuOverview() {
    // Show logistics.overview
    const overview = document.getElementById('logistics.overview');
    if (overview) overview.classList.remove('hidden');

    // Hide all logistics.details
    const allDetails = document.querySelectorAll('.logistics.detail');
    allDetails.forEach(detail => detail.classList.add('hidden'));

    // Hide back button
    const backBtn = document.getElementById('back-to-logistics.);
    if (backBtn) backBtn.style.display = 'none';
}

function switchSubmenuTab(tabName) {
    // Find the active tab content container
    const activeDetail = document.querySelector('.logistics.detail:not(.hidden)');
    if (activeDetail) {
        // Remove active from all tabs in this detail
        const tabs = activeDetail.querySelectorAll('.tab');
        const tabContents = activeDetail.querySelectorAll('.tab-content');

        tabs.forEach(tab => tab.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        // Add active to target tab and content
        const targetTab = activeDetail.querySelector(`[data-tab="${tabName}"]`);
        const targetContent = activeDetail.querySelector(`#${tabName}-tab`);

        if (targetTab) targetTab.classList.add('active');
        if (targetContent) targetContent.classList.add('active');
    }
}

function fillDemoCredentials(username, password) {
    const usernameField = document.getElementById('username');
    const passwordField = document.getElementById('password');

    if (usernameField) usernameField.value = username;
    if (passwordField) passwordField.value = password;
}

function trackShipment() {
    const trackingId = document.getElementById('tracking-input').value;
    if (trackingId) {
        fetch(`/shipments/${trackingId}/track`)
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('tracking-results');
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
            })
            .catch(error => {
                alert('Shipment not found');
            });
    }
}

// Make functions available globally
window.openModal = openModal;
window.closeModal = closeModal;
window.showSubmenuDetail = showSubmenuDetail;
window.showSubmenuOverview = showSubmenuOverview;
window.switchSubmenuTab = switchSubmenuTab;
window.trackShipment = trackShipment;

console.log('logistics JavaScript loaded successfully');
console.log('Available keyboard shortcuts:');
console.log('- Ctrl+F1: Create new shipment');
console.log('- Ctrl+U: Users section');
console.log('- Ctrl+E: Employee section');
console.log('- Ctrl+G: Change password');
console.log('- Alt+S: Service management');
