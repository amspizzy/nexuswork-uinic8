/**
 * Firebase Realtime Order Tracking
 * Real-time status updates for NexusWork order monitoring
 */

// Import Firebase modules
import { initializeApp, getApps, getApp } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js';
import { getDatabase, ref, onValue } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js';

// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyDwgjV7m8nH15mOzPcl1_C8Kfx34g7AGYc",
    authDomain: "nexuswork-uinic8.firebaseapp.com",
    databaseURL: "https://nexuswork-uinic8-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "nexuswork-uinic8",
    storageBucket: "nexuswork-uinic8.firebasestorage.app",
    messagingSenderId: "335535087896",
    appId: "1:335535087896:web:58a83aea9eb81412fe7e6d"
};

// Initialize or reuse existing Firebase app
const app = getApps().length > 0 ? getApp() : initializeApp(firebaseConfig);
const database = getDatabase(app);

// Listen to all orders in realtime
const ordersRef = ref(database, 'orders');
onValue(ordersRef, (snapshot) => {
    const data = snapshot.val();
    if (data) {
        console.log('[Firebase] Order update received:', Object.keys(data).length, 'orders');
        Object.keys(data).forEach((no_spk) => {
            const order = data[no_spk];
            updateOrderRow(no_spk, order.status_pengerjaan, order.tgl_update, order.updated_by);
        });
    }
});

/**
 * Update order row in DataTable
 */
function updateOrderRow(no_spk, new_status, tgl_update, updated_by) {
    // Find table row with matching SPK badge
    const rows = document.querySelectorAll('#dataTable tbody tr');
    
    rows.forEach((row) => {
        const spkBadge = row.querySelector('.badge.badge-light');
        
        if (spkBadge && spkBadge.textContent.trim() === no_spk) {
            console.log('[Firebase] Updating row for SPK:', no_spk, 'to status:', new_status);
            
            // Find status cell (column 6)
            const statusCell = row.cells[6];
            
            if (statusCell) {
                // Update button active states (original design uses .active class)
                const buttons = statusCell.querySelectorAll('.btn');
                
                buttons.forEach((btn) => {
                    const btnText = btn.textContent.trim();
                    
                    // Remove active from all status buttons
                    if (['ANTRI', 'PRODUKSI', 'PACKING', 'DIKIRIM', 'DITERIMA', 'CANCEL'].includes(btnText)) {
                        btn.classList.remove('active');
                    }
                    
                    // Add active to matching button
                    if (btnText === new_status.toUpperCase()) {
                        btn.classList.add('active');
                    }
                });
                
                // Visual feedback: pulse animation
                row.style.animation = 'pulse-update 0.8s ease-in-out';
                
                // Show toast notification
                showUpdateToast(no_spk, new_status, updated_by);
                
                setTimeout(() => {
                    row.style.animation = '';
                }, 800);
            }
        }
    });
}

/**
 * Show toast notification for status updates
 */
function showUpdateToast(no_spk, status, updated_by) {
    // Check if toast container exists, if not create it
    let toastContainer = document.getElementById('firebase-toast-container');
    
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'firebase-toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
        `;
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'alert alert-info alert-dismissible fade show shadow-sm';
    toast.style.cssText = 'min-width: 300px; margin-bottom: 10px;';
    toast.innerHTML = `
        <strong><i class="fas fa-sync-alt"></i> Status Update</strong><br>
        <small>SPK <strong>${no_spk}</strong> → <span class="badge badge-primary">${status}</span></small><br>
        <small class="text-muted">oleh ${updated_by || 'System'}</small>
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Add CSS animation for pulse effect
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse-update {
        0%, 100% { 
            background-color: transparent; 
            transform: scale(1);
        }
        50% { 
            background-color: rgba(0, 123, 255, 0.12); 
            transform: scale(1.01);
        }
    }
    
    #firebase-toast-container .alert {
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);

console.log('[Firebase] Realtime tracking initialized');
