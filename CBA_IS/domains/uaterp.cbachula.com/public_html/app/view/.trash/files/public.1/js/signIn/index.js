add_modal('form_validate1', 'กรุณากรอกข้อมูลให้ครบถ้วน');
add_modal('form_validate2', 'รหัสพนักงานหรือรหัสผ่านไม่ถูกต้อง');

$('#employee_no').on('keypress', (e) => { if(e.which === 13)  formValidate(); });
$('#employee_password').on('keypress', (e) => { if(e.which === 13)  formValidate(); });

function formValidate() {
    if($('#employee_no').val() === '' || $('#employee_password').val() === '')
        $('#form_validate1').modal('toggle');
    else
        signIn();
}

function signIn() {
    $.post("/signin/signin", {
        post: true,
        employee_no: $('#employee_no').val(),
        employee_password: $('#employee_password').val()
    }, (result) => {
        if (result === 'valid') {
            location.assign('/');
        } else {
            $('#form_validate2').modal('toggle');
            $('#employee_password').val('');
        }
    });
}