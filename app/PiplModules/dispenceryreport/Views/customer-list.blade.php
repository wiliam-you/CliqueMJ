@extends('layouts.vendor')

@section("meta")

	<title>Report</title>

@endsection
@section('content')
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE BREADCRUMB -->
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="{{url('dispensary/dashboard')}}">Dashboard</a>
				</li>
				<li>
					<a href="javascript:void(0)">Report</a>

				</li>
			</ul>

			@if (session('status'))
				<div class="alert alert-success">
					{{ session('status') }}
					<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
				</div>
			@endif
			@if (session('error'))
				<div class="alert alert-danger">
					{{ session('error') }}
					<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
				</div>
			@endif

			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="grey-cascade">
						<div class="portlet-title">
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
							<div class="x_title">

								<div class="clearfix"></div>

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
							<h1>Total Sale: $<span id="total_sale"></span></h1>
							<hr>
							<table class="table table-striped table-bordered table-hover" id="tbl_regusers">
								<thead>
								<tr>
									<th>Patient Name</th>
									<th>Product Name</th>
									<th>Property</th>
									<th>Size</th>
									<th>Quantity</th>
									<th>Price</th>
									<th>Total Price</th>
									<th>Product Sold Date</th>
								</tr>
								</thead>
								<tfoot>
								<tr>
									<th colspan="6" style="text-align:right">Total Sale (per page):</th>
									<th colspan="2"></th>


								</tr>
								</tfoot>
							</table>
						</div>
					</div>

				</div>
			</div>
			<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
			<script>
                var tbl_sale='';
                $(function() {
                    currentFilters = [];
                    tbl_sale = $('#tbl_regusers').DataTable({
                        processing: true,
                        serverSide: true,
                        //bStateSave: true,
                        ajax: {"url":'{{url("/dispencery/report/data")}}',"complete":afterRequestComplete, data: function(d){
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
                                .column( 6 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );

                            // Total over this page
                            pageTotal = api
                                .column( 6, { page: 'current'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );


                            $('#total_sale').text(total);


                            // Update footer
                            $( api.column( 6 ).footer() ).html(
                                '$'+ pageTotal
                            );
                        },
						columnDefs: [{
                            "defaultContent": "-",
                            "targets": "_all"
                        }],
                        columns: [
                            { data: 'patient_name', name: 'patient_name',searchable: true},
                            { data: 'product_name', name: 'product_name',searchable: true},
                            { data: 'property', name: 'property',searchable: true},
                            { data: 'size', name: 'size',searchable: true},
                            { data: 'quantity', name: 'quantity',searchable: true},
                            { data: 'price', name: 'price',searchable: true},
                            { data: 'total', name: 'total',searchable: true},
                            { data: 'created_at', name: 'created_at',searchable: true},



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
