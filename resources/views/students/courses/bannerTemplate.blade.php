<ul class="list-inline mynti-tabs">
    <li class="{{ @$tab1 }}"><a href="{{ url('/students/courses') }}" class="text-titlecase mynti-tab-item mynti-box">Register Courses</a></li>
    @if($studentInstance->getMyProfile()->programmeId != $NTIServices::basicInfo()->pttpId)
        <li class="{{ @$tab2 }}"><a href="{{ url('/students/courses/carryover') }}" class="text-titlecase mynti-tab-item mynti-box">Carry Over</a></li>
    @endif
    <li class="{{ @$tab3 }}"><a href="{{ url('/students/courses/history') }}" class="text-titlecase mynti-tab-item mynti-box">Course History</a></li>
    <li class="{{ @$tab4 }}"><a href="#" class="text-titlecase mynti-tab-item mynti-box">Course Materials</a></li>
</ul>