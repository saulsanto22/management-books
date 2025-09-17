<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Book Borrowed</title>
</head>
<body>
    <h1>Hello, {{ $userName }}</h1>
    <p>You have successfully borrowed the book:</p>
    <ul>
        <li><strong>Title:</strong> {{ $bookTitle }}</li>
        <li><strong>Author:</strong> {{ $bookAuthor }}</li>
        <li><strong>Borrowed At:</strong> {{ $borrowedAt }}</li>
    </ul>
    <p>Please return the book on time. Thank you!</p>
</body>
</html>
