// ====== CONFIG ======
const patterns = {
    name: /^[A-Za-zÀ-ÿ\s'-]{1,100}$/,
    last_name: /^[A-Za-zÀ-ÿ\s'-]{1,200}$/,
    phone: /^\d{9,15}$/,
    email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    user: /^[a-zA-Z0-9_-]{3,20}$/
};

const fieldNames = {
    name: "Nombre",
    last_name: "Apellidos",
    dni: "DNI",
    phone: "Teléfono",
    email: "Correo Electrónico",
    user: "Usuario",
    pass: "Contraseña"
};

// ====== DNI/NIE ======
const validateDNIorNIE = (value) => {
    const regex = /^([XYZ]?\d{7,8})([A-Z])$/;
    const match = value.toUpperCase().match(regex);

    if (!match) return false;

    let number = match[1];
    const letter = match[2];

    if (number.startsWith('X')) number = number.replace('X', '0');
    if (number.startsWith('Y')) number = number.replace('Y', '1');
    if (number.startsWith('Z')) number = number.replace('Z', '2');

    number = parseInt(number, 10);

    const letters = "TRWAGMYFPDXBNJZSQVHLCKE";
    return letter === letters[number % 23];
};

// ====== DOM HELPERS ======
const getFieldElements = (input) => {
    const label = document.querySelector(`label[for="${input.id}"]`);
    const error = input.parentElement.querySelector('.error_msg');
    return { label, error };
};

const setError = (input, label, errorEl, message) => {
    input.classList.add("error_input");
    label?.classList.add("error_label");
    if (errorEl) errorEl.textContent = message;
};

const clearError = (input, label, errorEl) => {
    input.classList.remove("error_input");
    label?.classList.remove("error_label");
    if (errorEl) errorEl.textContent = "";
};

// ====== VALIDACIÓN ======
const validateField = (input) => {
    const { label, error } = getFieldElements(input);

    const value = input.value.trim();
    const pattern = patterns[input.id];
    const fieldName = fieldNames[input.id] || input.id;

    let errorMsg = "";

    if (!value) {
        errorMsg = `El campo ${fieldName} no puede estar vacío`;
    }
    else if (input.id === 'dni' && !validateDNIorNIE(value)) {
        errorMsg = `El ${fieldName} no es válido`;
    }
    else if (pattern && !pattern.test(value)) {
        errorMsg = `Formato inválido`;
    }

    if (errorMsg) {
        setError(input, label, error, errorMsg);
        return false;
    }

    clearError(input, label, error);
    return true;
};

// ====== VALIDAR FORM ======
const validateForm = (form) => {
    const inputs = form.querySelectorAll('input');

    let isValid = true;

    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });

    return isValid;
};

// ====== LIMPIAR ======
const clearErrors = (containerId) => {
    const container = document.getElementById(containerId);

    container.querySelectorAll('input').forEach(input => {
        const { label, error } = getFieldElements(input);
        clearError(input, label, error);
    });
};

// ====== INIT ======
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('register_form');

    if (!form) return; // 🔥 imprescindible en MVC

    form.addEventListener('blur', (e) => {
        if (e.target.matches('input')) {
            validateField(e.target);
        }
    }, true);

    form.addEventListener('input', (e) => {
        if (e.target.matches('input')) {
            validateField(e.target);
        }
    });

    // Submit
    form.addEventListener('submit', (e) => {
        const isValid = validateForm(form);

        if (!isValid) {
            e.preventDefault();
        }
    });
});