@extends ('layouts.user')




@section('content') 
   
    {{-- Included Home Page --}}
    @include('admin.user.homeinc.homein')

    {{-- Included Feature Page --}}
    @include('admin.user.homeinc.feature')

    {{-- Included Details --}}
    @include('admin.user.homeinc.details')

    {{-- Included Video --}}
    @include('admin.user.homeinc.video')
    


    {{-- Included Pricing --}}
    @include('admin.user.homeinc.pricing')



   
@endsection