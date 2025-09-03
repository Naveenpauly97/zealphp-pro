document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.contact-form');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        data['csrf_token'] = csrfToken;

        try {
            const response = await fetch('/api/contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                window.location.replace('/');
                form.reset();
            } else {
                if (result.errors) {
                    Object.keys(result.errors).forEach(field => {
                        const errorSpan = document.getElementById(`${field}-error`);
                        if (errorSpan) {
                            errorSpan.textContent = result.errors[field];
                        }
                    });
                } else {
                    alert(result.message || 'Something went wrong!');
                }
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            alert('An unexpected error occurred. Please try again later.');
        }
    });
});