@extends('students.layouts.app')

@section('banner-tab')
    @include('students.courses.bannerTemplate')    
@endsection

@section('content')

<div class="table-responsive">

    @if(!$paid)
            <h4 class="text-info text-center" style="padding: 40px; background-color: #FBFCFD">Sorry! You have some outstanding fees. &#9785;
             <br/><a href="{{ url('/students/fees') }}">Proceed to make payment.</a>
            </h4>         
    @else
        <form action="{{ url('students/courses/register') }}" method="POST" id="courseForm" target="_blank">            

            {{ csrf_field() }}
            <input type="hidden" name="regType" value="normal" />
            <input type="hidden" name="semesterId" value="{{ $semesterId }}" />

            <table class="table table-hover">

                <thead class="table-head top-half-pill">

                    <tr>
                        <th></th>
                        <th>Code</th>
                        <th>Course Title</th>
                        <th>Unit</th>
                        <th>Type</th>
                    </tr>

                </thead>

                <tbody class="table-body bottom-half-pill">

                    @foreach($courses as $item)

                        <tr>

                            <td>
                                @if(empty($registeredCourses) || in_array($item->id, $registeredCourses) )

                                    @php
                                        $totalUnits += $item->credit_unit
                                    @endphp
                                
                                    @if(strtolower($item->type) == 'compulsory')
                                        <input type="checkbox" name="data[]" value="{{ $item->id }}" checked disabled>   
                                        <input type="hidden" name="data[]" value="{{ $item->id }}"/>
                                    @else        
                                        <input type="checkbox" name="data[]" value="{{ $item->id }}" checked>  
                                    @endif      
                                @else
                                    <input type="checkbox" name="data[]" value="{{ $item->id }}">                                            
                                @endif
                            </td>
                            <td>{{ strtoupper($item->code) }}</td>
                            <td>{{ ucwords($item->title) }}</td>
                            <td>{{ $item->credit_unit }}</td>
                            <td>{{ ucwords($item->type) }}</td>
                            
                        </tr>
                        
                    @endforeach

                    <tr>
                    <td></td>
                    <td></td>
                    <td style="text-align: right"><b>TOTAL UNITS: </b></td>
                    <td><b>{{ $totalUnits }}</b><td>
                    </tr>

                </tbody>


            </table>

            <p style="text-align: right; margin: 10px;">
                <a href="#" onclick="getElementById('courseForm').submit(); return false;" class="btn capsule mynti-button-calm text-titlecase lighter-size">Register</a>        
            </p>

        </form>    
    
    @endif
    
</div>

@endsection