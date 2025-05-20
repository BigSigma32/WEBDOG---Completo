<html>
    <head>
        <title>WEBDOG HOME</title>
        <link rel="stylesheet" href="estetica.css">
    </head>
<body class="sfondo">
    <div class="navbar">
        <div class="logo-container">
            <img src="img/Home/WEBDOG (1).png" alt="Logo">
            <h1 class="title">WEBDOG</h1>
        </div>
    
        <div class="nav-links">
            <a href="Home.php
            ">Home</a>
            <a href="Galleria.html">Galleria</a>
            <a href="Chisiamo.html">Chi siamo?</a>
            <a href="Eventi.html">Eventi</a>
            <a href="Donazioni.html">Donazioni</a>
        </div>
    
        <div class="login">
            <a href=""><img src="img/Login/user.jpg" alt="Foto profilo"></a>
        </div>
    </div>   
    <br>
    <br>
    <br>
        <div class="login">
            
        
        </div>
    </div>
    
    <center>

    <div class="wrapper">
        <form action="DB/Sign in.php" method="post">
            <h1>Registrazione</h1>
            <div class="input-box via-civico">
                <input type="text" class="via" name="nome" placeholder="Nome" required>
                <input type="text" class="civico" name="cognome" placeholder="Cognome" required>
                <i class='bx bxs-user'></i>
            </div>
        <div class="input-box">
            <input type="email" name="email" placeholder="Email" required>
            <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
                <input type="password" name="confermaPassword"  placeholder="confermaPassword" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <div class="input-box">
                <input type="text" name="numTelefono" placeholder="Numero di telefono">
                <i class='bx bxs-user'></i>
                </div>
                <div class="input-box via-civico">
                    <input type="text" class="via" name="citta" placeholder="Citt&agrave" required>
                    <input type="text" class="civico" name="CAP" placeholder="Cap" required>
                    <i class='bx bxs-user'></i>
                </div>
                    <div class="input-box via-civico">
                        <input type="text" class="via" name="via" placeholder="Via" required>
                        <input type="text" class="civico" name="nro" placeholder="Nro" required>
                        <input type="text" class="civico" name="provincia" placeholder="Provincia" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    
                      
            <button type="submit" class="btn">Registrati</button>
            <div class="register-link">
                <p>Hai già un account? <a href="Login1.php">login</a></p>
            </div>
        </form>
    </center>
    </div>
</body>
</html>
