@extends('students.layouts.app')

@section('banner-tab')
    @include('students.fees.bannerTemplate')    
@endsection

@section('filter')
    <form action="{{ url('/students/payment/status') }}" method="GET" class="form-inline">
        <input type="text" name="RRR" class="form-control text-field" style="color: #555; font-weight: bold; padding: 2px; font-size: 13px" autofocus placeholder="Enter RRR" required/>
        <button style="padding: 3px;">Verify RRR</button>
    </form>
@endsection

@section('content')

        @if(empty($outstandingTuitionFees) && empty($outstandingFeeAssignments))
            <h4 class="text-info text-center" style="padding: 40px; background-color: #FBFCFD">Congratulations! You don't have any outstanding fee. &#9786;
             <br/><a href="{{ url('/students/courses') }}">Proceed to course registration.</a>
            </h4> 
        @else
            <div class="table-responsive">
                <table class="table table-hover">

                    <thead class="table-head top-half-pill">
                
                        <tr>
                            <th>#</th>
                            <th>Fee Description</th>
                            <th>Amount</th>
                            <th>Payment Plan</th>
                            <th>Actions</th>
                        </tr>
                
                    </thead>

                    <tbody  class="table-body bottom-half-pill">
                    
                        <!--Outstanding Tuition Fee -->
                            @foreach($outstandingTuitionFees as $item)  
                                <tr>
                                    <td>{{ $sn++ }}.</td>
                                    <td class="text-primary">{{ ucwords($NTIServices::getInfo('fee_types', 'id', $studentInstance->getTuitionFeeDefinitionToBePaid($item->semesterId)->fee_id)->name) }} ({{ $NTIServices::numberToPosition($item->semester) }} Semester - {{$item->academicSession }})</td>
                                    <td>&#8358;{{ number_format($studentInstance->getTuitionFeeDefinitionToBePaid($item->semesterId)->amount, 2)}}</td>
                                    <td>
                                        <span class="mynti-box mynti-upload-text stretchable relative pill">
                                            <select tabindex="18" class="form-control installment-select" name="installment">
                                                <option value="0">Full Payment</option>
                                                <!--
                                                @foreach($NTIServices::jsonToArray($studentInstance->getTuitionFeeDefinitionToBePaid($item->semesterId)->installment) as $key => $install)
                                                <option value="{{ $key }}">{{ $NTIServices::numberToPosition($key) }} Installment - &#8358;{{ number_format($install, 2) }}</option>
                                                @endforeach 
                                                -->                                   
                                            </select>
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ url('students/fees/processpayment') }}" method="POST" target="_blank">
                                                {{ csrf_field() }}
                                            
                                            <input type="hidden" name="tbl" value="fee_definitions"/>
                                            <input type="hidden" name="id" value="{{ $studentInstance->getTuitionFeeDefinitionToBePaid($item->semesterId)->id }}"/>
                                            <input type="hidden" name="installment" value="0"/>
                                            <input type="hidden" name="semesterId" value="{{ $item->semesterId }}"/>
                                            <i class="icon-doc-view"></i><a href="#" class="payNow">Pay Now</a>
                                        </form>
                                    </td>    
                                </tr>   
                            @endforeach

                        <!--Outstanding Fee Assignment-->
                            @foreach($outstandingFeeAssignments as $feeAssignment)
                            
                                <tr>
                                    <td>{{ $sn++ }}</td>
                                    <td class="text-primary" title="{{ $feeAssignment->desc }}">{{ ucwords($NTIServices::getInfo('fee_types', 'id', $feeAssignment->fee_id)->name) }} (Fee Assignment)</td>
                                    <td>&#8358;{{ number_format($feeAssignment->amount, 2) }}</td>
                                    <td>
                                        <span class="mynti-box mynti-upload-text stretchable relative pill">
                                            <select tabindex="18" class="form-control" name="installment">
                                                <option value="0">Full Payment</option>
                                                <!--
                                                @foreach($NTIServices::jsonToArray($feeAssignment->installment) as $key => $install)
                                                <option value="{{ $key }}">{{ $NTIServices::numberToPosition($key) }} Installment - &#8358;{{ number_format($install, 2) }}</option>
                                                @endforeach
                                                -->                                       
                                            </select>
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ url('students/fees/processpayment') }}" method="POST" target="_blank">
                                                {{ csrf_field() }}
                                            <input type="hidden" name="tbl" value="fee_assignments"/>
                                            <input type="hidden" name="id" value="{{ $feeAssignment->id }}"/>
                                            <input type="hidden" name="installment" value="0"/>
                                            <input type="hidden" name="semesterId" value="{{ $feeAssignment->semester_id}}"/>
                                            <i class="icon-doc-view"></i><a href="#" class="payNow">Pay Now</a>
                                        </form>
                                    </td>
                                </tr>    
                            @endforeach

                    </tbody>

                </table>

            </div>    
        @endif

                <script type="text/javascript">
                     swal({
                        title:"Info",
                        text: "Please only make payments for the fees that are applicable to you. Contact support@nti.edu.ng for more info."
                    });
                </script>

                    
@endsection
