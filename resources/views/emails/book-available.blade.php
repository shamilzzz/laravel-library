<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Book Available</title>
</head>

<body>
    <h2>Hello {{ $bookQueue->user->name }},</h2>

    <p>Your requested book is now available.</p>

    <p>
        <strong>Book:</strong>
        {{ $bookQueue->book->title }}
    </p>

    <p>Please visit the library to borrow it.</p>

    <br>

    <p>Thank you,</p>
    <p><strong>Library Management System</strong></p>
</body>

</html>