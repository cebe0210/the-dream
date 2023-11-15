<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convertisseur de Devises</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .converter-container {
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        select, input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
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
        <button type="button" id="swapCurrencies">Inverser</button>
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
                    $result = $amount * $conversionRate;
                    echo "<p>{$amount} {$fromCurrency} équivaut à {$result} {$toCurrency} (Taux de conversion : {$conversionRate})</p>";
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

<script>
    document.getElementById('swapCurrencies').addEventListener('click', function () {
        var fromCurrency = document.getElementById('fromCurrency').value;
        var toCurrency = document.getElementById('toCurrency').value;
        document.getElementById('fromCurrency').value = toCurrency;
        document.getElementById('toCurrency').value = fromCurrency;
    });
</script>

</body>
</html>
