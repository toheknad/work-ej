<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-manufacturer" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-manufacturer" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name">Tube Name</label>
            <div class="col-sm-10">
              <input type="text" name="tube_name" value="<?php echo $tube_name; ?>"  id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>

          <?php 
            
            $result = '';
             
            for ($i = 0; $i < count($arr_column); $i++) {
               
                $result .= '<div class="form-group ">';
                $result .= '<label class="col-sm-2 control-label" for="input-name">' .$arr_column[$i]. '</label>';
                $result .= '<div class="col-sm-10">';
                $result .=  '<input type="text" name="' .$arr_rows[$i]. '" value="'. $$arr_rows[$i] .'" id="input-name" class="form-control" />';
                  if ($error_name) { 
                  $result .= '<div class="text-danger">' .$error_name. '</div>';
                   } 
               $result .= '</div>
              </div>';
            } 

            echo $result;
           
          ?>
                         
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>