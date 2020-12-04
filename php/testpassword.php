
<html>
    <head>
        <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
        <script src="../js/password_strength-master/password_strength/password_strength_lightweight.js"></script>
        <link rel="stylesheet" href="../js/password_strength-master/password_strength/password_strength.css">

        <script>
            
            $(document).ready(function($) {
                $('#myPassword').strength_meter();

                /*$('#mySecondPassword').strength_meter({
                    inputClass: 'c_strength_input',
                    strengthMeterClass: 'c_strength_meter',
                    toggleButtonClass: 'c_button_strength'
                });

                $("#myThirdPassword").strength_meter({
                    strengthMeterClass: 't_strength_meter'
                });*/
            });

        </script>
    </head>
    <body>

        <div id="myPassword">
            
        </div>


    </body>
</html>