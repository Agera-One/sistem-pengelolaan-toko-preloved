const LABEL_DEFAULT = 'Masuk';
const LABEL_LOADING = 'Memproses...';

function initLoginPage() {
    const form = document.getElementById('login-form');
    if (!form) return;

    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('toggle-password');
    const submitButton = document.getElementById('login-submit');
    const submitLabel = document.getElementById('login-submit-label');
    const spinner = document.getElementById('login-spinner');

    const iconShow = toggleButton?.querySelector('[data-icon="show"]');
    const iconHide = toggleButton?.querySelector('[data-icon="hide"]');

    let isSubmitting = false;

    function setPasswordVisible(visible) {
        if (!passwordInput || !toggleButton) return;

        const text = visible ? 'Sembunyikan password' : 'Tampilkan password';

        passwordInput.type = visible ? 'text' : 'password';
        toggleButton.setAttribute('aria-pressed', String(visible));
        toggleButton.setAttribute('aria-label', text);
        toggleButton.title = text;

        iconShow?.classList.toggle('hidden', visible);
        iconHide?.classList.toggle('hidden', !visible);
    }

    toggleButton?.addEventListener('click', () => {
        setPasswordVisible(passwordInput.type === 'password');
    });

    function setLoading(loading) {
        if (!submitButton || !submitLabel) return;

        submitButton.disabled = loading;
        submitButton.setAttribute('aria-busy', String(loading));
        spinner?.classList.toggle('hidden', !loading);
        submitLabel.textContent = loading ? LABEL_LOADING : LABEL_DEFAULT;
    }

    form.addEventListener('submit', (event) => {
        if (isSubmitting) {
            event.preventDefault();
            return;
        }

        isSubmitting = true;
        setPasswordVisible(false);
        setLoading(true);
    });

    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            isSubmitting = false;
            setLoading(false);
            setPasswordVisible(false);
        }
    });

    const firstInvalid = form.querySelector('[aria-invalid="true"]');
    (firstInvalid ?? emailInput)?.focus();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLoginPage);
} else {
    initLoginPage();
}
