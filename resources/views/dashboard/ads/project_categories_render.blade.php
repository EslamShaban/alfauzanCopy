<option disabled hidden selected>-- إختر القسم --</option>
@foreach($cats as $cat)
	<option value = "{{$cat->id}}">{{$cat->name_ar}}</option>
@endforeach