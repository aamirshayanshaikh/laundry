<div class="wrapper">
  <header class="main-header">               
    <nav class="navbar navbar-static-top">
      <div class="container-fluid">
      <!-- <div class="navbar-header">
        <a href="<?php base_url(); ?>" class="navbar-brand"><b>Admin</b>RTJ</a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <i class="fa fa-bars"></i>
        </button>
      </div> -->

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <?php
              if(isset($sideNav))
                echo $sideNav;
          ?>
          <!-- <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
          <li><a href="#">Link</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
              <li class="divider"></li>
              <li><a href="#">Separated link</a></li>
              <li class="divider"></li>
              <li><a href="#">One more separated link</a></li>
            </ul>
          </li> -->
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <?php
              if(isset($rSideNav))
                echo $rSideNav;
          ?>
          <!-- echo base_url().'users/profile'; -->
          <!-- <li class="dropdown user user-menu">
            <a href="">
              <span class="hidden-xs"><i class="fa fa-user fa-lg"></i> Profile</span>
            </a>
          </li> -->
          <li>
            <a href="<?php echo base_url().'site/logout'; ?>">
              <span><i class="fa fa-sign-out fa-lg"></i></span>
            </a>
          </li>
        </ul>
      </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
  </header>
  <!-- Full Width Column -->