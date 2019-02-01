@extends(config("piplmodules.back-view-layout-location"))

@section("meta")

<title>Sale Reports</title>

@endsection

@section('content')
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="page-content-wrapper">
		<div class="page-content">
                    <!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="{{url('admin/dashboard')}}">Dashboard</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="javascript:void(0)">Sale Reports</a>
					
				</li>
                        </ul>
                     @if (session('status'))
                         <div class="alert alert-success">
                              {{ session('status') }}
                              <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                          </div>
                     @endif
                    <div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-list"></i>Sale Reports
							</div>
							<div class="tools">
								<a class="collapse" href="javascript:;" data-original-title="" title="">
								</a>
								<a class="config" data-toggle="modal" href="#portlet-config" data-original-title="" title="">
								</a>
								<a class="reload" href="javascript:;" data-original-title="" title="">
								</a>
								<a class="remove" href="javascript:;" data-original-title="" title="">
								</a>
							</div>
						</div>
                                                 <div class="portlet-body">
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-12">
										<div class="col-md-2">
											<label>Sale By Date: </label>
										</div>

										<div class="col-md-5">
											Start Date: <input class="form-control" type="text" id="start_date" onchange="getData()">
										</div>
										<div class="col-md-5">
											End Date: <input class="form-control" type="text" id="end_date" onchange="getData()">
										</div>

									</div>


								</div>
								<br>
								<div class="row">
									<div class="col-md-6">
										<label>Sale By Product: </label>
										<select class="form-control" id="product" onchange="getData()">
											<option value="">Select Product</option>
											@foreach($all_product as $product)
												<option value="{{$product->id}}">{{$product->name}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-md-6">
										<label>Sale By Dispensary: </label>
										<select class="form-control" id="dispensary" onchange="getData()">
											<option value="">Select Dispensary</option>
											@foreach($all_dispensary as $dispensary)
												<option value="{{$dispensary->user_id}}">{{$dispensary->dispensary_name}}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
													 <hr>
													 <h1>Total Sale: $<span id="total_sale"></span></h1>
							<table class="table table-striped table-bordered table-hover" id="list_testimonial">
							<thead>
							<tr>

                                                                <th>Dispensary Name</th>
                                                                <th>Product Name</th>
                                                                <th>Weight</th>
                                                                <th>Price (per quantity)</th>
                                                                <th>Quantity</th>
																<th>Total Amount</th>
                                                                <th>Sold On Date</th>
                                                        </tr>
							</thead>
								<tfoot>
								<tr>
									<th colspan="5" style="text-align:right">Total Sale (per page):</th>
									<th colspan="2"></th>


								</tr>
								</tfoot>
                                                        </table>

						</div>
					</div>
	
				
			
			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END CONTENT -->
</div>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    var tbl_sale='';
$(function() {
    currentFilters = [];
    tbl_sale = $('#list_testimonial').DataTable({
        processing: true,
        serverSide: true,
         //bStateSave: true,
        ajax: {"url":'{{url("/admin/sale-report-data")}}',"complete":afterRequestComplete, data: function(d){
            d.filters = currentFilters;
        	d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.product = $('#product').val();
            d.dispensary = $('#dispensary').val();
        }},

        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );


			$('#total_sale').text(total);


            // Update footer
            $( api.column( 5 ).footer() ).html(
                '$'+ pageTotal
            );
        },








        columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }],
        columns: [
            { data: 'dispensary_name', name: 'dispensary_name'},
           { data: 'product_name', name: 'product_name'},
           { data: 'weight', name: 'weight'},
           { data: 'price', name: 'price'},
            { data: 'quantity', name: 'quantity' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'sold_date', name: 'sold_date' },



        ],


    });



    var dateFormat = "mm/dd/yy",
        from = $( "#start_date" )
            .datepicker({
                defaultDate: "+1w",
                changeMonth: true,
            })
            .on( "change", function() {
                to.datepicker( "option", "minDate", getDate( this ) );
            }),
        to = $( "#end_date" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
        })
            .on( "change", function() {
                from.datepicker( "option", "maxDate", getDate( this ) );
            });

    function getDate( element ) {
        var date;
        try {
            date = $.datepicker.parseDate( dateFormat, element.value );
        } catch( error ) {
            date = null;
        }

        return date;
    }



});

function getData() {
    if($('#product').val()!='')
    {
        currentFilters.push({'product':$('#product').val()});
    }
    tbl_sale.draw();
}

</script>
@endsection
