<?php
session_start();

// Check if an error message exists in the session
if (isset($_SESSION["error"])) {
    $error_message = $_SESSION["error"]; // Get the error message
    unset($_SESSION["error"]); // Clear the error message from the session
}

// Check if the error message matches the specific error
if (isset($error_message) && $error_message === "Too many unsuccessful login attempts. Please wait for 60 seconds.") {
    $disableLoginButton = true;
} else {
    $disableLoginButton = false;
}

// If the login button is disabled, set the remaining time for enabling it (in seconds)
$remainingTime = 60; // Set the remaining time here (in seconds)

// If the login button is disabled and there is no remaining time set in cookies, set the remaining time in cookies
if ($disableLoginButton && !isset($_COOKIE["remainingTime"])) {
    $expiry = time() + $remainingTime;
    setcookie("remainingTime", $expiry, $expiry);
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Narkoz</title>
    <!-- Required meta tags -->
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet">

    <style>
        .error-box {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-top: -40px;
            margin-bottom: 15px;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.5s linear;
        }

        .login-box.show-error .error-box {
            visibility: visible;
            opacity: 1;
        }

        .btn.disabled-button {
            /* Devre dışı bırakıldığında opaklık için stiller */
            opacity: 0.3;
            pointer-events: none;
        }
    </style>
</head>

<body>
<div class="loader"></div>
    <div class="login-box <?php echo isset($error_message) ? 'show-error' : ''; ?>">
        <div class="error-box">
            <?php echo isset($error_message) ? $error_message : ''; ?>
        </div>
        <h2>Login</h2>
        <form action="security/login.php" method="POST">
            <div class="user-box">
                <input type="text" name="username" required="">
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="password" name="password" required="">
                <label>Password</label>
            </div>
            <button class="btn <?php echo $disableLoginButton ? 'disabled-button' : ''; ?>" type="submit" <?php echo $disableLoginButton ? 'disabled' : ''; ?>>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Login
            </button>
            <tg> <a href="https://t.me/Narkoz042">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    Telegram
                </a> </tg>

            <dc> <a href="#">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    Discord
                </a> </dc>

        </form>
    </div>
    <script>
const loginButton = document.querySelector('.btn');
let disableLoginButton = <?php echo $disableLoginButton ? 'true' : 'false'; ?>;
let disableTimeout; // Zamanlayıcıyı saklamak için değişken
let NarkozTime = <?php echo $NarkozTime ? 'true' : 'false'; ?>;

// Sayfa yüklendiğinde veya yenilendiğinde butonun durumunu güncelle
function updateLoginButtonStatus() {
    // localStorage'dan disableLoginButton ve disableUntil değerlerini alın
    disableLoginButton = localStorage.getItem('disableLoginButton') === 'true';
    NarkozTime = localStorage.getItem('NarkozTime') === 'true';
    const disableUntil = parseInt(localStorage.getItem('disableUntil'), 10);

    if (NarkozTime) {
    let adsTimeInSecond = 180;
let isFocused = true;

window.addEventListener('blur', () => isFocused = false);
window.addEventListener('focus', () => isFocused = true);

const timerId = setInterval(() => {
  if (adsTimeInSecond <= 0) {
    clearInterval(timerId);
    document.title = 'Narkoz';
  }

  if (isFocused) {
    adsTimeInSecond--;
  }

  document.title = `To Try Again ${adsTimeInSecond}s`;
}, 1000);
    }

    if (disableLoginButton) {
        loginButton.disabled = true;
        loginButton.classList.add('disabled-button'); // Opaklığı azaltan stil sınıfını ekleyin

        // Eğer disableUntil değeri varsa, kalan süreyi hesaplayın
        let timeoutDuration = 180000;
        if (disableUntil) {
            const currentTime = new Date().getTime();
            timeoutDuration = Math.max(0, disableUntil - currentTime);
        }

        // timeoutDuration süresi sonra butonu tekrar etkinleştirin
        disableTimeout = setTimeout(function () {
            disableLoginButton = false; // Yasağı kaldırın
            localStorage.setItem('disableLoginButton', 'false'); // localStorage'da disableLoginButton değerini güncelleyin
            localStorage.setItem('NarkozTime', 'false'); // localStorage'da disableLoginButton değerini güncelleyin
            localStorage.removeItem('disableUntil'); // localStorage'da disableUntil değerini kaldırın
            loginButton.disabled = false;
            loginButton.classList.remove('disabled-button'); // Stil sınıfını kaldırın
        }, timeoutDuration);
    } else {
        // Eğer yasağı kaldırmışsak, zamanlayıcıyı temizleyin
        clearTimeout(disableTimeout);
    }
}

// Sayfa yüklendiğinde veya yenilendiğinde butonun durumunu kontrol et
updateLoginButtonStatus();

// Sayfa yenilendiğinde butonun durumunu güncellemek için "load" olayını dinleyin
window.addEventListener('load', updateLoginButtonStatus);

// Eğer belirli bir hata oluşursa, yasağı etkinleştirin ve localStorage'da gerekli değerleri ayarlayın
if (<?php echo isset($error_message) && $error_message === "Too many unsuccessful login attempts. Please wait for 60 seconds." ? 'true' : 'false'; ?>) {
    disableLoginButton = true;
    NarkozTime = true;
    localStorage.setItem('disableLoginButton', 'true');
    localStorage.setItem('NarkozTime', 'true');
    const currentTime = new Date().getTime();
    const disableUntil = currentTime + 180000;
    localStorage.setItem('disableUntil', disableUntil.toString());
    updateLoginButtonStatus();
}
    </script>
</body>

  </html>
  
