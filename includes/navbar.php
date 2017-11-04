<?php
echo <<<HTML
<!-- ~~~~~~~~~NAV BAR~~~~~~~~~~~ -->
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container-fluid">
        <!-- Title for Nav -->
        <div class="navbar-header">
          <a href="#" class="navbar-brand">What's My Shift</a>
        </div>
        <div>
          <ul class="nav navbar-nav">
            <!-- Main Nav item -->
            <li class=""><a href="index.php">Home</a></li>
            <!-- Other Menu items -->
            <li class="desktopNav"><a href="employer-info.php">Employer info</a></li>
            <li class="desktopNav"><a href="#">FAQ</a></li>
            <li class="desktopNav"><a href="terms-and-conditions.html">Terms</a></li>
            <li class="desktopNav"><a href="versions.php">Versions</a></li>
          <!-- Dropdown Menu -->
            <li class="dropdown mobileNav">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Links <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <!-- Dropdown Menu items -->
                <li class="mobileNav"><a href="employer-info.php">Employer info</a></li>
                <li class="mobileNav"><a href="#">FAQ</a></li>
                <li class="mobileNav"><a href="terms-and-conditions.html">Terms</a></li>
                <li class="mobileNav"><a href="versions.php">Versions</a></li>
                </ul> 
                <!-- End of Dropdown Menu items -->
            </li>
          </ul>
          <!-- End of Main Nav items -->
        </div>
      </div>
    </nav>
    <!-- ~~~~~~~~~END OF NAV BAR~~~~~~~~~~~ -->
HTML;

?>