<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-manufacturer').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default" id="mainblock">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <?php echo $ajax_form_url = '/admin/index.php?route=catalog/tubs&token='.$token.'&';?>
        <div class="well">
          <form action="<?php echo $ajax_form_url; ?>" method="GET" id="FormAjax">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name">Tube Name</label>
                <input type="text" name="filter_tube_name" value="<?php echo $filter_tube_name; ?>" placeholder="" id="input-name" class="form-control" />
              </div>

             
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status">Models</label>
                <select name="filter_models" id="input-status" class="form-control">
                  <?php

                    $result = '';
                    foreach($arr_tables as $value => $name) {

                      if($value == $selected_table)  {
                      $result .= '<option value="' .$value. '" selected="selected" >' .$name. '</option>';
                      } else $result .= '<option value="' .$value. '" >' .$name. '</option>';
                    }

                    echo $result;
                  ?>
                 <!-- <option value="tubs_1" selected="selected">MX_101</option>
                  <option value="tubs_2">BRK_S0</option> -->
                </select>
              </div>
              <button type="submit" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> Filter</button>
              </form>
            </div>
          </div>
        </div>
      
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-manufacturer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'tube_name') { ?>
                    <a href="<?php echo $sort_tube_name; ?>" class="<?php echo strtolower($order); ?>">Tubs Name</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_tube_name; ?>">Tubs Name</a>
                    <?php } ?>
                  </td>
                    <?php  
                      $result = '';
                        for($i=0; $i < count($arr_column); $i++) {
                        $buffer = $arr_column[$i];
                         $result .= '<td class="text-right">
                                     <a href="<?php echo $sort_selector_switch_a; ?>">' . $arr_column[$i] . '</a>
                                    </td>';
                        }
                      echo $result;                                        
                    ?>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php  if ($tubs) { ?>
                <?php foreach ($tubs as $tub) { ?>
                <tr>         
                  <td class="text-center"><?php if (in_array($tub['tube_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $tub['tube_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $tub['tube_id']; ?>" />
                    <?php } ?></td>

                      <td class="text-left"><?php echo $tub['tube_name']; ?></td>

                       <?php 
                       $result = '';
                        for($i=0; $i < count($arr_rows); $i++) {
                         $result .= '<td class="text-right">' . $tub["$arr_rows[$i]"]. '</td>';
                        }
                      
                        echo $result;
             
                    ?>           
                  <td class="text-right"><a href="<?php echo $tub['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>

                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
   <script type="text/javascript">
$('#button-filter').on('click', function() { /*
  var url = 'index.php?route=catalog/tubs&token=<?php echo $token; ?>';

  var filter_tube_name = $('input[name=\'filter_tube_name\']').val();

  if (filter_tube_name) {
    url += '&filter_tube_name=' + encodeURIComponent(filter_tube_name);
  }

  var filter_models = $('select[name=\'filter_models\']').val();

  if (filter_models != '*') {
    url += '&table=' + encodeURIComponent(filter_models);
  }



  location = url; */
}); 
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter_name\']').val(item['label']);
  }
});

$('#FormAjax').submit(function(){ 
  var action = $(this).attr('action');
  var form_data = $(this).serialize();

   $.ajax({
    url: action,
    type: 'GET',
    data: form_data,        
    datatype: "html",         
    success: function (data) {

      var form_content = $(data).find("#mainblock").html();


      $("#mainblock").html(form_content);
      alert(data);
    }     
  });

   alert(action+form_data);

  return false;
  }); 
//--></script>
</div>
<?php echo $footer; ?>