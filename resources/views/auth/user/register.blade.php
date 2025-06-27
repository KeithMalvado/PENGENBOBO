<!-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User | EvenTara</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', sans-serif;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
        }
        .left img {
            max-width: 80%;
            height: auto;
        }
        .right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .form-wrapper {
            width: 100%;
            max-width: 400px;
        }
        .form-wrapper h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 14px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
        }
        .btn-submit, .btn-google {
            width: 100%;
            padding: 14px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s;
        }
        .btn-submit {
            background-color: #F4A825;
            border: none;
            color: #fff;
            margin-bottom: 15px;
        }
        .btn-submit:hover {
            background-color: #d9931f;
        }
        .btn-google {
            background-color: #fff;
            border: 1px solid #ccc;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-google img {
            width: 18px;
            height: 18px;
            margin-right: 10px;
        }
        .btn-google:hover {
            background-color: #f1f1f1;
        }
        .text-center {
            text-align: center;
        }
        .small {
            font-size: 14px;
        }
    </style>

    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-auth-compat.js"></script>
    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyCnEmQjjseWuBAPKlvXQK2gDgDR0rmiJpQ",
            authDomain: "to-do-list-41416.firebaseapp.com.firebaseapp.com",
            projectId: "to-do-list-41416",
            appId: "111691320557"
        };
        firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();
        const provider = new firebase.auth.GoogleAuthProvider();
    </script>
</head>
<body>
    <div class="container">
        <div class="left">
            <img src="{{ asset('images/registeruser.png') }}" alt="Register User">
        </div>
        <div class="right">
            <div class="form-wrapper">
                <h3>Register Account</h3>
                <form action="{{ route('user.register') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="email@email.com" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
                    </div>
                    <button type="submit" class="btn-submit">Daftar Manual →</button>
                </form>
                <div class="text-center small" style="margin: 10px 0;">atau</div>
                <a href="#" class="btn-google" id="googleLogin">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" alt="Google Logo">
                    Daftar dengan Google
                </a>
                <p class="text-center small" style="margin-top: 15px;">
                    Sudah punya akun?
                    <a href="{{ route('user.login') }}" style="color: #F4A825; text-decoration: none;">Login</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('googleLogin').addEventListener('click', function(e) {
            e.preventDefault();
            auth.signInWithPopup(provider)
                .then(async (result) => {
                    const idToken = await result.user.getIdToken();
                    fetch("{{ route('user.google.callback') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ idToken: idToken })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = "{{ route('user.landingpage') }}";
                        } else {
                            alert("Login gagal: " + data.message);
                        }
                    });
                })
                .catch(error => {
                    alert("Login gagal: " + error.message);
                });
        });
    </script>
</body>
</html> -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User | EvenTara</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; }
        .container { display: flex; min-height: 100vh; }
        .left { flex: 1; display: flex; align-items: center; justify-content: center; background-color: #fff; }
        .left img { max-width: 80%; height: auto; }
        .right { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px; }
        .form-wrapper { width: 100%; max-width: 400px; }
        .form-wrapper h3 { font-size: 20px; font-weight: 600; margin-bottom: 30px; text-align: center; }
        .form-group { margin-bottom: 15px; }
        .form-group input { width: 100%; padding: 14px; font-size: 14px; border: 1px solid #ccc; border-radius: 4px; font-family: 'Poppins', sans-serif; }
        .btn-submit, .btn-google { width: 100%; padding: 14px; font-size: 14px; font-weight: 500; cursor: pointer; border-radius: 4px; font-family: 'Poppins', sans-serif; transition: all 0.2s; }
        .btn-submit { background-color: #F4A825; border: none; color: #fff; margin-bottom: 15px; }
        .btn-submit:hover { background-color: #d9931f; }
        .btn-google { background-color: #fff; border: 1px solid #ccc; color: #333; display: flex; align-items: center; justify-content: center; }
        .btn-google img { width: 18px; height: 18px; margin-right: 10px; }
        .btn-google:hover { background-color: #f1f1f1; }
        .text-center { text-align: center; }
        .small { font-size: 14px; }

       
        .alert-danger {
            background-color: #f8d7da; 
            color: #721c24;
            border: 1px solid #f5c6cb; 
            padding: 10px 15px; 
            margin-bottom: 20px; 
            border-radius: 4px; 
            text-align: center; 
            font-size: 14px;
        }
        .alert-success {
            background-color: #d4edda; 
            color: #155724;
            border: 1px solid #c3e6cb; 
            padding: 10px 15px; 
            margin-bottom: 20px; 
            border-radius: 4px; 
            text-align: center; 
            font-size: 14px;
        }
    </style>

    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-auth-compat.js"></script>
    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyCnEmQjjseWuBAPKlvXQK2gDgDR0rmiJpQ",
            authDomain: "to-do-list-41416.firebaseapp.com",
            projectId: "to-do-list-41416",
            appId: "111691320557"
        };
        firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();
        const provider = new firebase.auth.GoogleAuthProvider();
    </script>
</head>
<body>
    <div class="container">
        <div class="left">
            <img src="{{ asset('images/registeruser.png') }}" alt="Register User">
        </div>
        <div class="right">
            <div class="form-wrapper">
                <h3>Register Account</h3>

                <!-- Alert Messages -->
                <div id="alert-message"></div>

                <form id="registerForm">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="email@email.com" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
                    </div>
                    <button type="submit" class="btn-submit">Daftar →</button>
                </form>

                <p class="text-center small" style="margin-top: 15px;">
                    Sudah punya akun?
                    <a href="{{ route('user.login') }}" style="color: #F4A825; text-decoration: none;">Login</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        const alertBox = document.getElementById('alert-message');

        function showAlert(message, type = 'danger') {
            alertBox.innerHTML = `<div class="alert-${type}">${message}</div>`;
        }

        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            alertBox.innerHTML = '';

            const name = this.name.value.trim();
            const email = this.email.value.trim();
            const password = this.password.value;
            const confirmPassword = this.password_confirmation.value;

            if (password !== confirmPassword) {
                showAlert("Konfirmasi password tidak cocok.");
                return;
            }

            try {
                const userCredential = await auth.createUserWithEmailAndPassword(email, password);
                const user = userCredential.user;
                await user.updateProfile({ displayName: name });
                await user.sendEmailVerification();
                showAlert("Akun berhasil dibuat. Silakan cek email kamu untuk verifikasi.", "success");
                this.reset();
            } catch (error) {
                showAlert("Registrasi gagal: " + error.message);
            }
        });

        document.getElementById('googleLogin')?.addEventListener('click', function(e) {
            e.preventDefault();
            alertBox.innerHTML = '';
            auth.signInWithPopup(provider)
                .then(async (result) => {
                    const idToken = await result.user.getIdToken();
                    fetch("{{ route('user.google.callback') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ idToken: idToken })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = "{{ route('user.landingpage') }}";
                        } else {
                            showAlert("Login gagal: " + data.message);
                        }
                    });
                })
                .catch(error => {
                    showAlert("Login gagal: " + error.message);
                });
        });
    </script>
</body>
</html>
