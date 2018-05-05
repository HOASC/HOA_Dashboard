<!DOCTYPE html>
<html>
    <head>
    <title>Edit Inspection Sub Category</title>
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src='https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js'></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src='https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js'></script>
<script type="text/javascript">
function showPleaseWait() {
    var modalLoading = '<div class="modal" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false role="dialog">\
        <div class="modal-dialog">\
            <div class="modal-content">\
                <div class="modal-header">\
                    <h4 class="modal-title">Please wait...</h4>\
                </div>\
                <div class="modal-body">\
                    <div class="progress">\
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"\
                      aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%; height: 100px">\
                      </div>\
                    </div>\
                </div>\
            </div>\
        </div>\
    </div>';
    $(document.body).append(modalLoading);
    $("#pleaseWaitDialog").modal("show");
}
function hidePleaseWait() {
    $("#pleaseWaitDialog").modal("hide");
}

function displayInspectionData(button){
    showPleaseWait();
    id  = button.id;
    var jsonObj = [];
    var item = {};
    item["id"] = button.id;
    jsonObj.push(item);
    var lol = JSON.stringify(jsonObj);
    var request  = new  XMLHttpRequest();
    request.open("POST","https://hoaboardtime.com/getInspectionSubCategoryDetails.php",true);
    request.send(lol.toString());
    request.onreadystatechange = function(){
    if ( request.readyState == XMLHttpRequest.DONE ){
      hidePleaseWait();
      var dal = request.responseText
      var data = JSON.parse(dal);
      var fieldData = '<label for="subcatid">ID</label>'+'<input type="text" id="subcatid" disabled="disabled" class = "form-control" value="'+data['id']+'"/>'+'<label for="name">Name</label>'+'<input type="text" id="name"  class = "form-control" value="'+data['name']+'"/>'+'<label for="inspectionCategory">Category</label>'+'<input type="text" id="inspectionCategory"  class = "form-control" value="'+data['inspection_category']+'"/>'+'<label for="subCategoryRule">Rule</label>'+'<input type="text" id="subCategoryRule"  class = "form-control" value="'+data['rule']+'"/>'+'<label for="subCategoryRuleDescription">Rule Description</label>'+'<textarea class="form-control" rows="5" id="subCategoryRuleDescription">'+data['rule_description']+'</textarea>'+'<label for="subCategoryExplanation">Explanation</label>'+'<textarea class="form-control" rows="3" id="subCategoryExplanation">'+data['explanation']+'</textarea>'+'<label for="subject">Subject</label>'+'<input type="text" id="subject" class = "form-control" value="'+data['subject']+'"/>'+'<label for="legal_docs_id">Legal Document ID</label>'+'<input type="text" id="legal_docs_id" class = "form-control" value="'+data['community_legal_id']+'"/>'+'<label for="section">Section</label>'+'<input type="text" id="section" class = "form-control" value="'+data['section']+'"/>'+'<label for="community_id">Community ID</label>'+'<input type="text" id="community_id" class = "form-control" value="'+data['community_id']+'"/>';
        $("#myModal").modal("show");
        $("#myModal").find('.modal-body').html(fieldData);
    }
    hidePleaseWait();
  }

 

}

function saveChanges2(){
    // window.alert("Some message");
}

function saveChanges(){
    // window.alert("Some message");

    var inspectionSubCategoryId = document.getElementById("subcatid").value;
    
    var inspectionSubCategoryName = document.getElementById("name").value;
    
    var inspectionCategoryID  = document.getElementById("inspectionCategory").value;
    
    var inspectionSubCategoryRule = document.getElementById("subCategoryRule").value;
    
    var inspectionSubCategoryRuleDescription = document.getElementById("subCategoryRuleDescription").value;
    
    var inspectionSubCategoryExplanation  = document.getElementById("subCategoryExplanation").value;
    
    var inspectionSubCategorySubject = document.getElementById("subject").value;
    
    var inspectionSubCategoryLegalDocumentId  = document.getElementById("legal_docs_id").value;
    
    var inspectionSubCategorySection  = document.getElementById("section").value;
    jsonObj = [];
    item = {};
    item['id'] = inspectionSubCategoryId;
    item['name'] = inspectionSubCategoryName;
    item['inspection_category'] = inspectionCategoryID;
    item['rule'] = inspectionSubCategoryRule;
    item['rule_description'] = inspectionSubCategoryRuleDescription;
    item['explanation'] = inspectionSubCategoryExplanation;
    item['subject'] = inspectionSubCategorySubject;
    item['community_legal_docs_id'] = inspectionSubCategoryLegalDocumentId;
    item['section']  = inspectionSubCategorySection;
    jsonObj.push(item);
    var lol = JSON.stringify(jsonObj);
    var request  = new  XMLHttpRequest();
    request.open("POST","https://hoaboardtime.com/updateInspectionSubCategoryDetails.php",true);
    request.send(lol.toString());
    request.onreadystatechange = function(){
    if ( request.readyState == XMLHttpRequest.DONE ){
      hidePleaseWait();
      alert(request.responseText);
    }
    }
    $("#myModal").modal("hide");
}
  </script>
  <style >     
      .btn:hover {
        background-position: 0px;
    }
     input[type="radio"], input[type="radio"]+label img {
    vertical-align: middle;
  }
  </style>
    </head>
    <body>
        <div class='splash'> 
            <div class='color-line'></div>
            <div class='splash-title'>
                <h1></h1>
                <div class='spinner'> 
                    <div class='rect1'></div>
                    <div class='rect2'></div>
                    <div class='rect3'></div> 
                    <div class='rect4'></div>
                    <div class='rect5'></div>
                </div>
            </div>
        </div>
        <h1>Edit Inspection Category</h1>
<hr>
<div class="container"> 
     <!-- <button type="button"  onclick="displayInspectionData();">Launch modal</button> -->
        <table id="example" class="table table-striped" cellspacing="0" width="100%" style="font-size: 12px;" >
                                <thead>
                                    <tr>
                                       <center>
                                        <th>ID</th>
                                       <th>Name</th>
                                        <th>Category</th>
                                        <th>Rule</th>
                                        <th>RuleDescription</th>
                                        <th> Explanation</th>
                                        <th>Subject</th>
                                        <th>LegalDocsID</th>
                                        <th>Section</th>
                                        <th>CommunityID</th>
                                        <th></th>
                                       </center>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'includes/dbconn.php';
                                    if ( true ){
                                        $query = "SELECT * FROM INSPECTION_SUB_CATEGORY ORDER BY ID";
                                        $queryResult = pg_query($query);
                                        while ($row = pg_fetch_assoc($queryResult)) {
                                            echo '<tr>';
                                            echo '<td style="width=3%">';
                                            echo $row['id'];
                                            echo '</td>';
                                            echo '<td>';
                                                echo $row['name'];
                                            echo '</td>';
                                            echo '<td>';
                                                echo $row['inspection_category_id'];
                                            echo '</td>';
                                            echo '<td>';
                                                echo $row['rule'];
                                            echo '</td>';
                                            echo '<td width="30%">';
                                               echo substr($row['rule_description'],0,100);
                                            echo '</td>';
                                            echo '<td width="30%">';
                                                echo substr($row['explanation'],0,100);
                                            echo '</td>';
                                            echo '<td width="30%">';
                                                echo substr($row['subject'],0,50);
                                            echo '</td>';
                                            echo '<td>';
                                                echo $row['community_legal_docs_id'];
                                            echo '</td>';
                                            echo '<td>';
                                                echo $row['section'];
                                            echo '</td>';
                                            echo '<td>';
                                                echo $row['community_id'];
                                            echo '</td>';
                                            echo '<td>';
                                            echo '<input type="button" value="Edit" id="'.$row['id'].'" onclick="displayInspectionData(this);">';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    }

                                    ?>
        </tbody>
        </table>
        <br><br>
        <script type="text/javascript">
        $(document).ready(function() {
              $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'My button',
                action: function ( e, dt, node, config ) {
                    alert( 'Button activated' );
                }
            }
            ]
            } ); 
            var table = $('#example').DataTable({
            dom: 'l<"toolbar">frtip',
            initComplete: function(){
            $('.datatable').dataTable({
                "sPaginationType": "bs_four_button"
            }); 
            $('.datatable').each(function(){
                var datatable = $(this);
                var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                search_input.attr('placeholder', 'Search');
                search_input.addClass('form-control input-sm');
                var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                length_sel.addClass('form-control input-sm');
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#example').DataTable();
            } );
        </script> 
<script type="text/javascript">
    function changeState(el) {
        if (el.readOnly) el.checked=el.readOnly=false;
        else if (!el.checked) el.readOnly=el.indeterminate=true;
    }
</script>
  </div>

  <div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Edit Inspection</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="reset" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveChanges();">Save changes</button>
      </div>
    </div>
  </div>
</div>
    </body>
</html>
