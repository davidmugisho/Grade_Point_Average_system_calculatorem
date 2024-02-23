<h3 class="text-center fw-bolder">GPA Grade Table</h3>
<hr class="mx-auto opacity-100" style="width:50px;height:3px">
<?php 
include("Master.php");  
?>
<style>
    .clean-input {
        width: 100% !important;
        border: unset !important;
        background: transparent !important;
        outline: unset !important;
        box-shadow: none !important;
        text-align:center;
    }
    .remove-btn{
        padding: 3px !important;
        line-height: .9rem !important;
    }
    .remove-btn span{
        font-size: .8rem !important;
    }
</style>
<div class="row">
    <div class="col-lg-8 col-md-10 col-sm-12 col-12 mx-auto">
        <div class="card rounded-0 shadow">
            <div class="card-header rounded-0">
                <div class="card-title">Grade Table Management</div>
            </div>
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <div class="table-responsive">
                        <form action="" id="grade_tbl-form">
                            <table class="table table-sm table-bordered table-striped" id="gradeTBL">
                                <colgroup>
                                    <col width="30%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="30%">
                                    <col width="10%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-center">Letter Grade</th>
                                        <th colspan="2" class="text-center">Percentage Grade</th>
                                        <th rowspan="2" class="text-center">Scale</th>
                                        <th rowspan="2" class="text-center">Action</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">From</th>
                                        <th class="text-center">To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot class="d-none">
                                    <tr class="bg-secondary bg-opacity-25">
                                        <th class="text-center" colspan="5">No data</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="mb-3">
                                <div class="col-lg-4 col-md-6 col-sm-8 mx-auto mb-2">
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill w-100" id="add_row" type="button">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="material-symbols-outlined">add</span>
                                            Add Item
                                        </div>
                                    </button>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-8 mx-auto">
                                    <button class="btn btn-sm btn-primary rounded-pill w-100">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="material-symbols-outlined">save</span>
                                            Save
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const gradeTbl = document.getElementById('gradeTBL')
    const gradeTblRow = document.createElement('tr')
          gradeTblRow.innerHTML = `<td class='text-center'>
                                        <input type="text" class="clean-input" name="grade_letter[]"required>
                                    </td>
                                    <td class='text-center'>
                                        <input type="number" step="any" max="100" min="0" class="clean-input" name="grade_from[]"required>
                                    </td>
                                    <td class='text-center'>
                                        <input type="number" step="any" max="100" min="0" class="clean-input" name="grade_to[]"required>
                                    </td>
                                    <td class='text-center'>
                                        <input type="number" step="any" max="4" min="0" class="clean-input" name="scale[]"required>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-outline-danger rounded-0 btn-sm px-1 py-1 remove-btn" type="button" tabindex="-1">
                                            <span class="material-symbols-outlined" style="font-size:.5rem">close</span>
                                        </button>
                                    </td>`;
    function add_item(){
        var item = gradeTblRow.cloneNode(true)
        gradeTbl.querySelector('tbody').appendChild(item)
        item.querySelector('.remove-btn').addEventListener('click', e=>{
            e.preventDefault()
            remove_item(item)
        })
        if(!gradeTbl.querySelector('tfoot').classList.contains('d-none'))
            gradeTbl.querySelector('tfoot').classList.add('d-none')
    }
    function remove_item(el){
        if(confirm(`Are you sure to remove this item from the Grade Table?`) === true){
            el.remove()
            if(gradeTbl.querySelectorAll('tbody tr').length <= 0){
                if(gradeTbl.querySelector('tfoot').classList.contains('d-none'))
                    gradeTbl.querySelector('tfoot').classList.remove('d-none')
            }
        }
    }
    $(function(){
        start_loader();
        $.ajax({
            url:'Master.php?a=get_grade_tbl',
            dataType:'JSON',
            error:err=>{
                alert("An error occurred while fetch Grade Table Data. Kindly reload this page.")
                end_loader()
                console.error(err)
            },
            success:function(resp){
                if(typeof resp === 'object' && resp.length > 0){
                    resp.forEach(data => {
                        var item = gradeTblRow.cloneNode(true)
                        console.log(data.letter_grade)
                        item.querySelector('[name="grade_letter[]"]').value = data.letter_grade
                        item.querySelector('[name="grade_from[]"]').value = data.grade_from
                        item.querySelector('[name="grade_to[]"]').value = data.grade_to
                        item.querySelector('[name="scale[]"]').value = (data.scale)
                        gradeTbl.querySelector('tbody').appendChild(item)
                        item.querySelector('.remove-btn').addEventListener('click', e=>{
                            e.preventDefault()
                            remove_item(item)
                        })
                    })
                }
            },
            complete: ()=>{
                if(gradeTbl.querySelectorAll('tbody tr').length <= 0){
                    if(gradeTbl.querySelector('tfoot').classList.contains('d-none'))
                        gradeTbl.querySelector('tfoot').classList.remove('d-none')
                }
                end_loader()
            }
        })
        
        $('#add_row').click(function(e){
            e.preventDefault();
            add_item()
        })

        $('#grade_tbl-form').submit(function(e){
            e.preventDefault()
            start_loader()
            $.ajax({
                url: "Master.php?a=save_grade_tbl",
                method:'POST',
                data: $(this).serialize(),
                dataType:'JSON',
                error: err=>{
                    alert("An error occurred while saving the data.")
                    end_loader()
                    console.error(err)
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.reload()
                    }else{
                        if(!!resp.msg)
                            alert(resp.msg);
                        else
                            alert("An error occurred while saving the data.");
                        end_loader()
                        console.error(resp)
                    }
                }
            })
        })
    })
</script>
