<h3 class="text-center fw-bolder">GPA Calculator</h3>
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
    }
    .remove-btn{
        padding: 3px !important;
        line-height: .9rem !important;
    }
    .remove-btn span{
        font-size: .8rem !important;
    }
    @media print{
        .remove-btn{
            display:none;
        }
        #gpaTbl{
            counter-reset:gpaCount;
        }
        #gpaTbl tbody tr td:nth-child(1):before{
            counter-increment:gpaCount;
            content: counter(gpaCount)
        }
    }
</style>
<div class="row">
    <div class="col-lg-8 col-md-10 col-sm-12 col-12 mx-auto">
        <div class="card rounded-0 shadow">
            <div class="card-header rounded-0">
                <div class="card-title">GPA Calculator</div>
            </div>
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <div class="table-responsive" id="GPA">
                        <dl class="row mb-1 mx-0">
                            <dt class="col-auto flex-shrink-1">Student:</dt>
                            <dd class="col-auto flex-shrink-1" contenteditable="true">Enter Student Name Here</dd>
                        </dl>
                        <dl class="row mb-1 mx-0">
                            <dt class="col-auto flex-shrink-1">Semester:</dt>
                            <dd class="col-auto flex-shrink-1" contenteditable="true">1st</dd>
                        </dl>
                        <dl class="row mb-1 mx-0">
                            <dt class="col-auto flex-shrink-1">S.Y.:</dt>
                            <dd class="col-auto flex-shrink-1" contenteditable="true"><?= date("Y") ?></dd>
                        </dl>
                        <table class="table table-sm table-bordered table-striped" id="gpaTbl">
                            <colgroup>
                                <col width="10%">
                                <col width="30%">
                                <col width="15%">
                                <col width="15%">
                                <col width="30%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center">Course/Subject</th>
                                    <th class="text-center">Grade</th>
                                    <th class="text-center">Credit/s</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-center">Sub-Total</th>
                                    <th class="text-end" id="credit-sub">0</th>
                                    <th class="text-end" id="total-sub">0</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-center">Total Grade Percentage</th>
                                    <th colspan="2" id="total-percentage" class="text-center fw-bolder">0%</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-center">Grade Scale</th>
                                    <th colspan="2" id="grade-scale" class="text-center fw-bolder h3">0.0</th>
                                </tr>
                            </tfoot>
                        </table>
                        
                    </div>
                    <div class="mb-3">
                        <div class="row mx-0 justify-content-center align-items-center">
                            <div class="col-lg-4 col-md-6 col-sm-8 mb-2">
                                <button class="btn btn-sm btn-outline-secondary rounded-pill w-100" id="add_row" type="button">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="material-symbols-outlined">add</span>
                                        Add Item
                                    </div>
                                </button>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-8 mb-2">
                                <button class="btn btn-sm btn-primary rounded-pill w-100" id="print" type="button">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="material-symbols-outlined">print</span>
                                        Print
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var get_gtbl_ajax;
    const GPATbl = document.getElementById('gpaTbl')
    const GPATblRow = document.createElement('tr')
          GPATblRow.innerHTML = `<td class="text-center">
                                        <button class="btn btn-outline-danger rounded-0 btn-sm px-1 py-1 remove-btn" type="button" tabindex="-1">
                                            <span class="material-symbols-outlined" style="font-size:.5rem">close</span>
                                        </button>
                                    </td>
                                    <td class=''>
                                        <div contenteditable="true" class="clean-input course"></div>
                                    </td>
                                    <td class='text-center'>
                                        <div contenteditable="true" class="clean-input grade text-end"></div>
                                    </td>
                                    <td class='text-center'>
                                        <div contenteditable="true" class="clean-input credits text-end"></div>
                                    </td>
                                    <td class='text-center'>
                                        <div class="clean-input total text-end">0</div>
                                    </td>`;
    function add_item(){
        var item = GPATblRow.cloneNode(true)
        GPATbl.querySelector('tbody').appendChild(item)
        item.querySelector('.remove-btn').addEventListener('click', e=>{
            e.preventDefault()
            remove_item(item)
        })
        $(item).find('.grade, .credits').on('keyup', function(e){
            calc_grade_total(item)
        })
    }
    function remove_item(el){
        if(confirm(`Are you sure to remove this item from the GPA Table?`) === true){
            el.remove()
            calculate_totals()
        }
    }
    function calc_grade_total(el){
        var grade = (el.querySelector('.grade').innerText > 0 ? el.querySelector('.grade').innerText : 0) || 0
        var credits = (el.querySelector('.credits').innerText > 0 ? el.querySelector('.credits').innerText : 0) || 0
        el.querySelector('.total').innerText = grade * credits
        calculate_totals()
    }
    function calculate_totals(){
        var total_grades = 0
        var total_credits = 0
        $(GPATbl).find('tbody tr').each(function(){
            total_grades += parseFloat( ($(this).find('.total').text() > 0 ? $(this).find('.total').text() : 0) || 0 );
            total_credits += parseFloat( ($(this).find('.credits').text() > 0 ? $(this).find('.credits').text() : 0) || 0 );
        })
        $('#credit-sub').text((total_credits).toLocaleString('en-US', { style:'decimal', maximumFractionDigits:2}))
        $('#total-sub').text((total_grades).toLocaleString('en-US', { style:'decimal', maximumFractionDigits:2}))
        var perc = (total_grades / total_credits);
            perc = perc > 0 ? (total_grades / total_credits).toLocaleString('en-US', { style:'decimal', maximumFractionDigits:2}) : 0
        $('#total-percentage').text(perc + '%')
        if(get_gtbl_ajax != undefined)
            get_gtbl_ajax.abort()
        get_gtbl_ajax = $.ajax({
            url:"Master.php?a=get_scale",
            method:'POST',
            data:{perc: perc},
            dataType:'json',
            error:err=>{
                alert("There's an error occurred while getting the GPA.")
                console.log(err)
            },
            success:function(resp){
                console.log(typeof resp === 'object', typeof resp.scale, typeof resp.letter_grade)
                if(typeof resp === 'object' && typeof resp.scale && typeof resp.letter_grade){
                    var scale = resp.scale
                    var lg = resp.letter_grade
                    $('#grade-scale').html(`${scale} ${lg}`)
                }else{
                    alert("There's an error occurred while getting the GPA.")
                    $('#grade-scale').html(`0.0`)
                    console.log(resp)
                }
            }
        })
    }
    $(function(){
        add_item()
        $('#add_row').click(e=>{
            e.preventDefault()
            add_item()
        })
        $('#print').click(function(e){
            e.preventDefault()
            var _head = '';
            var _baseURL = location.origin+location.pathname
            document.querySelectorAll('link,script, style').forEach((eL)=>{
                if(eL.tagName.toLowerCase() == 'link'){
                    var _href = eL.href
                    _head += `<link rel="stylesheet" href="${_href}" />`
                }
                else if(eL.tagName.toLowerCase() == 'script' && eL.innerText == ""){
                    var _src = eL.src
                    var _script = document.createElement('script')
                    _script.src = _src
                    _head += _script.outerHTML

                }else if(eL.tagName.toLowerCase() == 'style'){
                    _head += eL.outerHTML
                }
            })
            // console.log(_head)
            // return false;
            var _output = $('#GPA').clone().html()
            start_loader()
            var nw = window.open('', '_blank', `height=${window.innerHeight},width=${window.innerWidth}`)
                nw.document.querySelector('head').innerHTML = _head
                nw.document.querySelector('body').innerHTML = _output
                nw.document.close()
                setTimeout(() => {
                    nw.print()
                    setTimeout(() => {
                        end_loader()
                        nw.close()
                    }, 100);
                }, 500);
        })
    })
</script>
