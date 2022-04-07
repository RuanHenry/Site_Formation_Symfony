
    $(document).ready(function() {

        $('#add-image').click(function(){
            const index = +$('#widgets-counter').val();
            const tmpl = $('#annonce_images').data('prototype').replace(/_name_/g, index);
            $('#annonce_images').append(tmpl);

            $('#widgets-counter').val(index+1);

            handleDeleteButtons();
        });

        function handleDeleteButtons() {
            $('button[data-action="delete"]').click(function() {
                const target = this.dataset.target;
                $(target).remove();
            });
        }

        function updateCounter() {
            const count = +$('#annonce_images div.form-group').length;

            $('#widgets-counter').val(count);
        }

        updateCounter();
        handleDeleteButtons();
    });
    
