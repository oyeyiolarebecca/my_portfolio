import './bootstrap';

function setContactStatus(element, message, variant) {
    if (!element) return;

    element.textContent = message || '';
    element.classList.remove('hidden', 'text-emerald-700', 'text-red-700');

    if (variant === 'success') element.classList.add('text-emerald-700');
    if (variant === 'error') element.classList.add('text-red-700');
}

function buildValidationMessage(errors) {
    if (!errors || typeof errors !== 'object') return null;
    const firstField = Object.keys(errors)[0];
    const firstError = firstField ? errors[firstField]?.[0] : null;
    return typeof firstError === 'string' ? firstError : null;
}

function buildMailtoUrl(toAddress, payload) {
    const subject = payload.subject?.trim() ? payload.subject.trim() : 'New portfolio message';
    const body = [
        `Name: ${payload.name || ''}`,
        `Email: ${payload.email || ''}`,
        '',
        payload.message || '',
    ].join('\n');

    const params = new URLSearchParams({
        subject,
        body,
    });

    return `mailto:${encodeURIComponent(toAddress)}?${params.toString()}`;
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contact-form');
    if (!form) return;

    const statusEl = document.getElementById('contact-form-status');
    const submitBtn = document.getElementById('contact-form-submit');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        submitBtn?.setAttribute('disabled', 'disabled');
        setContactStatus(statusEl, 'Opening your email app…', null);

        const formData = new FormData(form);
        const payload = {
            name: String(formData.get('name') || ''),
            email: String(formData.get('email') || ''),
            subject: String(formData.get('subject') || ''),
            message: String(formData.get('message') || ''),
        };

        const toAddress = String(form.dataset.contactTo || '').trim();
        if (!toAddress) {
            setContactStatus(statusEl, 'Missing contact email configuration.', 'error');
            submitBtn?.removeAttribute('disabled');
            return;
        }

        try {
            window.location.href = buildMailtoUrl(toAddress, payload);
            setContactStatus(statusEl, 'Your email app should open with the message prefilled.', 'success');
            form.reset();
        } catch {
            setContactStatus(statusEl, 'Could not open your email app. Please copy/paste and send manually.', 'error');
        } finally {
            submitBtn?.removeAttribute('disabled');
        }
    });
});
