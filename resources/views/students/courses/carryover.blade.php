@extends('students.layouts.app')

@section('banner-tab')
    @include('students.courses.bannerTemplate')    
@endsection

@section('filter')
    <form action="{{ url('/students/fees/verifyPayment') }}" method="POST" class="form-inline">
        <label>Filter Level:</label>
        <select name='carryoverFilter' class="form-control">
            {{ $studentInstance->printMyLevelDropdown(false) }}
        </select>
        <span id="info"></span>
    </form>    
@endsection


@section('content')

<div class="table-responsive" id="carryoverTable">

    @if(!$paid)
        <h4 class="text-info text-center" style="padding: 40px; background-color: #FBFCFD">Sorry! You havn't paid your carryover fee.
            <br/><a href="{{ url('/students/fees/others') }}">Proceed to make payment.</a>
        </h4>        
    @else
        
        <form action="{{ url('students/courses/register') }}" method="POST" id="courseForm" target="_blank">            
                
            {{ csrf_field() }}
            <input type="hidden" name="regType" value="carryover" />
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
                                     
                <tbody class='table-body bottom-half-pill' id="tableRows">

                        <script>

                            var coursesArray = @php echo $allCourses; @endphp;
                            
                            var tableRow = '';
                            coursesArray.forEach(function(element) {

                                    var klass = element.level+"-"+element.semester;
                            
                                    tableRow += `<tr class='trow ${klass}'>
                                                    <td><input type='checkbox' name='data[]' value='${element.id}' /></td>            
                                                    <td>${element.code.toUpperCase()}</td>
                                                    <td>${element.title}</td>
                                                    <td>${element.credit_unit}</td>                
                                                    <td>${element.type.charAt(0).toUpperCase() + element.type.slice(1) }</td>
                                                </tr>`;

                                                
                            }, this);

                            document.getElementById('tableRows').innerHTML = tableRow

                        </script>

                </tbody>
                
            </table>
                
            <p style="text-align: right; margin: 10px;">
                <a href="#" id="coSubmit" class="btn capsule mynti-button-calm text-titlecase lighter-size">Register</a>        
            </p>

        </form>               
        
    @endif

</div>

@endsection