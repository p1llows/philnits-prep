import Alpine from 'alpinejs';

// Modal utilities
const modalPlugin = () => ({
    modals: {},
    openModal(name) {
        this.modals[name] = true;
        document.body.style.overflow = 'hidden';
    },
    closeModal(name) {
        this.modals[name] = false;
        document.body.style.overflow = '';
    },
    isModalOpen(name) {
        return this.modals[name] || false;
    },
});

// Dropdown utilities
const dropdownPlugin = () => ({
    dropdowns: {},
    toggleDropdown(name) {
        this.dropdowns[name] = !this.dropdowns[name];
        if (this.dropdowns[name]) {
            setTimeout(() => {
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.dropdown-content')) {
                        this.dropdowns[name] = false;
                    }
                });
            }, 0);
        }
    },
    closeDropdown(name) {
        this.dropdowns[name] = false;
    },
    isOpen(name) {
        return this.dropdowns[name] || false;
    },
});

// Form helpers
const formPlugin = () => ({
    errors: {},
    processing: false,
    
    setErrors(errors) {
        this.errors = errors;
    },
    
    clearErrors() {
        this.errors = {};
    },
    
    setProcessing(isProcessing) {
        this.processing = isProcessing;
    },
});

document.addEventListener('alpine:init', () => {
    Alpine.data('modal', modalPlugin);
    Alpine.data('dropdown', dropdownPlugin);
    Alpine.data('form', formPlugin);
});

Alpine.start();

// Register Service Worker for PWA support
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch((err) => {
            console.warn('PWA service worker registration failed:', err);
        });
    });
}
