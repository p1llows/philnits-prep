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

// PWA Install Helper
const pwaInstallPlugin = () => ({
    deferredPrompt: null,
    isInstalled: false,
    init() {
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
        });
        window.addEventListener('appinstalled', () => {
            this.isInstalled = true;
            this.deferredPrompt = null;
        });
    },
    installApp() {
        if (this.deferredPrompt) {
            this.deferredPrompt.prompt();
            this.deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    this.isInstalled = true;
                }
                this.deferredPrompt = null;
            });
        } else {
            alert('To download PhilNITS Prep:\n\n• Mobile: Tap browser menu (⋮ or Share) → "Add to Home Screen".\n• Desktop: Click the install icon (⊕) in your browser URL bar.');
        }
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.data('modal', modalPlugin);
    Alpine.data('dropdown', dropdownPlugin);
    Alpine.data('form', formPlugin);
    Alpine.data('pwaInstall', pwaInstallPlugin);
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
