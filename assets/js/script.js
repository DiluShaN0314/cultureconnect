document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // Disable default HTML5 validation bubbles (just in case any are left)
        form.setAttribute('novalidate', true);
        
        form.addEventListener('submit', function(e) {
            let hasErrors = false;
            
            // Collect inputs to validate
            const requiredInputs = form.querySelectorAll('[data-required="true"]');
            const emailInputs = form.querySelectorAll('input[type="email"]');
            const urlInputs = form.querySelectorAll('input[type="url"]');
            const passwordInput = form.querySelector('input[name="password"]');
            const confirmPasswordInput = form.querySelector('input[name="confirm_password"]');
            const phoneInputs = form.querySelectorAll('input[name="phone"]');
            
            // Clear prior errors
            clearAllErrors(form);
            
            // Check required fields
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    const label = getLabelText(form, input);
                    showFieldError(input, `${label} is required.`);
                    hasErrors = true;
                }
            });
            
            // Check email fields
            emailInputs.forEach(input => {
                if (input.value && !validateEmail(input.value)) {
                    showFieldError(input, `Please enter a valid email address.`);
                    hasErrors = true;
                }
            });

            // Check URL fields
            urlInputs.forEach(input => {
                if (input.value && !validateURL(input.value)) {
                    showFieldError(input, `Please enter a valid URL (starting with http:// or https://).`);
                    hasErrors = true;
                }
            });

            // Check phone fields
            phoneInputs.forEach(input => {
                if (input.value && !validatePhone(input.value)) {
                    showFieldError(input, `Please enter a valid phone number (7-15 digits, + and spaces allowed).`);
                    hasErrors = true;
                }
            });
            
            // Check Matching Passwords
            if (passwordInput && confirmPasswordInput && passwordInput.value && confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                showFieldError(confirmPasswordInput, "Passwords do not match.");
                hasErrors = true;
            }
            
            if (hasErrors) {
                e.preventDefault();
                // Scroll to first error
                const firstError = form.querySelector('.invalid-field');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    });

    // Profile Dropdown Toggle
    const profileBtn = document.querySelector('.profile-btn');
    const dropdownContent = document.querySelector('.dropdown-content');

    if (profileBtn && dropdownContent) {
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownContent.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileBtn.contains(e.target)) {
                dropdownContent.classList.remove('show');
            }
        });
    }

    // Success Banner Auto-dismiss
    const successBanner = document.getElementById('success-banner');
    if (successBanner) {
        // Show banner with small delay for animation feel
        setTimeout(() => {
            successBanner.classList.add('show');
        }, 100);

        // Hide banner after 4 seconds
        setTimeout(() => {
            successBanner.classList.remove('show');
        }, 4000);
    }

    // Interests Snippet Toggle
    document.querySelectorAll('.interests-toggle').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const container = this.closest('.interests-container');
            const snippet = container.querySelector('.interests-snippet');
            
            // Close other snippets first for cleaner UI
            document.querySelectorAll('.interests-snippet.active').forEach(openSnippet => {
                if (openSnippet !== snippet) {
                    openSnippet.classList.remove('active');
                }
            });

            snippet.classList.toggle('active');
        });
    });

    // Close interest snippet when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.interests-container')) {
            document.querySelectorAll('.interests-snippet.active').forEach(snippet => {
                snippet.classList.remove('active');
            });
        }
    });

    // Custom Multi-select Component Logic
    const multiselects = document.querySelectorAll('.custom-multiselect');
    
    multiselects.forEach(ms => {
        const trigger = ms.querySelector('.multiselect-trigger');
        const dropdown = ms.querySelector('.multiselect-dropdown');
        const options = ms.querySelectorAll('.multiselect-option');
        const labelText = trigger.querySelector('span');

        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            ms.classList.toggle('active');
        });

        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const checkbox = this.querySelector('input[type="checkbox"]');
                
                // If the click wasn't directly on the checkbox, toggle it
                if (e.target !== checkbox) {
                    checkbox.checked = !checkbox.checked;
                }
                
                this.classList.toggle('selected', checkbox.checked);
                updateSelectionCount(trigger, ms);
            });
        });

        // Initialize display
        updateSelectionCount(trigger, ms);
    });

    function updateSelectionCount(trigger, container) {
        const checked = container.querySelectorAll('input[type="checkbox"]:checked');
        const labelText = trigger.querySelector('.trigger-text');
        const countBadge = trigger.querySelector('.selected-count') || document.createElement('span');
        
        if (checked.length > 0) {
            countBadge.className = 'selected-count';
            countBadge.textContent = checked.length;
            if (!trigger.querySelector('.selected-count')) {
                trigger.appendChild(countBadge);
            }
            labelText.textContent = "Selected Topics";
        } else {
            if (trigger.querySelector('.selected-count')) {
                countBadge.remove();
            }
            labelText.textContent = "Select Options...";
        }
    }

    // Close all multiselects when clicking outside
    document.addEventListener('click', () => {
        multiselects.forEach(ms => ms.classList.remove('active'));
    });

    // Password Strength Meter Interaction
    const pwdFields = document.querySelectorAll('input[type="password"]');
    pwdFields.forEach(pwd => {
        if (pwd.name === 'password' || pwd.id === 'password') {
            // Check if this is a login form (we don't want strength meter on login)
            const form = pwd.closest('form');
            if (form && (form.action.includes('/login') || form.getAttribute('action') === '/cultureconnect/login')) {
                return;
            }

            const wrapper = document.createElement('div');
            wrapper.className = 'strength-meter-wrapper';
            wrapper.innerHTML = `
                <div class="strength-meter-bar">
                    <div class="strength-meter-fill"></div>
                </div>
                <span class="strength-text"></span>
            `;
            pwd.parentNode.appendChild(wrapper);

            pwd.addEventListener('input', function() {
                const strength = calculatePasswordStrength(this.value);
                updateStrengthUI(wrapper, strength);
            });
        }
    });

    function calculatePasswordStrength(password) {
        if (password.length === 0) return { score: 0, label: '', class: '' };
        if (password.length < 6) return { score: 1, label: 'Very Weak', class: 'low' };
        
        let score = 1;
        if (password.length >= 8) score++;
        if (password.length >= 10) score++;
        
        const hasNumber = /[0-9]/.test(password);
        const hasUpper = /[A-Z]/.test(password);
        const hasSpecial = /[^A-Za-z0-9]/.test(password);

        if (hasNumber || hasUpper || hasSpecial) score++;
        if (hasNumber && hasUpper && hasSpecial) score += 2;

        if (score <= 2) return { score: score, label: 'Low', class: 'low' };
        if (score <= 4) return { score: score, label: 'Intermediate', class: 'intermediate' };
        return { score: score, label: 'Strong', class: 'strong' };
    }

    function updateStrengthUI(wrapper, strength) {
        const fill = wrapper.querySelector('.strength-meter-fill');
        const text = wrapper.querySelector('.strength-text');
        
        fill.className = 'strength-meter-fill'; // Reset
        text.className = 'strength-text'; // Reset

        if (strength.score > 0) {
            fill.classList.add('strength-' + strength.class);
            text.classList.add('text-' + strength.class);
            text.textContent = strength.label;
        } else {
            text.textContent = '';
        }
    }
});

function getLabelText(form, input) {
    const label = form.querySelector(`label[for="${input.id}"]`);
    if (label) {
        return label.textContent.replace('*', '').replace(':', '').trim();
    }
    const prevLabel = input.previousElementSibling;
    if (prevLabel && prevLabel.tagName === 'LABEL') {
        return prevLabel.textContent.replace('*', '').replace(':', '').trim();
    }
    return input.placeholder || input.name || "Field";
}

function showFieldError(input, message) {
    input.classList.add('invalid-field');
    
    // Check if error message already exists
    let errorSpan = input.parentNode.querySelector('.error-text');
    if (!errorSpan) {
        errorSpan = document.createElement('span');
        errorSpan.className = 'error-text';
        input.parentNode.appendChild(errorSpan);
    }
    errorSpan.textContent = message;
}

function clearAllErrors(form) {
    form.querySelectorAll('.invalid-field').forEach(el => el.classList.remove('invalid-field'));
    form.querySelectorAll('.error-text').forEach(el => el.remove());
    
    // Also remove the top summary if it exists from server-side or previous versions
    const summary = form.querySelector('.error-message');
    if (summary) summary.remove();
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

function validateURL(url) {
    try {
        if (!url.startsWith('http://') && !url.startsWith('https://')) return false;
        new URL(url);
        return true;
    } catch (_) {
        return false;
    }
}

function validatePhone(phone) {
    const re = /^[0-9\+\s]{7,15}$/;
    return re.test(String(phone));
}
