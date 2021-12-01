<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <style>

* {
  margin: 0;
  padding: 0;
}
body {
  min-width: 500px;
  margin: 2em;
}
p {
  margin: 1.5em 0 .5em;
  color: #222;
  font-family: 'Open Sans', sans-serif;
  font-size: 1.8rem;
  font-weight: 300;
  text-align: center;
}
p:first-child {
  margin-top: 0;
}
.parent {
  display: flex;
}
.parent01 {
  justify-content: space-between;
}
.parent02 {
  justify-content: space-around;
}
.parent03 {
  justify-content: space-evenly;
}
.child {
  width: 60px;
  height: 60px;
  background: #3498db;
}

    </style>
  </head>
  <body>

<p>space-between</p>
<div class="parent parent01">
  <div class="child"></div>
  <div class="child"></div>
  <div class="child"></div>
  <div class="child"></div>
  <div class="child"></div>
</div>
<p>space-around</p>
<div class="parent parent02">
  <div class="child"></div>
  <div class="child"></div>
  <div class="child"></div>
  <div class="child"></div>
  <div class="child"></div>
</div>
<p>space-evenly</p>
<div class="parent parent03">
  <div class="child"></div>
  <div class="child"></div>
  <div class="child"></div>
  <div class="child"></div>
  <div class="child"></div>
</div>

  </body>
</html>
