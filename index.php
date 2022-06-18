<!---
We used bootstrap.
To help make the font end look better
https://bootsnipp.com/snippets/dldxB by Rj78

datatables to add a clean design to our tables, which is a javascript library and uses jQuery
//cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js



-->

<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">



    <link rel="stylesheet" href="admin.css">

    <title>Garits</title>
</head>
<body>

<div class="wrapper fadeInDown">
    <div id="formContent">
        <!-- Tabs Titles -->

        <!-- Icon -->
        <div class="fadeIn first">
            <img src="person.png" id="icon" alt="User Icon" />
        </div>
        <h3 class="text-secondary">Garits System</h3>
        <!-- Login Form -->
        <form class="form-group" action="login.php" method="POST">
            <input type="text"  class="p-2 form-control" name="un" placeholder="Enter Your Username">
            <input type="password" class="p-2 form-control" name="pw" placeholder="Enter Your Password">

            <input type="submit" class="fadeIn fourth" value="Login">
        </form>

    </div>
</div>

</body>
</html>