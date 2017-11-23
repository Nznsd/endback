@extends('students.layouts.app')

@section('banner-tab')

    @include('students.fees.bannerTemplate')
    
@endsection

@section('content')
@section('content')

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
                    
                        <!-- Loop through other fees array-->
                            @foreach($otherFees as $item)  
                                <tr>
                                    <td>{{ $sn++ }}.</td>
                                    <td class="text-primary">{{ ucwords($item->name) }}</td>
                                    <td>&#8358;{{ number_format($item->amount, 2) }}</td>
                                    <td>
                                        <span class="mynti-box mynti-upload-text stretchable relative pill">
                                            <select tabindex="18" class="form-control installment-select" name="installment">
                                                <option value="0">Full Payment</option>
                                                <!--
                                                @foreach($NTIServices::jsonToArray($item->installment) as $key => $install)
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
                                            <input type="hidden" name="id" value="{{ $item->id }}"/>
                                            <input type="hidden" name="installment" value="0"/>
                                            <input type="hidden" name="semesterId" value="{{ $NTIServices::getCurrentAcademicSessionInfo()->semesterId }}"/>
                                            <i class="icon-doc-view"></i><a href="#" class="payNow">Pay Now</a>
                                        </form>
                                    </td>    
                                </tr>   
                            @endforeach
                    </tbody>

                </table>
            </div>    
                    
@endsection
