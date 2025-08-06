// Data storage
let shipments = [
    {
        id: "SH-2025-001",
        client: "Global Trading Co.",
        origin: "Shanghai Port",
        destination: "Los Angeles Port",
        containerType: "40ft Container",
        status: "In Transit",
        eta: "2025-07-15",
    },
];

let companies = [
    {
        name: "Global Trading Co.",
        contact: "John Smith",
        email: "john@globaltrading.com",
        phone: "+1-555-0123",
        country: "USA",
        type: "Client",
        status: "Active",
    },
];

// Navigation functionality
document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll(".nav-link");
    const sections = document.querySelectorAll(".content-area");

    navLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            // Remove active class from all links
            navLinks.forEach((l) => l.classList.remove("active"));

            // Add active class to clicked link
            this.classList.add("active");

            // Hide all sections
            sections.forEach((section) => section.classList.add("hidden"));

            // Show target section
            const target = this.getAttribute("data-section") + "-section";
            const targetSection = document.getElementById(target);
            if (targetSection) {
                targetSection.classList.remove("hidden");
            }

            // Update page title
            const pageTitle = document.getElementById("page-title");
            pageTitle.textContent = this.textContent.trim();
        });
    });

    // Tab functionality
    const tabs = document.querySelectorAll(".tab");
    tabs.forEach((tab) => {
        tab.addEventListener("click", function () {
            const tabGroup = this.closest(".content-area");
            const tabContents = tabGroup.querySelectorAll(".tab-content");
            const targetTab = this.getAttribute("data-tab") + "-tab";

            // Remove active class from all tabs in this group
            tabGroup
                .querySelectorAll(".tab")
                .forEach((t) => t.classList.remove("active"));

            // Add active class to clicked tab
            this.classList.add("active");

            // Hide all tab contents in this group
            tabContents.forEach((content) =>
                content.classList.remove("active")
            );

            // Show target tab content
            const targetContent = document.getElementById(targetTab);
            if (targetContent) {
                targetContent.classList.add("active");
            }
        });
    });

    // logistics.item clicks
    const logistics.tems = document.querySelectorAll(".logistics.item");
    logistics.tems.forEach((item) => {
        item.addEventListener("click", function () {
            const logistics.ype = this.getAttribute("data-logistics.);
            showSubmenuDetail(logistics.ype);
        });
    });

    // Keyboard shortcuts
    document.addEventListener("keydown", function (e) {
        if (e.ctrlKey) {
            switch (e.key) {
                case "F1":
                    e.preventDefault();
                    showNewShipmentForm();
                    break;
                case "u":
                case "U":
                    e.preventDefault();
                    showSection("users");
                    break;
                case "e":
                case "E":
                    e.preventDefault();
                    showSection("employee");
                    break;
                case "g":
                case "G":
                    e.preventDefault();
                    showSection("settings");
                    break;
            }
        }

        if (e.altKey && e.key === "s") {
            e.preventDefault();
            alert("Opening Service management...");
        }
    });

    // Form submissions
    const forms = document.querySelectorAll("form");
    forms.forEach((form) => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            if (this.id === "shipment-form") {
                handleShipmentSubmission(this);
            } else if (this.id === "company-form") {
                handleCompanySubmission(this);
            } else {
                alert("Form submitted successfully!");
            }
        });
    });
});

// Helper functions
function showSection(sectionName) {
    const link = document.querySelector(`[data-section="${sectionName}"]`);
    if (link) {
        link.click();
    }
}

function showNewShipmentForm() {
    showSection("shipment");
    setTimeout(() => {
        const newShipmentTab = document.querySelector(
            '[data-tab="new-shipment"]'
        );
        if (newShipmentTab) {
            newShipmentTab.click();
        }
    }, 100);
}

function handleShipmentSubmission(form) {
    const formData = new FormData(form);
    const newShipment = {
        id: "SH-2025-" + String(shipments.length + 1).padStart(3, "0"),
        client: formData.get("client") || "New Client",
        origin: formData.get("origin") || "Unknown",
        destination: formData.get("destination") || "Unknown",
        containerType: formData.get("containerType") || "40ft Container",
        status: "Pending",
        eta: formData.get("eta") || new Date().toISOString().split("T")[0],
    };

    shipments.push(newShipment);
    updateShipmentsTable();
    form.reset();
    alert("Shipment created successfully!");
}

function handleCompanySubmission(form) {
    const formData = new FormData(form);
    const newCompany = {
        name: formData.get("name") || "New Company",
        contact: formData.get("contact") || "Unknown",
        email: formData.get("email") || "",
        phone: formData.get("phone") || "",
        country: formData.get("country") || "",
        type: formData.get("type") || "Client",
        status: "Active",
    };

    companies.push(newCompany);
    updateCompaniesTable();
    form.reset();
    alert("Company added successfully!");
}

function updateShipmentsTable() {
    const tbody = document.getElementById("shipments-tbody");
    if (tbody) {
        tbody.innerHTML = shipments
            .map(
                (shipment) => `
                    <tr>
                        <td>${shipment.id}</td>
                        <td>${shipment.client}</td>
                        <td>${shipment.origin}</td>
                        <td>${shipment.destination}</td>
                        <td>${shipment.containerType}</td>
                        <td>${shipment.status}</td>
                        <td>${shipment.eta}</td>
                        <td>
                            <button class="btn btn-secondary">Edit</button>
                            <button class="btn btn-primary">Track</button>
                        </td>
                    </tr>
                `
            )
            .join("");
    }
}

function updateCompaniesTable() {
    const tbody = document.getElementById("client-companies-tbody");
    if (tbody) {
        const clientCompanies = companies.filter((c) => c.type === "Client");
        tbody.innerHTML = clientCompanies
            .map(
                (company) => `
                    <tr>
                        <td>${company.name}</td>
                        <td>${company.contact}</td>
                        <td>${company.email}</td>
                        <td>${company.phone}</td>
                        <td>${company.country}</td>
                        <td>${company.status}</td>
                        <td>
                            <button class="btn btn-secondary">Edit</button>
                            <button class="btn btn-primary">View</button>
                        </td>
                    </tr>
                `
            )
            .join("");
    }
}

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

// Close modal when clicking outside
window.addEventListener("click", function (e) {
    if (e.target.classList.contains("modal")) {
        e.target.style.display = "none";
    }
});

// Search functionality
const searchBars = document.querySelectorAll(".search-bar");
searchBars.forEach((searchBar) => {
    searchBar.addEventListener("input", function () {
        const searchTerm = this.value.toLowerCase();
        const table = this.nextElementSibling?.nextElementSibling; // Skip button, get table

        if (table && table.classList.contains("data-table")) {
            const rows = table.querySelectorAll("tbody tr");
            rows.forEach((row) => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? "" : "none";
            });
        }
    });
});

// logistics.navigation functions
function showSubmenuDetail(logistics.ype) {
    // Hide logistics.overview
    document.getElementById("logistics.overview").classList.add("hidden");

    // Hide all logistics.details
    const allDetails = document.querySelectorAll(".logistics.detail");
    allDetails.forEach((detail) => detail.classList.add("hidden"));

    // Show specific logistics.detail
    const targetDetail = document.getElementById(logistics.ype + "-management");
    if (targetDetail) {
        targetDetail.classList.remove("hidden");
    }

    // Show back button
    document.getElementById("back-to-logistics.).style.display = 'inline-block'");
}

function showSubmenuOverview() {
    // Show logistics.overview
    document.getElementById("logistics.overview").classList.remove("hidden");

    // Hide all logistics.details
    const allDetails = document.querySelectorAll(".logistics.detail");
    allDetails.forEach((detail) => detail.classList.add("hidden"));

    // Hide back button
    document.getElementById("back-to-logistics.).style.display = 'none'");
}

function switchSubmenuTab(tabName) {
    // Find the active tab content container
    const activeDetail = document.querySelector(".logistics.detail:not(.hidden)");
    if (activeDetail) {
        // Remove active from all tabs in this detail
        const tabs = activeDetail.querySelectorAll(".tab");
        const tabContents = activeDetail.querySelectorAll(".tab-content");

        tabs.forEach((tab) => tab.classList.remove("active"));
        tabContents.forEach((content) => content.classList.remove("active"));

        // Add active to target tab and content
        const targetTab = activeDetail.querySelector(`[data-tab="${tabName}"]`);
        const targetContent = activeDetail.querySelector(`#${tabName}-tab`);

        if (targetTab) targetTab.classList.add("active");
        if (targetContent) targetContent.classList.add("active");
    }
}
class UserStatusMonitor {
    constructor() {
        this.checkInterval = 30000; // Check every 30 seconds
        this.intervalId = null;
        this.isChecking = false;
        
        this.init();
    }

    init() {
        // Only run if user is authenticated
        if (this.isAuthenticated()) {
            this.startMonitoring();
            
            // Check immediately on page load
            this.checkUserStatus();
            
            // Check when page becomes visible again
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    this.checkUserStatus();
                }
            });
        }
    }

    isAuthenticated() {
        // Check if there's a CSRF token (indicates authenticated session)
        return document.querySelector('meta[name="csrf-token"]') !== null;
    }

    startMonitoring() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
        }
        
        this.intervalId = setInterval(() => {
            this.checkUserStatus();
        }, this.checkInterval);
    }

    stopMonitoring() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
    }

    async checkUserStatus() {
        if (this.isChecking) return;
        
        this.isChecking = true;
        
        try {
            const response = await fetch('/check-user-status', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            const data = await response.json();

            if (!response.ok) {
                this.handleStatusError(data, response.status);
                return;
            }

            // Check if user must change password
            if (data.user && data.user.must_change_password) {
                this.redirectToPasswordChange();
                return;
            }

            // User is still active and good
            console.log('User status check: OK');

        } catch (error) {
            console.warn('User status check failed:', error);
            // Don't do anything drastic on network errors
        } finally {
            this.isChecking = false;
        }
    }

    handleStatusError(data, status) {
        if (status === 401) {
            // Not authenticated - redirect to login
            this.redirectToLogin('Your session has expired. Please log in again.');
        } else if (status === 403) {
            // Account blocked
            this.stopMonitoring();
            this.showBlockedAccountMessage(data.message);
            setTimeout(() => {
                this.redirectToLogin('Your account has been blocked. Please contact your administrator.');
            }, 3000);
        }
    }

    redirectToLogin(message = null) {
        this.stopMonitoring();
        
        if (message) {
            // Store message in session storage to show after redirect
            sessionStorage.setItem('login_message', message);
        }
        
        window.location.href = '/login';
    }

    redirectToPasswordChange() {
        this.stopMonitoring();
        window.location.href = '/change-password';
    }

    showBlockedAccountMessage(message) {
        // Create and show a prominent notification
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #dc2626;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        `;
        notification.innerHTML = `
            <div>🚫 ${message}</div>
            <div style="font-size: 14px; margin-top: 5px;">You will be redirected to the login page...</div>
        `;
        
        document.body.prepend(notification);
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.userStatusMonitor = new UserStatusMonitor();
    
    // Show any stored login messages
    const loginMessage = sessionStorage.getItem('login_message');
    if (loginMessage) {
        sessionStorage.removeItem('login_message');
        
        // Show the message (you can customize this based on your notification system)
        const messageDiv = document.createElement('div');
        messageDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f59e0b;
            color: white;
            padding: 15px;
            border-radius: 8px;
            z-index: 1000;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        messageDiv.textContent = loginMessage;
        document.body.appendChild(messageDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
});

// Enhanced logout function with better error handling
window.logout = function() {
    if (window.userStatusMonitor) {
        window.userStatusMonitor.stopMonitoring();
    }
    
    fetch('/logout', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect || '/login';
        } else {
            // Fallback redirect
            window.location.href = '/login';
        }
    })
    .catch(error => {
        console.error('Logout error:', error);
        // Force redirect even if logout request fails
        window.location.href = '/login';
    });
};

// Export for use in other scripts
window.UserStatusMonitor = UserStatusMonitor;

console.log("Logistics Management System initialized");
console.log("Available keyboard shortcuts:");
console.log("- Ctrl+F1: Create new shipment");
console.log("- Ctrl+U: Users section");
console.log("- Ctrl+E: Employee section");
console.log("- Ctrl+G: Change password");
console.log("- Alt+S: Service management");
