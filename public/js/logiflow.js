// Main JavaScript functionality
document.addEventListener("DOMContentLoaded", function () {
    console.log("logisphere JavaScript loaded from public directory");

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
    const tabs = document.querySelectorAll(".tab");
    tabs.forEach((tab) => {
        tab.addEventListener("click", function () {
            const tabGroup = this.closest(".content-area") || document;
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
}

function initializeTabs() {
    // Additional tab initialization if needed
}

function initializeKeyboardShortcuts() {
    document.addEventListener("keydown", function (e) {
        if (e.ctrlKey) {
            switch (e.key) {
                case "F1":
                    e.preventDefault();
                    alert("Ctrl+F1 pressed - New shipment shortcut");
                    break;
                case "u":
                case "U":
                    e.preventDefault();
                    alert("Ctrl+U pressed - Users shortcut");
                    break;
                case "e":
                case "E":
                    e.preventDefault();
                    alert("Ctrl+E pressed - Employee shortcut");
                    break;
                case "g":
                case "G":
                    e.preventDefault();
                    alert("Ctrl+G pressed - Settings shortcut");
                    break;
            }
        }
    });
}

function initializeSearch() {
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
}

function initializeDemoCredentials() {
    const credentialItems = document.querySelectorAll(".credential-item");
    credentialItems.forEach((item) => {
        item.addEventListener("click", function () {
            const text = this.textContent;
            if (text.includes("admin / admin123")) {
                fillDemoCredentials("admin", "admin123");
            } else if (text.includes("manager / manager123")) {
                fillDemoCredentials("manager", "manager123");
            } else if (text.includes("user / user123")) {
                fillDemoCredentials("user", "user123");
            }
        });
    });
}

function fillDemoCredentials(username, password) {
    const usernameField = document.getElementById("username");
    const passwordField = document.getElementById("password");

    if (usernameField) usernameField.value = username;
    if (passwordField) passwordField.value = password;
}

function trackShipment(shipmentId = null) {
    const trackingId =
        shipmentId || document.getElementById("tracking-input")?.value;
    if (trackingId) {
        // Make AJAX request to track shipment
        fetch(`/shipments/${trackingId}/track`)
            .then((response) => response.json())
            .then((data) => {
                const resultsDiv = document.getElementById("tracking-results");
                if (resultsDiv) {
                    resultsDiv.innerHTML = `
                        <h4>Tracking Results: ${data.shipment_id}</h4>
                        <div style="margin-top: 15px;">
                            ${data.tracking_events
                                .map(
                                    (event) => `
                                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                                    <div style="width: 20px; height: 20px; background: ${event.color}; border-radius: 50%; margin-right: 15px;"></div>
                                    <div>
                                        <strong>${event.status}</strong><br>
                                        <small>${event.location} - ${event.timestamp}</small>
                                    </div>
                                </div>
                            `
                                )
                                .join("")}
                        </div>
                    `;
                    resultsDiv.style.display = "block";
                }
            })
            .catch((error) => {
                alert("Shipment not found or tracking information unavailable");
                console.error("Tracking error:", error);
            });
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
            document.addEventListener("visibilitychange", () => {
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
            const response = await fetch("/check-user-status", {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                },
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
            console.log("User status check: OK");
        } catch (error) {
            console.warn("User status check failed:", error);
            // Don't do anything drastic on network errors
        } finally {
            this.isChecking = false;
        }
    }

    handleStatusError(data, status) {
        if (status === 401) {
            // Not authenticated - redirect to login
            this.redirectToLogin(
                "Your session has expired. Please log in again."
            );
        } else if (status === 403) {
            // Account blocked
            this.stopMonitoring();
            this.showBlockedAccountMessage(data.message);
            setTimeout(() => {
                this.redirectToLogin(
                    "Your account has been blocked. Please contact your administrator."
                );
            }, 3000);
        }
    }

    redirectToLogin(message = null) {
        this.stopMonitoring();

        if (message) {
            // Store message in session storage to show after redirect
            sessionStorage.setItem("login_message", message);
        }

        window.location.href = "/login";
    }

    redirectToPasswordChange() {
        this.stopMonitoring();
        window.location.href = "/change-password";
    }

    showBlockedAccountMessage(message) {
        // Create and show a prominent notification
        const notification = document.createElement("div");
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
document.addEventListener("DOMContentLoaded", function () {
    window.userStatusMonitor = new UserStatusMonitor();

    // Show any stored login messages
    const loginMessage = sessionStorage.getItem("login_message");
    if (loginMessage) {
        sessionStorage.removeItem("login_message");

        // Show the message (you can customize this based on your notification system)
        const messageDiv = document.createElement("div");
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
window.logout = function () {
    if (window.userStatusMonitor) {
        window.userStatusMonitor.stopMonitoring();
    }

    fetch("/logout", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN":
                document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content") || "",
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                window.location.href = data.redirect || "/login";
            } else {
                // Fallback redirect
                window.location.href = "/login";
            }
        })
        .catch((error) => {
            console.error("Logout error:", error);
            // Force redirect even if logout request fails
            window.location.href = "/login";
        });
};

// Export for use in other scripts
window.UserStatusMonitor = UserStatusMonitor;
// Make functions available globally
window.trackShipment = trackShipment;
