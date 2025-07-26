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
    document.getElementById("back-to-logistics.).style.display = "inline-block";
}

function showSubmenuOverview() {
    // Show logistics.overview
    document.getElementById("logistics.overview").classList.remove("hidden");

    // Hide all logistics.details
    const allDetails = document.querySelectorAll(".logistics.detail");
    allDetails.forEach((detail) => detail.classList.add("hidden"));

    // Hide back button
    document.getElementById("back-to-logistics.).style.display = "none";
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

console.log("Logistics Management System initialized");
console.log("Available keyboard shortcuts:");
console.log("- Ctrl+F1: Create new shipment");
console.log("- Ctrl+U: Users section");
console.log("- Ctrl+E: Employee section");
console.log("- Ctrl+G: Change password");
console.log("- Alt+S: Service management");
