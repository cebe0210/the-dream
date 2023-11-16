<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="icon.png">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <title>Convertisseur de Devises</title>
</head>
<body>

<div class="converter-container">
    <h1>Convertisseur de Devises</h1>
    <form action="" method="post">
        <div class="input-section">
            <label for="fromCurrency">Devises de départ:</label>
            <select name="fromCurrency" id="fromCurrency" required>
                <option value="EUR">Euro (EUR)</option>
                <option value="USD">Dollar Américain (USD)</option>
                <option value="CHF">Franc Suisse (CHF)</option>
                <option value="JPY">Yen Japonais (JPY)</option>
                <option value="GBP">Livre Sterling Britannique (GBP)</option>
                <option value="AUD">Dollar Australien (AUD)</option>
                <option value="CAD">Dollar Canadien (CAD)</option>
                <option value="CNY">Yuan Chinois (CNY)</option>
            </select>
        </div>
        <button type="button" id="swapCurrencies"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
  <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
  <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
</svg></button>
        <div class="input-section">
            <label for="toCurrency">Devises de destination:</label>
            <select name="toCurrency" id="toCurrency" required>
                <option value="USD">Dollar américain (USD)</option>
                <option value="EUR">Euro (EUR)</option>
                <option value="CHF">Franc Suisse (CHF)</option>
                <option value="JPY">Yen Japonais (JPY)</option>
                <option value="GBP">Livre Sterling Britannique (GBP)</option>
                <option value="AUD">Dollar Australien (AUD)</option>
                <option value="CAD">Dollar Canadien (CAD)</option>
                <option value="CNY">Yuan Chinois (CNY)</option>
            </select>
        </div>
        <div class="input-section">
            <label for="amount">Montant à convertir:</label>
            <input type="number" name="amount" id="amount" required>
        </div>
        <button type="submit">Convertir</button>
        
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fromCurrency = $_POST["fromCurrency"];
        $toCurrency = $_POST["toCurrency"];
        $amount = $_POST["amount"];

        $keyApi = "fca_live_D22BvMjeKImGKWWRYE7T8679g7TnrVk2Ju7jIKkY";
        $apiUrl = "https://api.freecurrencyapi.com/v1/latest";
        $url = "{$apiUrl}?apikey={$keyApi}&base_currency={$fromCurrency}&symbols={$toCurrency}";

        $certPath = __DIR__ . '/cacert.pem';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CAINFO => $certPath,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
            echo "Erreur cURL : " . $err;
        } else {
            $data = json_decode($response, true);

            if ($data && isset($data['data'])) {
                $rates = $data['data'];

                if (isset($rates[$toCurrency])) {
                    $conversionRate = $rates[$toCurrency];
                    $rounded_conversion = number_format($conversionRate, 4);
                    $result = $amount * $conversionRate;
                    $rounded_number = number_format($result, 2);
                    echo "<p>{$amount} {$fromCurrency} équivaut à {$rounded_number} {$toCurrency} (Taux de conversion : {$rounded_conversion})</p>";
                } else {
                    echo "Erreur : Taux de change non disponibles pour la devise de destination.";
                }
            } else {
                echo "Erreur lors de la conversion.";
            }

            curl_close($curl);
        }
    }
    ?>

</div>

<script src="script.js"></script>

</body>
</html>
