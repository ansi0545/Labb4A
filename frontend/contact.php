<!DOCTYPE html>
<html lang="sv">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Sono:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Kontakta oss - Vovvebloggen</title>
 
</head>

<body>
    <header>
        
    </header>
    <div class="container">
        <h1>Kontakta oss</h1>
        <p>Har du frågor, förslag eller bara vill säga hej? Tveka inte att kontakta oss! Fyll i formuläret nedan och vi återkommer till dig så snart som möjligt.</p>
        <form action="send_message.php" method="post">
            <label for="name">Namn:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">E-postadress:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Meddelande:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit">Skicka meddelande</button>
        </form>
    </div>
   
</body>

</html>
