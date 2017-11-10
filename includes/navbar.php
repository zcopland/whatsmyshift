<?php
echo <<<HTML
<!-- ~~~~~~~~~NAV BAR~~~~~~~~~~~ -->
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container-fluid">
        <!-- Title for Nav -->
        <div class="navbar-header">
          <a href="#" class="navbar-brand">What's My Shift</a>
        </div>
        <button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse navHeaderCollapse">
          <ul class="nav navbar-nav navbar-right">
            <!-- Main Nav item -->
            <li class=""><a href="index.php">Home</a></li>
            <!-- Other Menu items -->
            <li><a href="employer-info.php">Employer info</a></li>
            <li><a href="faq.php">FAQ</a></li>
            <li><a href="bug-reporter.php">Bug Reporter</a></li>
            <li><a href="terms-and-conditions.html">Terms</a></li>
            <li><a href="versions.php">Versions</a></li>
          </ul>
          <!-- End of Main Nav items -->
        </div>
      </div>
    </nav>
    <!-- ~~~~~~~~~END OF NAV BAR~~~~~~~~~~~ -->
HTML;

?>