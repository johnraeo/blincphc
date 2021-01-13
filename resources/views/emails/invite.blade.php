<!DOCTYPE html>
<html>
<head>
    <title>BLINC EMAIL</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet"> 
    <style type="text/css">
      body {
        margin: 0;
        padding: 0;
        background: white;
        font-family: "Nunito";
      }

      nav {
        padding: 2.5%;
        width: 100%;
        height: 50px;
        background-color: blue;
      }

      .txt-container {
        text-align: center;;
        padding-top: 5%;
      }

      .verify {
        text-align: center;
      }

      .verify .button-txt-container {
        margin: 0 auto;
        width: 60%;
        height: 50px;
        background-color: blue;
        display: table;
      }

      .verify a {
        display: table-cell;
        vertical-align: middle;
        text-decoration: none;
        color: white;
        font-weight: bold;
      }
    </style>
</head>
<body>
    <nav>
        <img src="{{ asset('svg/logo_inverted.svg')}}">
    </nav>
    <div class ="txt-container">
        <h1> You are invited by {{$user->email}} </h1>
        <p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Adipisci maiores minima unde, vel beatae voluptatibus asperiores, exercitationem iure quia tenetur quidem modi? Fugit dolore praesentium dicta magnam iste accusamus deserunt? </p>
    </div>
    <div class="verify">
        <div class="button-txt-container">
            <a href="{{url('/login')}}">Sign Up</a>
        </div>
    </div>
    <div class="txt-container">
        <small> Blinc.ph All rights reserved 2020 </small>
    </div>
</body>
</html>