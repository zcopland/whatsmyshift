<?php

sanitize("<h1>Hello world!</h1>");

function sanitize($dirty) {
    echo htmlentities($dirty, ENT_QUOTES, 'UTF-8');
}

?>
<!DOCTYPE html>
  <head>
    <title>Test Page</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
  </head>
  <body>
    <h1></h1>
  </body>
</html>