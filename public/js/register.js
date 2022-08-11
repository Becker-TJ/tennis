// $(document).ready( function () {
//     $('#register-form').submit(function(e) {
//         e.preventDefault();
//         var enteredPasscode = $('#coach-passcode').val();
//             $.ajax({
//                 type:'POST',
//                 async:false,
//                 url:'/compareCoachPasscode',
//                 data:{enteredPasscode:enteredPasscode},
//                 success:function(data){
//                     if(data.passcodeMatch === true) {
//                         $(this).submit();
//                     } else {
//                         alert('Incorrect Passcode');
//                     }
//                 }
//             });
//     });
// });

