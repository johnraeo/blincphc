<!DOCTYPE html>
<html>
  <head>
    <title>BLINC EMAIL</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet"> 
    <style type="text/css">
      body{
        margin: 0;
        padding: 0;
        background: white;
        font-family: "Nunito";
      }
      nav{
        padding: 2.5%;
        width: 100%;
        height: 50px;
        background-color: blue;
      }
      .txt-container{
        text-align: center;;
        padding-top: 5%;
      }
      .verify{
        text-align: center;
      }
      .verify .button-txt-container{
        margin: 0 auto;
        width: 60%;
        height: 50px;
        background-color: blue;
        display: table;
      }
      .verify a{
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
        <h1> Thanks for signing up for Blinc.ph </h1>
        <p> We're happy you're here. Let's get your email address verified </p>
    </div>
    <div class="verify">
      <div class="button-txt-container">
        <a href="{{url('user/verify', $user->secret_key)}}">Verify Email</a>
      </div>
    </div>
    <div class="txt-container">
      <small> Blinc.ph All rights reserved 2020 </small>
    </div>
  </body>
</html>