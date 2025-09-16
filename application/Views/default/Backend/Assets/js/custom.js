$(document).ready(function(){
     // CKEDITOR

     const {
         ClassicEditor,
         Essentials,
         Bold,
         Italic,
         Font,
         Paragraph
     } = CKEDITOR;

     ClassicEditor
         .create( document.querySelector( '#content' ), {
             licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NjUwNjU1OTksImp0aSI6IjkzYjdjZGE5LWFlMGUtNDhmOC1iNmFiLTAzMTRjZTdhY2MyNCIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiXSwiZmVhdHVyZXMiOlsiRFJVUCJdLCJ2YyI6IjU4OGZkY2I5In0.ChAFaMaB-NumjNtkeoVO5tkxUrNwxujHU3iqJ7NDShJ0uOZzwNxO3iFZ3CJQJm3JGM8ClIVk_dDtCYIi5Z1uGg', // Create a free account on https://portal.ckeditor.com/checkout?plan=free
             plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
             toolbar: {
                items: [
                    'undo', 'redo',
                    '|',
                    'heading',
                    '|',
                    'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor',
                    '|',
                    'bold', 'italic', 'strikethrough', 'subscript', 'superscript', 'code',
                    '|',
                    'link', 'uploadImage', 'blockQuote', 'codeBlock',
                    '|',
                    'alignment',
                    '|',
                    'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent'
                ],
                shouldNotGroupWhenFull: true
            }
         } )
             .then( editor => {
                 window.editor = editor;
             } )
             .catch( error => {
                 console.error( error );
             } );



     ClassicEditor
     .create( document.querySelector( '#description' ), {
         licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NjUwNjU1OTksImp0aSI6IjkzYjdjZGE5LWFlMGUtNDhmOC1iNmFiLTAzMTRjZTdhY2MyNCIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiXSwiZmVhdHVyZXMiOlsiRFJVUCJdLCJ2YyI6IjU4OGZkY2I5In0.ChAFaMaB-NumjNtkeoVO5tkxUrNwxujHU3iqJ7NDShJ0uOZzwNxO3iFZ3CJQJm3JGM8ClIVk_dDtCYIi5Z1uGg', // Create a free account on https://portal.ckeditor.com/checkout?plan=free
         plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
         toolbar: {
            items: [
                'undo', 'redo',
                '|',
                'heading',
                '|',
                'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor',
                '|',
                'bold', 'italic', 'strikethrough', 'subscript', 'superscript', 'code',
                '|',
                'link', 'uploadImage', 'blockQuote', 'codeBlock',
                '|',
                'alignment',
                '|',
                'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent'
            ],
            shouldNotGroupWhenFull: true
        }
     } )
         .then( editor => {
             window.editor = editor;
         } )
         .catch( error => {
             console.error( error );
         } );

    let $button_search = $('#btn-search');

    let $inputSearchField = $('input[name = search_field]');
    let $inputSearchValue = $('input[name = search_value]');
    let $selectChangeAttr = $('select[name = select_change_attr]');
    let $selectFilter     = $('a[name = select_filter]');

    $('a.select_field').click(function(e){
        e.preventDefault();

        let field       = $(this).data('field');
        let fieldName   = $(this).html();
        $('button.btn-active-field').html(fieldName);
        $inputSearchField.val(field);
    });

    $button_search.click(function(){
        var pathname = window.location.pathname;
        let params = ['filter_status'];
        let searchParams = new URLSearchParams(window.location.search);


        let link = '';

        $.each(params, function(key, param){
            if(searchParams.has(param)){
                link += param + '=' + searchParams.get(param) + '&';
            }
        });

        let searchField = $inputSearchField.val();
        let searchValue = $inputSearchValue.val();

        if(searchValue.replace(/\s/g,"") == ''){
            alert('Type in Search box!!!');
        } else {
            window.location.href = pathname + '?' + link + 'search_field=' + searchField + '&search_value=' + searchValue;
        }
    });

    $('.btn-delete').on('click', function(){
        if(!confirm('Are you sure to DELETE this???'))
            return false;
    });

    $('.status-ajax').on('click', function(){
        let url = $(this).data('url');
        let btn = $(this);
        $.ajax({
            method: "GET",
            url: url,
            dataType:  "json",
            success: function(response) {
                if(response.status == 'inactive'){
                    btn.removeClass('btn-success');
                    btn.addClass('btn-danger');
                    btn.html('Inactive');
                } else {
                    btn.removeClass('btn-danger');
                    btn.addClass('btn-success');
                    btn.html('Active');
                }
                btn.data('url', response.link);
                $.notify("Update Status Successfully", "success");
            }
        });
    });

    $selectChangeAttr.on('change', function(){
        let selectValue = $(this).val();
        let url = $(this).data('url');
        url =  url.replace('value_new', selectValue);
        $.ajax({
            method: "GET",
            url: url,
            dataType:  "json",
            success: function(response) {
                $.notify("Update "+response.message+" Successfully", "success");
            }
        });
    });
});
