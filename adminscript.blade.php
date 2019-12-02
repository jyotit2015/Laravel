<script>
	/*
	* Used for get respective Controler and action name from route url
	*/
    var controller				=	'<% $controller %>';
	var action					=	'<% $action %>';
	var csrfTkn					=	'<% csrf_token() %>';
	var baseUrl					=	'<% URL::to('/'); %>';
	var onChangeFunction		=	'';
	var listingUrl				=	'';

	@if(isset($id) && $id != '')		
		@if($action == "getAddattribute") 
			getSubCategoyList(<% $layoutArr['viewDataObj']->category_ids %>,"form-control","FieldMaster","subCateResDiv");				
		@endif			
	@endif
	//alert(action);
	function showJsonErrors(errors){
		if(errors != ''){
			resp = $.parseJSON(errors);
			var totErrorLen = resp.length;	
			for(var errCnt =0;errCnt <totErrorLen;errCnt++){
				var modelField         =   resp[errCnt]['modelField'];
				var modelErrorMsg      =   resp[errCnt]['modelErrorMsg'];
				$('[id="'+modelField+'"]').after('<div class="error-message">'+modelErrorMsg+'</div>'); 
			}
		}
	}
    // Action used for reset all dynamic select box 
	var student_id                          =	0;
	var student_name                        =	'';
	var checkFlagbedlist					=	0;
	
    function resetAlotDropDown(type,response_dv){ 	
		var response_div														=	"_"+response_dv;
		if(response_dv == ""){
			response_div														=	response_dv;
		}
		if(type == 'state'){
			$('#district_id'+response_div).empty().append($("<option></option>").attr("value","").text("Select"));
			$('#city_id'+response_div).empty().append($("<option></option>").attr("value","").text("Select"));
			$('#postal_area_id'+response_div).empty().append($("<option></option>").attr("value","").text("Select"));			
			$('#postal_pin_id'+response_div).empty().append($("<option></option>").attr("value","").text("Select"));
			//$('#bedResponseDv').html('');
		}else if(type == 'city'){				
			$('#location_id'+response_div).empty().append($("<option></option>").attr("value","").text("Select"));
			$('#hostel_id'+response_div).empty().append($("<option></option>").attr("value","").text("Select"));			
			$('#room_id'+response_div).empty().append($("<option></option>").attr("value","").text("Select"));
			$('#bedResponseDv').html('');
		}
	}
	
	/*
	* Group Delete confirmation
	*/
   function checkConfirmation(){
	   if(confirm("Are you sure to Delete ?")){
		   return true;
	   }else{
		   return false;
	   }
   }
   
   /*
	* Block UI
	*/
	$(document).submit(function() {
		/*var isBlock = true;
		if(document.getElementById('pageUI_BlockStatus')){	
			var pageBlockStatus = $('#pageUI_BlockStatus').val();
			if(pageBlockStatus == 1){
				isBlock	= false;    
			}
		}
		if(isBlock){
			showBlockUI();
		}else{
			setTimeout('allowBlock',5);
		}*/showBlockUI();		
	});
	function showBlockUI(){
		$.blockUI({
				message: '<div class="blockUiDv">Please wait...</div>',
				css: { 
					border: 'none', 
					padding:'10px 0',
					backgroundColor: '#FFFFFF',				
					'border-radius': '10px',
					'font-size': '20px',
					opacity: 1, 
					color: '#000000'
				}
			});
	}
	function allowBlock(){
		$('#pageUI_BlockStatus').val('0');    
	}
	function showBlockDv(){
		$('.blockUiDv').block({ 
			message: null,
			css: { 
				border: '1 px', 
				'border-color':'#e7ebee',				
				'border-radius': '3px',
				opacity: 1,
			} 
		});
	}
	function unBlockDv(){
		$('.blockUiDv').unblock(); 
	}
	/*
	* Used for reset all form
	*/
	function resetFormVal(frmId,radVal,hidVal){		
		if(radVal == 1){
			$('#'+frmId).find('input:checkbox').removeAttr('checked').removeAttr('selected');
			$('.'+frmId).find('input:checkbox').removeAttr('checked').removeAttr('selected');
		}else{
			$('#'+frmId).find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
			$('.'+frmId).find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');			
		}
		if(hidVal == 1){
			$('#'+frmId).find('input:hidden').val('');
		}
		$('#'+frmId).find('input:password,input:text, input:file, select, textarea').val('');	
		$('.'+frmId).find('input:password,input:text, input:file, select, textarea').val('');
		$('.error-message').remove();
		//resetting file upload content
		@if($action == 'getStudent')
			$('.file-preview').hide();
			$('.emptyDv').hide();
			$('#photo').fileinput('reset');
			$('#photo_name').val('');
			$('#photo_size').val('');
			$('#photo_selected_cnt').val('');
		@elseif($action == 'getArchievelist')
			$('#archieve_upload').fileinput('reset');
			$('#photo_selected_cnt').val(0);
		@elseif($action == 'getComposemail')
			$('#file_upload').fileinput('reset');
			$('#photo_selected_cnt').val(0);
			$('#subject').val('');
			$('#messagearea').val('');
		@elseif($action == 'getUploadclassfile' || $action == 'getGallerylist')
			$('#file_upload').fileinput('reset');
			$('#file_upload').fileinput('unlock');
			@if($action == 'getUploadclassfile')
				$('#classFileDtlDv').html('');	
			@endif
			@if($action == 'getGallerylist')
				$('#galleryFileDtlDv').html('');
			@endif
			$('.editClassBtn').hide();
			photo_name			=	'';
			photo_size			=	'';
			photo_download_name	=	'';
		@endif
		//$('.formError').remove();
	}
	/*
	* Used for make print screen
	*/
    function printDiv(contElement){ 
        var data = $('.'+contElement).html();
        var mywindow = window.open('', 'my div', 'height=400,width=800');
        mywindow.document.write('<html><head><title>my div</title>');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
 
        mywindow.print();
        mywindow.close();
        return true;
    }
    /*
	* Used for make datepicker intialisation process
	*/
	function ajaxCompleteFunc(){				
		$(".datepkr").datepicker({
					format: 'dd-mm-yyyy',
					autoclose:true,
					endDate:new Date()
		});
		$(".datepkrNoRestrict").datepicker({
					format: 'dd-mm-yyyy',
					autoclose:true
		});
		$(".datepkrCallBck").datepicker({
					format: 'dd-mm-yyyy',
					autoclose:true,
					//endDate:new Date()
		});
		$('.datepkrCallBck').datepicker().on('hide', function(e){
			calculateFirstMonthHostelFee();
		});
		$(".dateMask").inputmask(
						"dd-mm-yyyy", 
						{
							"placeholder": "dd-mm-yyyy"
						}
		);
		$(".phoneNoMask").inputmask("9999999999");
		$(".pinMask").inputmask("999999");
        setColorBoxDisplay();
    }
    /*
	* Used for make all field number validation
	*/
	function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)){
           return false;
        }
        return true;
    }
    /*
	* Used for make all field number validation
	*/
    function isNumberKeyWithDot(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
	    if (charCode == 46)
	        return true
        else
        {
			if (charCode > 31 && (charCode < 48 || charCode > 57)){
			   return false;
			}
			else
				return true;
		}
    }
    /*
	* Used for Lost password fnctionality with ajax
	*/
    @if($action == 'getLostpassword')
    	function validateLostpswdMail(){
    		$('.error-message').remove();
            $('#loddingImage').show();
            $('.frmbtngroup').prop('disabled',true);
            $('.form-control').prop('readonly',true);
			$.ajaxSetup({
				headers: {
					'X-CSRF-Token': csrfTkn
				}
			});
			$.ajax({
				url:baseUrl+'/user/validatelostpswdmail',
				type: 'post',
				cache: false,					
				data:{
					"formdata": $('#form-lostpswd').serialize(),
				},
				success: function(res){					
					var resp		=   res.split('****');
					$('#loddingImage').hide();
					$('.form-control').prop('readonly',false);
					$('.frmbtngroup').prop('disabled',false);
					if(resp[1] == 'ERROR'){											
						$('#failMsgDiv').removeClass('text-none');
						$('.failmsgdiv').html(resp[2]);
						$('#failMsgDiv').show('slow');
						setTimeout(function(){ $('#failMsgDiv').fadeOut('slow'); }, 5000);
					}else if(resp[1] == 'FAILURE'){
						showJsonErrors(resp[2]);
					}else if(resp[1] == 'SUCCESS'){
						resetFormVal('form-lostpswd',0);
						$('#sucMsgDiv').removeClass('text-none');
						$('.sucmsgdiv').html(resp[2]);
						$('#sucMsgDiv').show('slow');	
                        setTimeout(function(){ $('#sucMsgDiv').fadeOut('slow'); }, 5000);
						//window.location.replace(baseUrl+"/usersetting/userlist"); 
					}		
				},
				error: function(xhr, textStatus, thrownError) {
					$('#loddingImage').hide();
					$('.form-control').prop('readonly',false);
					$('.frmbtngroup').prop('disabled',false);
					$('.failmsgdiv').html('Something went to wrong.Please Try again later...');
					$('#failMsgDiv').show('slow');
					setTimeout(function(){ $('#failMsgDiv').fadeOut('slow'); }, 5000);
				}
			});
    	}
    	
    @endif
    /*
	* Used for Reset password fnctionality with ajax
	*/
    @if($action == 'getResetpassword')
		function resetPassword(){
			$('.frmbtngroup').prop('disabled',true);			
			$('.registerBtn').prop('disabled',true);
			$('.regBtnTxt').html('Please wait...');
			$('.imgLoader').show();
			$.ajaxSetup({
				headers: {
					'X-CSRF-Token': csrfTkn
				}
			});
			$.ajax({
				url:baseUrl+'/user/resetpassword',
				type: 'post',
				cache: false,					
				data:{
					"formdata": $('#form-resetpswd').serialize(),
				},
				success: function(res){
					$('.error-message').remove();
					$('.registerBtn').prop('disabled',false);						
					$('.imgLoader').hide();
					$('.regBtnTxt').html('SUBMIT');
					var resp		=   res.split('****');
					if(resp[1] == 'SUCCESS'){
						resetFormVal('form-resetpswd',0);
						$('.sucmsgdiv').html(resp[2]);
						$('#sucMsgDiv').show('slow');
						setTimeout(function(){ $('#sucMsgDiv').fadeOut('slow'); }, 2000);
                        setTimeout(function(){ window.location.replace(baseUrl+"/user/login"); }, 2000); 			
					}else if(resp[1] == 'FAILURE'){
						showJsonErrors(resp[2]);																		
					}else if(resp[1] == 'ERROR'){
						alert(resp[2]);
					}		
				},
				error: function(xhr, textStatus, thrownError) {
					alert('Something went to wrong.Please Try again later...');
				}
			});
		}
	@endif
	/*
	* Used for Email valdation after forgot password fnctionality with ajax
	*/
	@if($action == 'getUsermailvalidation')
            function verifyLogin(){
		$('.error-message').remove();
                $('#loddingImage').show();
                $.ajaxSetup({
                    headers: {
                            'X-CSRF-Token': csrfTkn
                    }
                });
                $.ajax({
                    url:baseUrl+'/user/verifylogin',
                    type: 'post',
                    cache: false,					
                    data:{
                            "formdata": $('#login-verify').serialize(),
                    },
                    success: function(res){					
                        var resp		=   res.split('****');
                        $('#loddingImage').hide();
                        $('.frmbtngroup').prop('disabled',false);
                        if(resp[1] == 'ERROR'){											
                            $('#failMsgDiv').removeClass('text-none');
                            $('.failmsgdiv').html(resp[2]);
                            $('#failMsgDiv').show('slow');
                        }else if(resp[1] == 'FAILURE'){
                            showJsonErrors(resp[2]);
                        }else if(resp[1] == 'SUCCESS'){
                            resetFormVal('login-verify',0);
                            $('#sucMsgDiv').removeClass('text-none');
                            $('.sucmsgdiv').html(resp[2]);
                            $('#sucMsgDiv').show('slow');	
                            setTimeout(function(){ $('#sucMsgDiv').fadeOut('slow'); }, 5000);
                            window.location.replace(baseUrl); 
                        }		
                    },
                    error: function(xhr, textStatus, thrownError) {
                            //alert('Something went to wrong.Please Try again later...');
                    }
                });
            }
	@endif
</script>