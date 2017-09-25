       <div class="content-wrapper">
         <div class="container-fluid">
           <!-- Content Header (Page header) -->
           <section class="content-header">
             <h1>
               <?php
                   if(isset($page_title))
                       echo $page_title;
               ?>
               <?php
                   if(isset($page_subtitle)){
                       echo "
                           <small>
                               ".$page_subtitle."
                           </small>
                       ";
                   }
                   if(isset($top_btns)){
                      echo '<div class = "pull-right">';
                        foreach ($top_btns as $tb) {
                          echo '<'.$tb['tag'].' '.$tb['params'].'>'.$tb['text'].'</'.$tb['tag'].'> ';
                        }
                      echo '</div>';
                   }
               ?>
             </h1>
             
           </section>

           <!-- Main content -->
           <section class="content code-div <?php if(isset($no_padding) && $no_padding == true) echo 'no-padding'; ?>">
             <?php 
                 if(isset($code))
                     echo $code; 
             ?>
           </section><!-- /.content -->
         </div><!-- /.container -->
       </div><!-- /.content-wrapper -->
       <footer class="main-footer">
         <div class="pull-right hidden-xs">
           <b>Version</b> 1.0
         </div>
         <strong><a href="#">PointOne</a>.</strong>
       </footer>
       <?php
           if(isset($js))
               echo $js;
       ?> 
       <?php 
           if(isset($add_js)){
               if(is_array($add_js)){
                   foreach ($add_js as $path) {
                       echo '<script src="'.base_url().$path.'" type="text/javascript"  language="JavaScript"></script>';
                   }
               }
               else
                    echo '<script src="'.base_url().$add_js.'" type="text/javascript"  language="JavaScript"></script>';
           }
       ?> 