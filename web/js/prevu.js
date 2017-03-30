function displayTables(show){

    $('table').hide();

    $('#notices tr').each(
        function(index){
            console.log('show ='+show);
            if (show !=true){
                if( index > 10){
                    $(this).hide();
                }
            }
            else{
                $(this).fadeIn();
                $(".showall").hide();
            }
        }
    );

    $('table').fadeIn(1000);
}


$(document).ready(function()
    {
        $(".tablesorter").tablesorter();
        $('select').material_select();
        $('textarea').trigger('autoresize');
        $('.datepicker').pickadate({
            //selectMonths: true, // Creates a dropdown to control month
            selectYears: 15 // Creates a dropdown of 15 years to control year
        });
    }
);
