function changeView() {
    document.getElementById('signIn_Box').classList.toggle('d-none');
    document.getElementById('signUp_Box').classList.toggle('d-none');
}

async function signUp() {
    try {
        const response = await httpPost('signUpProcess.php', {
            f: document.getElementById('fname').value,
            l: document.getElementById('lname').value,
            e: document.getElementById('email').value,
            p: document.getElementById('password').value,
            m: document.getElementById('mobile').value,
            g: document.getElementById('gender').value,
        });
        if (response === 'success') {
            showSuccess('Welcome!', 'Account created. Check your email for offers and recommendations.');
            window.location.replace('home.php');
        } else {
            showError('Oops!', response);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

async function signIn() {
    try {
        const response = await httpPost('signInProcess.php', {
            e: document.getElementById('email1').value,
            pw: document.getElementById('password1').value,
            r: document.getElementById('rememberme').checked,
        });
        if (response === 'success') {
            showSuccess('Good job!', 'Sign In');
            window.location.replace('home.php');
        } else {
            showError('Oops!', response);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

async function handleGoogleCredential(response) {
    try {
        const result = await httpPost('googleSignInProcess.php', {
            credential: response.credential,
        });
        if (result === 'success' || result === 'registered') {
            const msg = result === 'registered'
                ? 'Welcome! Check your email for offers and recommendations.'
                : 'Signed in with Google.';
            showSuccess('Good job!', msg);
            window.location.replace('home.php');
        } else {
            showError('Oops!', result);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

async function handleAdminGoogleCredential(response) {
    try {
        const result = await httpPost('adminGoogleSignInProcess.php', {
            credential: response.credential,
        });
        if (result === 'Success') {
            showSuccess('HELLO !', 'Successfully Login');
            window.location.replace('adminDashboard.php');
        } else {
            showError('Oops!', result);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

function showGoogleNotConfigured() {
    showError(
        'Google Sign-In',
        'Google Sign-In is not configured. Add GOOGLE_CLIENT_ID to your .env file, then add this site URL as an Authorized JavaScript origin in Google Cloud Console.'
    );
}

function renderGoogleFallbackButton(host, label) {
    if (!host || host.dataset.googleRendered) {
        return;
    }
    host.dataset.googleRendered = '1';

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'lc-google-fallback-btn';
    btn.innerHTML = '<span class="lc-google-fallback-icon" aria-hidden="true">G</span><span>' + label + '</span>';
    btn.addEventListener('click', showGoogleNotConfigured);
    host.appendChild(btn);
}

function initGoogleSignIn() {
    const clientId = (window.GOOGLE_CLIENT_ID || '').trim();
    if (!clientId || typeof google === 'undefined' || !google.accounts) {
        renderGoogleFallbackButton(document.getElementById('googleSignInBtn'), 'Sign in with Google');
        renderGoogleFallbackButton(document.getElementById('googleSignUpBtn'), 'Sign up with Google');
        return;
    }

    google.accounts.id.initialize({
        client_id: clientId,
        callback: handleGoogleCredential,
        use_fedcm_for_prompt: true,
        itp_support: true,
    });

    const signInBtn = document.getElementById('googleSignInBtn');
    const signUpBtn = document.getElementById('googleSignUpBtn');

    if (signInBtn) {
        google.accounts.id.renderButton(signInBtn, {
            type: 'standard',
            theme: 'outline',
            size: 'large',
            text: 'signin_with',
            width: signInBtn.offsetWidth || 360,
        });
    }

    if (signUpBtn) {
        google.accounts.id.renderButton(signUpBtn, {
            type: 'standard',
            theme: 'outline',
            size: 'large',
            text: 'signup_with',
            width: signUpBtn.offsetWidth || 360,
        });
    }
}

function initGoogleAdminSignIn() {
    const clientId = (window.GOOGLE_CLIENT_ID || '').trim();
    const adminBtn = document.getElementById('googleAdminSignInBtn');

    if (!clientId || typeof google === 'undefined' || !google.accounts) {
        renderGoogleFallbackButton(adminBtn, 'Sign in with Google');
        return;
    }

    google.accounts.id.initialize({
        client_id: clientId,
        callback: handleAdminGoogleCredential,
        use_fedcm_for_prompt: true,
        itp_support: true,
    });

    if (adminBtn) {
        google.accounts.id.renderButton(adminBtn, {
            type: 'standard',
            theme: 'outline',
            size: 'large',
            text: 'signin_with',
            width: adminBtn.offsetWidth || 360,
        });
        adminBtn.dataset.googleRendered = '1';
    }
}

function bootGoogleAuth() {
    const isAdmin = !!document.getElementById('googleAdminSignInBtn');
    const clientId = (window.GOOGLE_CLIENT_ID || '').trim();

    if (!clientId) {
        if (isAdmin) {
            renderGoogleFallbackButton(document.getElementById('googleAdminSignInBtn'), 'Sign in with Google');
        } else {
            renderGoogleFallbackButton(document.getElementById('googleSignInBtn'), 'Sign in with Google');
            renderGoogleFallbackButton(document.getElementById('googleSignUpBtn'), 'Sign up with Google');
        }
        return;
    }

    const waitForGoogle = setInterval(function () {
        if (typeof google !== 'undefined' && google.accounts) {
            clearInterval(waitForGoogle);
            if (isAdmin) {
                initGoogleAdminSignIn();
            } else {
                initGoogleSignIn();
            }
        }
    }, 100);

    setTimeout(function () {
        clearInterval(waitForGoogle);
        if (isAdmin) {
            initGoogleAdminSignIn();
        } else {
            initGoogleSignIn();
        }
    }, 10000);
}

document.addEventListener('DOMContentLoaded', bootGoogleAuth);

async function signout() {
    await httpPost('signOutProcess.php', {});
    window.location.replace('index.php');
}

function togglePassword(fieldId, buttonId) {
    const field = document.getElementById(fieldId);
    const button = document.getElementById(buttonId);
    if (!field || !button) return;
    field.type = field.type === 'password' ? 'text' : 'password';
    button.innerHTML = field.type === 'password'
        ? "<i class='bi bi-eye-slash-fill'></i>"
        : "<i class='bi bi-eye-fill'></i>";
}

function showPassword2() { togglePassword('password1', 'sp'); }
function showPassword() { togglePassword('pw1', 'sp'); }
function showPassword3() { togglePassword('np', 'npb'); }
function showPassword4() { togglePassword('rnp', 'rnpb'); }

let forgotPasswordModal;

async function forgotPassword() {
    const email = document.getElementById('email1').value;
    if (!email) {
        showError('Oops!', 'Please enter your email address first.');
        return;
    }
    try {
        const text = await httpPost('forgotPasswordProcess.php', { e: email });
        if (text === 'Success') {
            showSuccess('Check your email', 'A 6-digit OTP has been sent to your registered email address.');
            forgotPasswordModal = new bootstrap.Modal(document.getElementById('fpmodal'));
            forgotPasswordModal.show();
        } else {
            showError('Oops!', text);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

async function resetPassword() {
    const emailEl = document.getElementById('email1') || document.getElementById('email');
    try {
        const response = await httpPost('resetPasswordProcess.php', {
            e: emailEl.value,
            n: document.getElementById('np').value,
            r: document.getElementById('rnp').value,
            v: document.getElementById('vcode').value,
        });
        if (response === 'success') {
            showSuccess('Updated', 'Password updated successfully.');
            forgotPasswordModal?.hide();
        } else {
            showError('Oops!', response);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

async function adminSignIn() {
    try {
        const response = await httpPost('adminSignInProcess.php', {
            e: document.getElementById('email').value,
            pw: document.getElementById('pw1').value,
        });
        if (response === 'Success') {
            showSuccess('HELLO !', 'Successfully Login');
            window.location.replace('adminDashboard.php');
        } else {
            showError('Oops!', response);
        }
    } catch (e) {
        showError('Error', e.message);
    }
}

async function adminSignout() {
    await httpPost('adminSignOutProcess.php', {});
    window.location.replace('adminSignIn.php');
}

window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
        window.location.reload();
    }
});
