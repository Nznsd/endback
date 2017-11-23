@extends('students.layouts.app')

@section('banner-tab')
    @include('students.courses.bannerTemplate')    
@endsection

@section('content')

<div class="table-responsive">

    @if(empty(json_decode($courseHistory) ))
            <h4 class="text-info text-center" style="padding: 40px; background-color: #FBFCFD">You don't have any courses registered. &#9785;
             <br/><a href="{{ url('/students/courses') }}">Register your courses.</a>
            </h4>         
    @else

        <table class="table table-hover">

            <thead class="table-head top-half-pill">

                <tr>
                    <th>#</th>
                    <th>Academic Session</th>
                    <th>Level</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody class="table-body bottom-half-pill">

                @foreach($courseHistory as $item)

                    <tr>
                        <td>{{ $sn++ }}.</td>
                        <td>{{ $NTIServices::numberToPosition($NTIServices::getAcademicInfo($item->semester_id)->semester) }} Semester - {{ $NTIServices::getAcademicInfo($item->semester_id)->academicSession}} </td>
                        <td>{{ $NTIServices::getLevelName($studentInstance->getMyProfile()->programmeId, $studentInstance->getMyLevelIn($item->semester_id)) }}</td>
                        <td>{{ ucwords($item->registration_type) }}</td>
                        <td>
                            <i class="icon-doc-view"></i>
                            <a href="{{ url('/students/course/form/'.encrypt($item->id) ) }}" target="_blank"> Download Course Form</a>
                        </td>
                    </tr>
                    
                @endforeach
                
            </tbody>

        </table>
        
    @endif
    
</div>

@endsection