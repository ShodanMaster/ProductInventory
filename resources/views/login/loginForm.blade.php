<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>ProductInventory</title>
</head>
<body>

    <div class="container mt-5 d-flex justify-content-center">
        <div class="card shadow-lg rounded-3" style="width: 100%; max-width: 400px;">
            <div class="card">
                <div class="card-header bg-success text-white text-center fs-4" id="formTitle">
                    Login Form
                </div>
                <div class="card-body">
                    <!-- Login Form -->
                    <form id="loginForm" action="{{route('logingin')}}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="loginPassword" placeholder="Enter your password" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" onclick="togglePasswordVisibility('login')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 py-2">Login</button>
                    </form>

                    <!-- Register Form -->
                    <form id="registerForm" action="{{route('userregister')}}" method="POST" onsubmit="return validatePasswords()" style="display: none;">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="name" id="username" placeholder="Enter your username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Enter your username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="registerPassword" name="password" placeholder="Enter your password" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePasswordRegister" onclick="togglePasswordVisibility('register')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="repassword" class="form-label">Re-password</label>
                            <input type="password" class="form-control" id="repassword" name="password_confirmation" placeholder="Re-enter your password" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 py-2">Register</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <small>
                        Don't have an account?
                        <a href="javascript:void(0)" class="text-decoration-none" id="toggleButton" onclick="toggleForms()">Register</a>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle between Login and Register Forms
        function toggleForms() {
            const loginForm = document.getElementById("loginForm");
            const registerForm = document.getElementById("registerForm");
            const formTitle = document.getElementById("formTitle");
            const toggleButton = document.getElementById("toggleButton");

            if (loginForm.style.display === "none") {
                loginForm.style.display = "block";
                registerForm.style.display = "none";
                formTitle.textContent = "Login Form";
                toggleButton.textContent = "Register";
            } else {
                loginForm.style.display = "none";
                registerForm.style.display = "block";
                formTitle.textContent = "Register Form";
                toggleButton.textContent = "Login";
            }
        }

        // Validate passwords in the Register Form
        function validatePasswords() {
            const password = document.getElementById("registerPassword").value;
            const repassword = document.getElementById("repassword").value;

            if (password !== repassword) {
                alert("Passwords do not match. Please re-enter.");
                return false;
            }
            return true;
        }

        // Toggle password visibility
        function togglePasswordVisibility(formType) {
            const passwordField = document.getElementById(formType === 'register' ? 'registerPassword' : 'loginPassword');
            const eyeIcon = formType === 'register' ? document.getElementById('togglePasswordRegister') : document.getElementById('togglePassword');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            eyeIcon.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
