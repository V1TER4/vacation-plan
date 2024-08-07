<!-- resources/views/vacation_plans/export.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Exportação de Planos de Férias</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Planos de Férias</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Data</th>
                <th>Localização</th>
                <th>Participantes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vacationPlans as $vacationPlan)
            <tr>
                <td>{{ $vacationPlan->id }}</td>
                <td>{{ $vacationPlan->title }}</td>
                <td>{{ $vacationPlan->date }}</td>
                <td>{{ $vacationPlan->location }}</td>
                <td>
                    <ul>
                        @foreach($vacationPlan->participant as $participant)
                        <li>{{ $participant->name }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
