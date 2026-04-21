<!DOCTYPE html>
<html>

<head>
    <title>SmartMobilePlan</title>
</head>

<body>

    <h2>Calcul Forfait Mobile</h2>

    <form method="POST" action="/calculate">
        @csrf

        <label>Data utilisée (Go):</label>
        <input type="number" name="dataUsed"><br><br>

        <label>Data hors UE (MB):</label>
        <input type="number" name="dataNonEU_MB"><br><br>

        <label>Ancienneté (années):</label>
        <input type="number" name="anciennete"><br><br>

        <label>Étudiant :</label>
        <input type="checkbox" name="isEtudiant" value="1"><br><br>

        <button type="submit">Calculer</button>
    </form>

    @if (isset($total))
        <h3>Total : {{ $total }} €</h3>
    @endif

</body>

</html>
