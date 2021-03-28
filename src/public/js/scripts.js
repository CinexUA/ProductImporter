function loader(action = 'block') {
    const loader = document.getElementById('loader');
    if(loader){
        loader.style.display = action;
    }
}

function showLoader()
{
    loader('block');
}

function hideLoader()
{
    loader('none');
}

const forms = document.querySelectorAll('form');
if(!!forms.length){
    forms.forEach(function (el) {
        el.addEventListener("submit", function(e){
            showLoader(1);
        });
    });
}

jQuery(function($){

    //delete table item
    $(document).on('click','.delete',function(e){
        e.preventDefault();

        if (confirm('Delete: ' + $(this).data('name')+"?")){

            $.ajax({
                url: $(this).data('url'),
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: { "_method": "DELETE" },
                beforeSend: function( xhr ) {
                    showLoader();
                },
                success: function(response){
                    document.location.reload();
                },
                error:function(response, success, failure) {
                    document.location.reload();
                },
                complete: function () {
                    hideLoader();
                }
            });
        }
    });

});
