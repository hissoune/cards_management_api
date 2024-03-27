<html>
<head>
    <title>Business Card</title>
</head>
<body>
    <h1>Create Business Card</h1>
    <form id="businessCardForm"  action="{{ route('business_cards') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Name" required><br>
        <input type="text" name="company" placeholder="Company" required><br>
        <input type="text" name="title" placeholder="Title" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="tel" name="phone" placeholder="Phone" required><br>
        <button type="submit">Submit</button>
    </form>

  

</body>
</html>
