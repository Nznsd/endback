$(function(){
    
    // to create Microsoft Account
    $('#submitFormBtn').click(function(e){

        e.preventDefault();

        $recoveryEmailInput = $("input[name='recoveryEmail']");
        $phoneNo = $("input[name='phone']");

        if($recoveryEmailInput.val().length == 0)
        {
            $recoveryEmailInput.css({border:"1px solid red"}); return;
        }
        else
        {
            $recoveryEmailInput.removeAttr("style");
        }

        if($phoneNo.val().length == 0)
        {
            $phoneNo.css({border:"1px solid red"}); return;
        }
        else
        {
            $phoneNo.removeAttr("style");
        }


        swal({
            title:"Info",
            text:"Creating an account might take a while. PLEASE do not interrupt the browser during this process."
        });
       // alert("Creating an account might take a while. PLEASE do not interrupt the browser during this process.");


        $(this.form).submit();
    
    });
    
    validateStudent();

    //confirm Registration Number
    $("select[name='states']").change(function(){

        var programmeId = $("select[name='programme']").val();        
        var stateId = $(this).val();

        $studyCenters = $("select[name='studyCenter']")
        $studyCenters.html("<option>Loading study centers...</option>");

        $.get('/students/getstudycenters/'+programmeId+'/'+stateId, function(data, status){
            if(status == 'success')
                $studyCenters.html(data);
        });

    });

    $("#searchNow").click(function(){

        $searchNow = $(this);
        
        $searchNow.html("Searching...");

        var surname = $("input[name='surname']").val();
        var programme = $("select[name='programme']").val();
        var entryYear = $("select[name='entryYear']").val();
        var state = $("select[name='states']").val();
        var studyCenter = $("select[name='studyCenter']").val();

        if(surname.length < 3)
        {
            $searchNow.html("Search");
            
            swal({
                title:"Info",
                text: "Surname field cannot be empty or less than 3 characters"
            });

            return;
        }
        
        $.get('/students/confirm/'+surname+'/'+programme+'/'+entryYear+'/'+state+'/'+studyCenter, function(data, status){

            if(status == 'success')
            {

                console.log(data);

                if(data.length == 0)
                {
                    $searchNow.html("Search");

                    swal({
                        title:"Info",
                        text: "Record not found. Please contact support@nti.edu.ng"
                    });
    
                }
                else{

                    $searchNow.html("Search");
                    
                    var data = JSON.parse(data);

                    swal({
                        title:"Success",
                        text: data.msg,
                        confirmButtonText:"Go To Create Account"
                    }, function(){
                        location.href= location.origin + "/students/create?regNo="+data.regNo
                    });
    
                }
        
            }

        });
       
        return false;

    })

    // Work on this when you want to activate installment payment
    $('.payNow').click(function(e){
        e.preventDefault();
    
        $this = $(this);
        //var installment = $this.parents('td').siblings('td').children('select').val();
        //$this.siblings("input[name='installment']").val(installment);
        $this.parent('form').submit();   
        
    });
    
    //Payment History Filter (Academic Semester dropdown)
    $("select[name='paymentHistoryFilter']").change(function(){

        var semesterId = $(this).val();
        
        var info = $('#info')
        info.html("<small class='text-info'><img src='/assets/img/icons/gif/loader-grey.gif' alt='...' /></small>");

        $.get('/students/fees/history/'+semesterId, function(data, status){

            if(status == 'success'){
                $('#historyTable').html(data);
                info.html('');
            }
        });
        
    });

    //Carryover Level Filter
    $("select[name='carryoverFilter']").change(function(){
                                
                $selected = $("select[name='carryoverFilter'] option:selected");

                var level = $selected.attr('data-level');
                var semester = $selected.attr('data-semester');
                var klass = "."+level+"-"+semester;                 

                $('.trow').hide()
                $(klass).fadeIn('slow');
                
    });

    //Submit carryover form
    $("#coSubmit").click(function(){
        
        if($("input[type='checkbox']:checked").length == 0)
        {
            alert("Select at least one course");    
            return;
        }
        $("#courseForm").submit();

        return false;
    });
                
});

function validateStudent()
{

    $('#validate').click(function(e){
        
        e.preventDefault();

        var info = $('#info')
        var regNo = $("input[name='regNo']").val();

        if(regNo.length == 0){
            info.html("<small class='text-danger'>Please enter your registartion number.</small>")
            return;
        }    
        info.html("<small class='text-info'> <img src='/assets/img/icons/gif/loader-grey.gif' alt='' /> Validating data. Please be patient...</small>");
        
        $.get("/students/validate?format=true&regNo="+regNo, function(data, status){

            if(status == 'success')
            {
                if(Object.keys(data).length != 0)
                {
                    $("input[name='fullname']").val(data.fullname);
                    $("input[name='surname']").val(data.surname);
                    $("input[name='firstname']").val(data.firstname);
                    $("input[name='regNo']").val(data.regNo);
                    $("input[name='programme']").val(data.programme);
                    $("input[name='specialization']").val(data.specialization);
                    $("input[name='entryYear']").val(data.entryYear);
                    $("input[name='sor']").val(data.sor);
                    $("input[name='studyCenter']").val(data.studyCenter);
                    
                    $("#validatedForm").show();
                    info.html("");

                    //checkbox toggle
                    $("input[name='confirm']").click(function(){
                        
                        if($(this).is(':checked'))
                        {
                            $('#submitFormBtnDisabled').hide();                            
                            $('#submitFormBtn').show();
                        }
                        else
                        {
                            $('#submitFormBtn').hide();
                            $('#submitFormBtnDisabled').show();
                        }

                    });

                }
                else{
                    $("#validatedForm").hide();
                    info.html("");
                    swal({
                        title:"Info",
                        icon:"info",
                        text:"Record not found. Please check your Registration number or contact support@omniswift.com"
                    });
                    //alert('Record not found. Please check your Registration number or contact support@omniswift.com');
                }
            }
            else{
                $('#info').html("<small class='text-danger'>Something went wrong. Please refresh your browser and try again.</small>");                
            }
            
        });
    });

}
